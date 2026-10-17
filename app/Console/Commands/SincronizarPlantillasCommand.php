<?php

namespace App\Console\Commands;

use App\Models\Plantilla;
use App\Models\PlantillaComponente;
use App\Models\WhatsappAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class SincronizarPlantillasCommand extends Command
{
    protected $signature = 'sincronizar:plantillas {waba_id}';

    protected $description = 'Comando para consultar todas las platillas de los usuarios en META y agregarlos en la plataforma.';

    /** Tope de páginas de seguridad (100 plantillas por página) */
    private const MAX_PAGINAS = 50;

    public function handle(): int
    {
        try {
            $total = $this->sincronizar($this->argument('waba_id'));
            $this->info("✅ Plantillas sincronizadas correctamente ({$total})");

            return self::SUCCESS;
        } catch (Throwable $e) {
            Log::warning('Sincronización de plantillas fallida', ['waba_id' => $this->argument('waba_id'), 'error' => $e->getMessage()]);
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Trae TODAS las plantillas de la WABA (con paginación) y las refleja en la base de datos.
     * Si Meta no responde bien no se toca nada: así una falla temporal nunca marca todo como eliminado.
     *
     * @return int cantidad de plantillas recibidas de Meta
     */
    public function sincronizar(string $waba_id): int
    {
        $configuraciones = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
            ->where('waba_id', $waba_id)
            ->get();

        $total = 0;
        foreach ($configuraciones as $config) {
            $plantillasMeta = $this->descargar($waba_id, Crypt::decrypt($config->access_token));
            $ids = [];

            foreach ($plantillasMeta as $tpl) {
                try {
                    $this->guardar($tpl, $config->id);
                    $ids[] = $tpl['id'];
                } catch (Throwable $e) {
                    // Una plantilla rara no debe impedir sincronizar las demás
                    Log::warning('Plantilla no sincronizada', ['id' => $tpl['id'] ?? null, 'error' => $e->getMessage()]);
                    if (isset($tpl['id'])) {
                        $ids[] = $tpl['id'];
                    }
                }
            }

            // Las que ya no existen en Meta quedan como eliminadas
            Plantilla::whereNotIn('id', $ids)
                ->where('cod_config', $config->id)
                ->where('status', '!=', Plantilla::ELIMINADO)
                ->update(['status' => Plantilla::ELIMINADO]);

            $total += count($plantillasMeta);
        }

        return $total;
    }

    /** Descarga todas las páginas de /{waba}/message_templates */
    private function descargar(string $waba_id, string $token): array
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        $url = "https://graph.facebook.com/{$version}/{$waba_id}/message_templates";
        $query = ['limit' => 100];
        $todas = [];

        for ($pagina = 0; $url && $pagina < self::MAX_PAGINAS; $pagina++) {
            $response = Http::withToken($token)->timeout(30)->get($url, $query);

            if (! $response->successful()) {
                $mensaje = $response->json('error.message') ?? 'Meta no respondió';
                throw new RuntimeException("No se pudieron leer las plantillas de Meta: {$mensaje}");
            }

            foreach ($response->json('data', []) as $tpl) {
                $todas[] = $tpl;
            }

            // "next" ya trae todos los parámetros de la siguiente página
            $url = $response->json('paging.next');
            $query = [];
        }

        if ($url) {
            throw new RuntimeException('Hay más plantillas de las que se pueden sincronizar de una vez.');
        }

        return $todas;
    }

    private function guardar(array $tpl, string $codConfig): void
    {
        $plantilla = Plantilla::updateOrCreate([
            'id' => $tpl['id'],
            'cod_config' => $codConfig,
        ], [
            'name' => $tpl['name'],
            'language' => $tpl['language'],
            'status' => Plantilla::VALIDAR_VALOR_ESTADO[$tpl['status'] ?? ''] ?? Plantilla::PENDIENTE,
            'category' => Plantilla::VALIDAR_VALOR_CATEGORIA[$tpl['category'] ?? ''] ?? Plantilla::MARKETING,
            'sub_category' => $tpl['sub_category'] ?? null,
            'parameter_format' => Plantilla::VALIDAR_VALOR_FORMATO_PARAMETRO[$tpl['parameter_format'] ?? ''] ?? Plantilla::POSIONAL,
        ]);

        $tipos = [];
        $otros = [];

        foreach ($tpl['components'] ?? [] as $comp) {
            $tipo = PlantillaComponente::VALIDAR_TIPO[$comp['type'] ?? ''] ?? null;

            // Tipos que la app todavía no edita (carrusel, oferta limitada…): se guardan completos para mostrarlos
            if ($tipo === null) {
                $otros[] = $comp;
                continue;
            }

            $tipos[] = $tipo;
            PlantillaComponente::updateOrCreate([
                'plantilla_id' => $plantilla->id,
                'type' => $tipo,
            ], [
                'format' => PlantillaComponente::VALIDAR_FORMATO[$comp['format'] ?? ''] ?? PlantillaComponente::N_A,
                'text' => $comp['text'] ?? null,
                'buttons' => isset($comp['buttons']) ? json_encode($comp['buttons']) : null,
                'example' => isset($comp['example']) ? json_encode($comp['example']) : null,
            ]);
        }

        if ($otros) {
            $tipos[] = PlantillaComponente::OTRO;
            PlantillaComponente::updateOrCreate([
                'plantilla_id' => $plantilla->id,
                'type' => PlantillaComponente::OTRO,
            ], [
                'format' => PlantillaComponente::N_A,
                'text' => null,
                'buttons' => null,
                'example' => json_encode($otros),
            ]);
        }

        // Componentes que ya no existen en Meta
        PlantillaComponente::where('plantilla_id', $plantilla->id)->whereNotIn('type', $tipos)->delete();
    }
}
