<?php

namespace App\Services\Chatbot;

use App\Models\Chatbots\ChatbotConfiguracion as Cfg;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Configuración general del chatbot de una empresa (pantalla "Configuración" de chatbots):
 * lee/guarda las tablas chatbot_configuracion, _horarios, _palabras_clave, _mensajes, _motores y _motivos_transferencia,
 * y responde las preguntas que el webhook necesita (¿dentro de horario?, ¿palabra clave?, ¿qué motor va primero?…).
 */
class ChatbotConfigService
{
    const MENSAJES_POR_DEFECTO = [
        Cfg::MENSAJE_BIENVENIDA => ['¡Hola! 👋 Soy el asistente virtual de { empresa }. ¿En qué podemos ayudarte hoy?', true],
        Cfg::MENSAJE_FUERA_HORARIO => ['Hola 👋 En este momento estamos fuera de nuestro horario de atención. Te responderemos cuando volvamos a estar disponibles.', true],
        Cfg::MENSAJE_TRANSFERENCIA => ['Perfecto 👍 Voy a transferir tu conversación con uno de nuestros asesores. En un momento te atenderán.', true],
    ];

    const MOTIVOS_POR_DEFECTO = [
        Cfg::MOTIVO_PALABRA_CLAVE => true,
        Cfg::MOTIVO_SOLICITA_ASESOR => true,
        Cfg::MOTIVO_NO_RESUELVE => true,
        Cfg::MOTIVO_ERROR => false,
        Cfg::MOTIVO_SOLICITA_PERSONA => true,
    ];

    /** Frases que el usuario usa para pedir una persona (motivos "solicita un asesor" / "solicita hablar con una persona") */
    const PATRON_ASESOR = '/\b(asesor|asesora|agente|ejecutivo|ejecutiva)\b/u';
    const PATRON_PERSONA = '/(hablar|comunicar(me)?|pasar(me)?|contactar(me)?) con (una |un )?(persona|humano|alguien)|persona real|\bhumano\b/u';

    /* ---------- Lectura / creación ---------- */

    /** Configuración de la empresa; si no existe se crea con los valores por defecto (mensajes, motores, motivos, horario L-V 8-18). */
    public static function paraEmpresa(string $codEmpresa): Cfg
    {
        $cfg = Cfg::where('cod_empresa', $codEmpresa)->where('estado', Cfg::ACTIVO)->first();
        if ($cfg) {
            return $cfg;
        }

        return DB::transaction(function () use ($codEmpresa) {
            $cfg = Cfg::create([
                'cod_empresa' => $codEmpresa,
                'mensaje_no_resuelto' => 'No estoy seguro de poder ayudarte con esta solicitud. Permíteme comunicarte con uno de nuestros asesores.',
            ]);
            $cfg->refresh();

            foreach (self::MENSAJES_POR_DEFECTO as $tipo => [$texto, $activo]) {
                self::insertar('chatbot_mensajes', [
                    'chatbot_configuracion_id' => $cfg->id,
                    'tipo' => $tipo,
                    'titulo' => ['', 'Mensaje de bienvenida', 'Mensaje fuera de horario', 'Mensaje de transferencia'][$tipo],
                    'mensaje' => $texto,
                    'activo' => $activo,
                    'estado' => 1,
                ]);
            }
            foreach ([Cfg::MOTOR_NODOS, Cfg::MOTOR_IA, Cfg::MOTOR_AUTOMATIZACION] as $i => $tipo) {
                self::insertar('chatbot_motores', ['chatbot_configuracion_id' => $cfg->id, 'tipo' => $tipo, 'orden' => $i + 1, 'activo' => true]);
            }
            foreach (self::MOTIVOS_POR_DEFECTO as $motivo => $activo) {
                self::insertar('chatbot_motivos_transferencia', ['configuracion_id' => $cfg->id, 'motivo' => $motivo, 'activo' => $activo]);
            }
            foreach (range(1, 5) as $dia) {
                self::insertar('chatbot_horarios', ['chatbot_configuracion_id' => $cfg->id, 'dia' => $dia, 'hora_inicio' => '08:00:00', 'hora_fin' => '18:00:00', 'activo' => true]);
            }

            return $cfg;
        });
    }

    private static function insertar(string $tabla, array $fila): void
    {
        DB::table($tabla)->insert($fila + ['id' => (string) Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
    }

    /** Todo lo que necesita la pantalla, en un arreglo listo para @json. */
    public static function datos(string $codEmpresa): array
    {
        $cfg = self::paraEmpresa($codEmpresa);
        $id = $cfg->id;

        $horarios = DB::table('chatbot_horarios')->where('chatbot_configuracion_id', $id)->where('activo', true)->orderBy('dia')->orderBy('hora_inicio')->get();
        $diasActivos = $horarios->pluck('dia')->unique()->map(fn ($d) => (int) $d)->values()->all();

        // La pantalla maneja un mismo juego de rangos para todos los días activos (se toman los del primer día activo)
        $primerDia = $diasActivos[0] ?? null;
        $rangos = $horarios->where('dia', $primerDia)->map(fn ($h) => ['a' => substr($h->hora_inicio, 0, 5), 'b' => substr($h->hora_fin, 0, 5)])->values()->all();

        return [
            'activo' => (bool) $cfg->activo,
            'respetar_horario' => (bool) $cfg->respetar_horario,
            'accion_palabra_clave' => (int) $cfg->accion_palabra_clave,
            'coincidencia_aproximada' => (bool) $cfg->coincidencia_aproximada,
            'permitir_transferencia' => (bool) $cfg->permitir_transferencia,
            'tiempo_espera_agente' => (int) $cfg->tiempo_espera_agente,
            'accion_sin_agente' => (int) $cfg->accion_sin_agente,
            'mantener_contexto' => (bool) $cfg->mantener_contexto,
            'tiempo_sesion' => (int) $cfg->tiempo_sesion,
            'tipo_reinicio_conversacion' => (int) $cfg->tipo_reinicio_conversacion,
            'tiempo_respuesta' => (int) $cfg->tiempo_respuesta,
            'mostrar_escribiendo' => (bool) $cfg->mostrar_escribiendo,
            'agrupar_mensajes' => (int) $cfg->agrupar_mensajes,
            'accion_respuesta_no_resuelta' => (int) $cfg->accion_respuesta_no_resuelta,
            'mensaje_no_resuelto' => $cfg->mensaje_no_resuelto,
            'dias' => $diasActivos,
            'rangos' => $rangos ?: [['a' => '08:00', 'b' => '18:00']],
            'palabras' => DB::table('chatbot_palabras_clave')->where('chatbot_configuracion_id', $id)->where('activo', true)->orderBy('created_at')->pluck('palabra')->all(),
            'mensajes' => DB::table('chatbot_mensajes')->where('chatbot_configuracion_id', $id)->orderBy('tipo')
                ->get(['tipo', 'activo', 'mensaje'])->map(fn ($m) => ['tipo' => (int) $m->tipo, 'activo' => (bool) $m->activo, 'mensaje' => (string) $m->mensaje])->all(),
            'motores' => DB::table('chatbot_motores')->where('chatbot_configuracion_id', $id)->orderBy('orden')->pluck('tipo')->map(fn ($t) => (int) $t)->all(),
            'motivos' => DB::table('chatbot_motivos_transferencia')->where('configuracion_id', $id)->pluck('activo', 'motivo')
                ->map(fn ($a) => (bool) $a)->mapWithKeys(fn ($a, $m) => [(int) $m => $a])->all(),
        ];
    }

    /* ---------- Guardado ---------- */

    /** Guarda la configuración que envía la pantalla (ya validada en el controlador). */
    public static function guardar(string $codEmpresa, array $d): void
    {
        $cfg = self::paraEmpresa($codEmpresa);
        $id = $cfg->id;

        DB::transaction(function () use ($cfg, $id, $d) {
            $cfg->update([
                'activo' => (bool) $d['activo'],
                'respetar_horario' => (bool) $d['respetar_horario'],
                'accion_palabra_clave' => (int) $d['accion_palabra_clave'],
                'coincidencia_aproximada' => (bool) $d['coincidencia_aproximada'],
                'permitir_transferencia' => (bool) $d['permitir_transferencia'],
                'tiempo_espera_agente' => (int) $d['tiempo_espera_agente'],
                'accion_sin_agente' => (int) $d['accion_sin_agente'],
                'mantener_contexto' => (bool) $d['mantener_contexto'],
                'tiempo_sesion' => (int) $d['tiempo_sesion'],
                'tipo_reinicio_conversacion' => (int) $d['tipo_reinicio_conversacion'],
                'tiempo_respuesta' => (int) $d['tiempo_respuesta'],
                'mostrar_escribiendo' => (bool) $d['mostrar_escribiendo'],
                'agrupar_mensajes' => (int) $d['agrupar_mensajes'],
                'accion_respuesta_no_resuelta' => (int) $d['accion_respuesta_no_resuelta'],
                'mensaje_no_resuelto' => $d['mensaje_no_resuelto'] ?? null,
            ]);

            // Horario: una fila por día activo y rango
            DB::table('chatbot_horarios')->where('chatbot_configuracion_id', $id)->delete();
            foreach (array_unique(array_map('intval', $d['dias'] ?? [])) as $dia) {
                foreach ($d['rangos'] ?? [] as $r) {
                    self::insertar('chatbot_horarios', ['chatbot_configuracion_id' => $id, 'dia' => $dia, 'hora_inicio' => $r['a'] . ':00', 'hora_fin' => $r['b'] . ':00', 'activo' => true]);
                }
            }

            // Palabras clave
            DB::table('chatbot_palabras_clave')->where('chatbot_configuracion_id', $id)->delete();
            foreach (array_unique(array_map('trim', $d['palabras'] ?? [])) as $p) {
                if ($p !== '') {
                    self::insertar('chatbot_palabras_clave', ['chatbot_configuracion_id' => $id, 'palabra' => mb_substr($p, 0, 150), 'activo' => true]);
                }
            }

            // Mensajes especiales
            foreach ($d['mensajes'] ?? [] as $m) {
                DB::table('chatbot_mensajes')->where('chatbot_configuracion_id', $id)->where('tipo', (int) $m['tipo'])
                    ->update(['mensaje' => $m['mensaje'], 'activo' => (bool) $m['activo'], 'updated_at' => now()]);
            }

            // Orden de los motores
            foreach (array_values($d['motores'] ?? []) as $i => $tipo) {
                DB::table('chatbot_motores')->where('chatbot_configuracion_id', $id)->where('tipo', (int) $tipo)->update(['orden' => $i + 1, 'updated_at' => now()]);
            }

            // Motivos de transferencia
            foreach ($d['motivos'] ?? [] as $motivo => $activo) {
                DB::table('chatbot_motivos_transferencia')->where('configuracion_id', $id)->where('motivo', (int) $motivo)
                    ->update(['activo' => (bool) $activo, 'updated_at' => now()]);
            }
        });
    }

    /* ---------- Consultas que usa el bot ---------- */

    public static function dentroDeHorario(Cfg $cfg, ?Carbon $ahora = null): bool
    {
        if (!$cfg->respetar_horario) {
            return true;
        }

        $ahora = $ahora ?: now();

        return DB::table('chatbot_horarios')
            ->where('chatbot_configuracion_id', $cfg->id)->where('activo', true)
            ->where('dia', $ahora->dayOfWeekIso)
            ->where('hora_inicio', '<=', $ahora->format('H:i:s'))
            ->where('hora_fin', '>=', $ahora->format('H:i:s'))
            ->exists();
    }

    /** Mensaje especial activo ya con las variables { nombre } { empresa } { fecha } reemplazadas; null si está apagado. */
    public static function mensaje(Cfg $cfg, int $tipo, array $vars = []): ?string
    {
        $m = DB::table('chatbot_mensajes')->where('chatbot_configuracion_id', $cfg->id)->where('tipo', $tipo)->where('activo', true)->first();
        if (!$m || trim((string) $m->mensaje) === '') {
            return null;
        }

        $vars += ['fecha' => now()->format('d/m/Y')];
        $texto = (string) $m->mensaje;
        foreach ($vars as $k => $v) {
            $texto = preg_replace('/\{\s*' . preg_quote($k, '/') . '\s*\}/u', (string) $v, $texto);
        }

        return $texto;
    }

    /** Tipos de motor en el orden de prioridad que eligió el cliente. */
    public static function motoresEnOrden(Cfg $cfg): array
    {
        $orden = DB::table('chatbot_motores')->where('chatbot_configuracion_id', $cfg->id)->where('activo', true)->orderBy('orden')->pluck('tipo')->map(fn ($t) => (int) $t)->all();

        return $orden ?: [Cfg::MOTOR_NODOS, Cfg::MOTOR_IA, Cfg::MOTOR_AUTOMATIZACION];
    }

    public static function motivoActivo(Cfg $cfg, int $motivo): bool
    {
        $a = DB::table('chatbot_motivos_transferencia')->where('configuracion_id', $cfg->id)->where('motivo', $motivo)->value('activo');

        return $a === null ? (self::MOTIVOS_POR_DEFECTO[$motivo] ?? false) : (bool) $a;
    }

    /** ¿El texto contiene alguna palabra clave configurada? Devuelve la palabra o null. */
    public static function palabraClave(Cfg $cfg, string $texto): ?string
    {
        $t = self::normalizar($texto);
        if ($t === '') {
            return null;
        }
        $palabrasTexto = preg_split('/\s+/', $t);

        foreach (DB::table('chatbot_palabras_clave')->where('chatbot_configuracion_id', $cfg->id)->where('activo', true)->pluck('palabra') as $p) {
            $n = self::normalizar($p);
            if ($n === '') continue;

            if (str_contains($t, $n)) {
                return $p;
            }

            // Coincidencia aproximada: solo para palabras sueltas (variaciones: "cotizacion" ~ "cotizar")
            if ($cfg->coincidencia_aproximada && !str_contains($n, ' ')) {
                foreach ($palabrasTexto as $w) {
                    if (self::parecidas($w, $n)) {
                        return $p;
                    }
                }
            }
        }

        return null;
    }

    private static function parecidas(string $a, string $b): bool
    {
        $min = min(strlen($a), strlen($b));
        if ($min < 4) {
            return $a === $b;
        }
        if (levenshtein($a, $b) <= max(1, intdiv($min, 5))) {
            return true;
        }
        $prefijo = 0;
        while ($prefijo < $min && $a[$prefijo] === $b[$prefijo]) {
            $prefijo++;
        }

        return $prefijo >= 5 && $prefijo >= 0.7 * $min;
    }

    /** Minúsculas, sin tildes ni signos. */
    public static function normalizar(string $s): string
    {
        $s = Str::ascii(mb_strtolower($s));

        return trim(preg_replace('/\s+/', ' ', preg_replace('/[^a-z0-9 ]/', ' ', $s)));
    }

    /** Motivo de transferencia por frase ("quiero un asesor", "hablar con una persona") si ese motivo está activo. */
    public static function motivoPorFrase(Cfg $cfg, string $texto): ?int
    {
        $t = mb_strtolower($texto);

        if (preg_match(self::PATRON_PERSONA, $t) && self::motivoActivo($cfg, Cfg::MOTIVO_SOLICITA_PERSONA)) {
            return Cfg::MOTIVO_SOLICITA_PERSONA;
        }
        if (preg_match(self::PATRON_ASESOR, $t) && self::motivoActivo($cfg, Cfg::MOTIVO_SOLICITA_ASESOR)) {
            return Cfg::MOTIVO_SOLICITA_ASESOR;
        }

        return null;
    }
}
