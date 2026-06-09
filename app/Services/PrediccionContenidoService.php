<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Contacto;
use Carbon\Carbon;

class PrediccionContenidoService
{
    protected $apiUrl;
    protected $timeout;
    protected $usarPrediccionRapida = false; // Flag para usar predicción rápida

    public function __construct()
    {
        $this->apiUrl = env('PREDICCION_CONTENIDO_API_URL', 'http://localhost:8002');
        $this->timeout = 90;
    }

    /**
     * Obtener historial de contactos en el formato requerido (OPTIMIZADO)
     * Usa una sola query con agrupación
     */
    public function obtenerHistorialContactos($contactosIds = null, $meses = 2)
    {
        $fechaInicio = Carbon::now()->subMonths($meses);

        // Query optimizada compatible con todas las versiones de MySQL
        $query = Contacto::selectRaw(
                'contactos.id,
                COALESCE(NULLIF(CONCAT(TRIM(contactos.nombre), " ", TRIM(contactos.apellido)), " "), "Sin nombre") AS nombre_cliente,
                envios_campanas.telefono,
                COALESCE(campanas.nombre, "Sin nombre") AS nombre_campana,
                COALESCE(campanas.contenido, "") AS contenido_campana,
                envios_campanas.apertura,
                envios_campanas.fecha_apertura,
                campanas.fecha_envio'
            )
            ->join('envios_campanas', 'envios_campanas.cod_contacto', '=', 'contactos.id')
            ->join('campanas', 'campanas.id', '=', 'envios_campanas.cod_campana')
            ->where('contactos.estado', Contacto::ACTIVO)
            ->where('campanas.fecha_envio', '>=', $fechaInicio);

        if ($contactosIds && is_array($contactosIds)) {
            $query->whereIn('contactos.id', $contactosIds);
        }

        $datos = $query
            ->orderBy('contactos.id')
            ->orderBy('campanas.fecha_envio', 'desc')
            ->get();

        // Agrupar manualmente en PHP (más compatible)
        $contactosAgrupados = [];

        foreach ($datos as $dato) {
            $contactoId = $dato->id;

            if (!isset($contactosAgrupados[$contactoId])) {
                $contactosAgrupados[$contactoId] = [
                    'id' => $dato->id,
                    'nombre_cliente' => $dato->nombre_cliente,
                    'telefono' => $dato->telefono,
                    'envios' => []
                ];
            }

            $contactosAgrupados[$contactoId]['envios'][] = [
                'nombre_campana' => $dato->nombre_campana,
                'contenido_campana' => $dato->contenido_campana,
                'apertura' => (int)$dato->apertura,
                'fecha_apertura' => $dato->fecha_apertura,
                'fecha_envio' => $dato->fecha_envio,
            ];
        }

        return array_values($contactosAgrupados);
    }

    /**
     * Predicción rápida sin API externa (para grandes volúmenes)
     * Procesa 15,000 contactos en ~5 segundos
     */
    protected function predecirRapidoLocal($contactosIds, string $contenidoMensaje)
    {
        $inicioTiempo = microtime(true);
        $fechaInicio = Carbon::now()->subMonths(2);

        try {
            // Una sola query optimizada con todas las métricas
            $metricas = DB::table('contactos as c')
                ->select([
                    'c.id',
                    'c.telefono',
                    DB::raw('COALESCE(NULLIF(CONCAT(TRIM(c.nombre), " ", TRIM(c.apellido)), " "), "Sin nombre") AS nombre_cliente'),

                    // Métricas básicas
                    DB::raw('COUNT(ec.id) as total_envios'),
                    DB::raw('SUM(CASE WHEN ec.apertura = 1 THEN 1 ELSE 0 END) as total_aperturas'),
                    DB::raw('COALESCE(SUM(CASE WHEN ec.apertura = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(ec.id), 0), 0.5) as tasa_apertura'),

                    // Tiempo promedio hasta apertura
                    DB::raw('AVG(CASE WHEN ec.apertura = 1 THEN TIMESTAMPDIFF(MINUTE, cam.fecha_envio, ec.fecha_apertura) END) as tiempo_promedio_apertura'),

                    // Análisis de contenido
                    DB::raw('SUM(CASE WHEN ec.apertura = 1 AND cam.contenido REGEXP "[😀-🙏🌀-🗿🚀-🛿🇦-🇿]" THEN 1 ELSE 0 END) as aperturas_con_emojis'),
                    DB::raw('SUM(CASE WHEN ec.apertura = 1 AND LENGTH(cam.contenido) < 200 THEN 1 ELSE 0 END) as aperturas_cortas'),

                    // Recencia
                    DB::raw('DATEDIFF(NOW(), MAX(cam.fecha_envio)) as dias_sin_contacto')
                ])
                ->leftJoin('envios_campanas as ec', 'ec.cod_contacto', '=', 'c.id')
                ->leftJoin('campanas as cam', function($join) use ($fechaInicio) {
                    $join->on('cam.id', '=', 'ec.cod_campana')
                         ->where('cam.fecha_envio', '>=', $fechaInicio);
                })
                ->whereIn('c.id', $contactosIds)
                ->where('c.estado', Contacto::ACTIVO)
                ->groupBy('c.id', 'c.telefono', 'c.nombre', 'c.apellido')
                ->get();

            // Analizar contenido del mensaje nuevo (una sola vez)
            $scoreContenido = $this->analizarContenido($contenidoMensaje);

            // Calcular scores para cada contacto
            $resultados = [];
            foreach ($metricas as $m) {
                $scoreHistorial = $m->tasa_apertura;

                // Ajustes basados en comportamiento
                $ajustes = 0;

                // Si le gustan emojis y el mensaje tiene emojis
                if ($scoreContenido['tiene_emojis'] && $m->total_aperturas > 0) {
                    $prefEmojis = $m->aperturas_con_emojis / max($m->total_aperturas, 1);
                    $ajustes += ($prefEmojis - 0.5) * 0.15;
                }

                // Si prefiere mensajes cortos
                if ($scoreContenido['es_corto'] && $m->total_aperturas > 0) {
                    $prefCortos = $m->aperturas_cortas / max($m->total_aperturas, 1);
                    $ajustes += ($prefCortos - 0.5) * 0.1;
                }

                // Penalizar si contacto muy reciente
                if ($m->dias_sin_contacto < 3) {
                    $ajustes -= 0.08;
                } elseif ($m->dias_sin_contacto > 30) {
                    $ajustes += 0.05;
                }

                // Usuario muy activo (abre rápido)
                if ($m->tiempo_promedio_apertura && $m->tiempo_promedio_apertura < 60) {
                    $ajustes += 0.08;
                }

                // Score final
                $scoreFinal = ($scoreHistorial * 0.6) + ($scoreContenido['score_base'] * 0.4) + $ajustes;
                $scoreFinal = max(0.0, min(1.0, $scoreFinal));

                $nivelConfianza = $scoreFinal >= 0.7 ? 'Alta' : ($scoreFinal >= 0.4 ? 'Media' : 'Baja');

                $resultados[] = [
                    'id' => $m->id,
                    'telefono' => $m->telefono,
                    'nombre_cliente' => $m->nombre_cliente,
                    'probabilidad_apertura' => round($scoreFinal, 3),
                    'nivel_confianza' => $nivelConfianza,
                    'razon_prediccion' => $this->generarRazon($m, $scoreContenido, $scoreFinal),
                    'recomendaciones' => $this->generarRecomendaciones($m, $scoreContenido),
                    'estadisticas_personales' => [
                        'tasa_apertura_historica' => round($scoreHistorial, 3),
                        'total_envios' => $m->total_envios ?? 0,
                        'envios_abiertos' => $m->total_aperturas ?? 0,
                        'engagement' => $m->tiempo_promedio_apertura < 60 ? 'Rápido' : 'Normal'
                    ],
                    'mejor_horario' => 'tarde',
                    'palabras_clave_efectivas' => $scoreContenido['palabras_clave']
                ];
            }

            // Ordenar por probabilidad
            usort($resultados, fn($a, $b) => $b['probabilidad_apertura'] <=> $a['probabilidad_apertura']);

            $tiempoTotal = round((microtime(true) - $inicioTiempo) * 1000, 2);

            // Calcular estadísticas
            $probs = array_column($resultados, 'probabilidad_apertura');
            $estadisticas = [
                'probabilidad_promedio' => !empty($probs) ? round(array_sum($probs) / count($probs), 3) : 0,
                'probabilidad_maxima' => !empty($probs) ? max($probs) : 0,
                'probabilidad_minima' => !empty($probs) ? min($probs) : 0,
                'contactos_alta_confianza' => count(array_filter($resultados, fn($r) => $r['nivel_confianza'] === 'Alta')),
                'contactos_media_confianza' => count(array_filter($resultados, fn($r) => $r['nivel_confianza'] === 'Media')),
                'contactos_baja_confianza' => count(array_filter($resultados, fn($r) => $r['nivel_confianza'] === 'Baja')),
                'tasa_apertura_historica_promedio' => !empty($metricas) ? round(collect($metricas)->avg('tasa_apertura'), 3) : 0
            ];

            return [
                'success' => true,
                'data' => [
                    'resultados' => $resultados,
                    'estadisticas_generales' => $estadisticas,
                    'mensaje_analizado' => substr($contenidoMensaje, 0, 100) . '...',
                    'fecha_analisis' => now()->toISOString(),
                    'total_contactos' => count($resultados),
                    'tiempo_procesamiento_ms' => $tiempoTotal,
                    'modo' => 'rapido_local'
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error en predecirRapidoLocal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Analizar contenido del mensaje
     */
    protected function analizarContenido($contenido)
    {
        $tieneEmojis = preg_match('/[\x{1F300}-\x{1F9FF}\x{2600}-\x{26FF}]/u', $contenido);
        $longitud = mb_strlen($contenido);
        $esCorto = $longitud < 200;

        // Palabras clave
        $palabrasOferta = ['descuento', 'oferta', 'promoción', 'gratis', 'regalo', '2x1', '%'];
        $palabrasUrgencia = ['hoy', 'ahora', 'urgente', 'último', 'pronto'];

        $tieneOferta = false;
        $tieneUrgencia = false;
        $palabrasEncontradas = [];

        foreach ($palabrasOferta as $palabra) {
            if (stripos($contenido, $palabra) !== false) {
                $tieneOferta = true;
                $palabrasEncontradas[] = $palabra;
            }
        }

        foreach ($palabrasUrgencia as $palabra) {
            if (stripos($contenido, $palabra) !== false) {
                $tieneUrgencia = true;
                $palabrasEncontradas[] = $palabra;
            }
        }

        // Calcular score base
        $scoreBase = 0.5;
        if ($tieneEmojis) $scoreBase += 0.08;
        if ($tieneOferta) $scoreBase += 0.12;
        if ($tieneUrgencia) $scoreBase += 0.05;
        if ($esCorto) $scoreBase += 0.05;

        return [
            'tiene_emojis' => $tieneEmojis,
            'es_corto' => $esCorto,
            'tiene_oferta' => $tieneOferta,
            'tiene_urgencia' => $tieneUrgencia,
            'score_base' => min($scoreBase, 1.0),
            'palabras_clave' => $palabrasEncontradas
        ];
    }

    /**
     * Generar razón de la predicción
     */
    protected function generarRazon($metrica, $contenido, $score)
    {
        $tasa = $metrica->tasa_apertura * 100;

        $razon = "Contacto con tasa histórica de apertura del {$tasa}%. ";

        if ($score >= 0.7) {
            $razon .= "Alta probabilidad debido a buen historial y mensaje optimizado.";
        } elseif ($score >= 0.4) {
            $razon .= "Probabilidad media. El mensaje es adecuado para este contacto.";
        } else {
            $razon .= "Baja probabilidad. Considerar personalización o esperar para contactar.";
        }

        return $razon;
    }

    /**
     * Generar recomendaciones
     */
    protected function generarRecomendaciones($metrica, $contenido)
    {
        $recs = [];

        if ($metrica->dias_sin_contacto < 3) {
            $recs[] = "Contacto muy reciente. Espera unos días.";
        }

        if ($contenido['tiene_emojis'] && $metrica->total_aperturas > 0) {
            $prefEmojis = $metrica->aperturas_con_emojis / max($metrica->total_aperturas, 1);
            if ($prefEmojis < 0.3) {
                $recs[] = "Este contacto no responde bien a emojis.";
            }
        }

        if (empty($recs)) {
            $recs[] = "Mensaje adecuado para este contacto.";
        }

        return $recs;
    }

    /**
     * Predecir apertura - Método principal
     * Automáticamente usa predicción rápida para grandes volúmenes
     */
    public function predecirApertura($contactosIds, string $contenidoMensaje, string $nombreCampana = null)
    {
        if (!is_array($contactosIds)) {
            $contactosIds = [$contactosIds];
        }

        $totalContactos = count($contactosIds);

        // Si hay muchos contactos O está habilitado el modo rápido, usar predicción local
        if ($this->usarPrediccionRapida || $totalContactos > 50) {
            Log::info("Usando predicción rápida local", ['contactos' => $totalContactos]);
            return $this->predecirRapidoLocal($contactosIds, $contenidoMensaje);
        }

        // Para pocos contactos, usar API de IA (más detallada pero lenta)
        return $this->predecirConIA($contactosIds, $contenidoMensaje, $nombreCampana);
    }

    /**
     * Predicción con IA (método original, para pocos contactos)
     */
    protected function predecirConIA($contactosIds, string $contenidoMensaje, string $nombreCampana = null)
    {
        try {
            $contactosHistorial = $this->obtenerHistorialContactos($contactosIds);

            if (empty($contactosHistorial)) {
                return [
                    'success' => false,
                    'error' => 'No se encontró historial para los contactos especificados'
                ];
            }

            $payload = [
                'contactos_historial' => $contactosHistorial,
                'mensaje_nuevo' => [
                    'contenido_campana' => $contenidoMensaje,
                    'nombre_campana' => $nombreCampana
                ]
            ];

            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/predecir", $payload);

            if ($response->successful()) {
                $data = $response->json();
                // $this->guardarPredicciones($data['resultados'], $contenidoMensaje);

                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            Log::error('Error en API de predicción', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Error al obtener predicciones',
                'details' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('Excepción en predecirConIA', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Error de conexión',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener historial de un solo contacto (OPTIMIZADO)
     */
    public function obtenerHistorialContacto($contactoId, $meses = 2)
    {
        $resultado = $this->obtenerHistorialContactos([$contactoId], $meses);
        return !empty($resultado) ? $resultado[0] : null;
    }

    /**
     * Predecir apertura para un solo contacto
     */
    public function predecirAperturaContacto(string $contactoId, string $contenidoMensaje, string $nombreCampana = null)
    {
        $resultado = $this->predecirApertura([$contactoId], $contenidoMensaje, $nombreCampana);

        if ($resultado['success'] && !empty($resultado['data']['resultados'])) {
            return [
                'success' => true,
                'data' => $resultado['data']['resultados'][0],
                'estadisticas' => $resultado['data']['estadisticas_generales']
            ];
        }

        return $resultado;
    }

    /**
     * Guardar predicciones en base de datos
     */
    protected function guardarPredicciones(array $predicciones, string $contenido)
    {
        foreach ($predicciones as $prediccion) {
            DB::table('predicciones_apertura')->insert([
                'telefono' => $prediccion['telefono'],
                'nombre_cliente' => $prediccion['nombre_cliente'],
                'contenido_mensaje' => $contenido,
                'probabilidad_apertura' => $prediccion['probabilidad_apertura'],
                'nivel_confianza' => $prediccion['nivel_confianza'],
                'razon_prediccion' => $prediccion['razon_prediccion'],
                'recomendaciones' => json_encode($prediccion['recomendaciones']),
                'mejor_horario' => $prediccion['mejor_horario'] ?? null,
                'palabras_clave_efectivas' => json_encode($prediccion['palabras_clave_efectivas']),
                'tasa_apertura_historica' => $prediccion['estadisticas_personales']['tasa_apertura_historica'],
                'fecha_prediccion' => now(),
                'created_at' => now()
            ]);
        }
    }

    /**
     * Analizar campaña completa antes de enviar
     */
    public function analizarCampana(int $campanaId)
    {
        try {
            // Obtener datos de la campaña
            $campana = DB::table('campanas')->where('id', $campanaId)->first();

            if (!$campana) {
                return [
                    'success' => false,
                    'error' => 'Campaña no encontrada'
                ];
            }

            // Obtener IDs de contactos de la campaña
            $contactosIds = DB::table('envios_campanas')
                ->where('cod_campana', $campanaId)
                ->distinct()
                ->pluck('cod_contacto')
                ->toArray();

            if (empty($contactosIds)) {
                return [
                    'success' => false,
                    'error' => 'No hay contactos en la campaña'
                ];
            }

            // Predecir
            $resultado = $this->predecirApertura(
                $contactosIds,
                $campana->contenido,
                $campana->nombre
            );

            if (!$resultado['success']) {
                return $resultado;
            }

            // Agregar análisis adicional
            $data = $resultado['data'];
            $resultados = $data['resultados'];
            $stats = $data['estadisticas_generales'];

            return [
                'success' => true,
                'campana' => [
                    'id' => $campanaId,
                    'nombre' => $campana->nombre,
                    'contenido' => $campana->contenido
                ],
                'analisis' => [
                    'score_general' => $stats['probabilidad_promedio'],
                    'contactos_recomendados' => array_filter($resultados, function($r) {
                        return $r['nivel_confianza'] === 'Alta' || $r['nivel_confianza'] === 'Media';
                    }),
                    'contactos_no_recomendados' => array_filter($resultados, function($r) {
                        return $r['nivel_confianza'] === 'Baja';
                    }),
                    'mejora_vs_historico' => ($stats['probabilidad_promedio'] - $stats['tasa_apertura_historica_promedio']) * 100
                ],
                'estadisticas' => $stats,
                'predicciones_detalladas' => $resultados
            ];

        } catch (\Exception $e) {
            Log::error('Error en analizarCampana', [
                'campana_id' => $campanaId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Optimizar contactos de una campaña
     * Marca contactos con baja probabilidad para no enviarles
     */
    public function optimizarCampana(int $campanaId, float $probabilidadMinima = 0.5)
    {
        try {
            // Analizar campaña
            $analisis = $this->analizarCampana($campanaId);

            if (!$analisis['success']) {
                return $analisis;
            }

            $resultados = $analisis['predicciones_detalladas'];

            // Filtrar por probabilidad mínima
            $contactosOptimizados = array_filter($resultados, function($r) use ($probabilidadMinima) {
                return $r['probabilidad_apertura'] >= $probabilidadMinima;
            });

            $idsOptimizados = array_column($contactosOptimizados, 'id');

            // Actualizar envios_campanas - marcar para no enviar
            DB::table('envios_campanas')
                ->where('cod_campana', $campanaId)
                ->update(['incluir_en_envio' => false]);

            if (!empty($idsOptimizados)) {
                DB::table('envios_campanas')
                    ->where('cod_campana', $campanaId)
                    ->whereIn('cod_contacto', $idsOptimizados)
                    ->update([
                        'incluir_en_envio' => true,
                        'fecha_optimizacion' => now()
                    ]);
            }

            return [
                'success' => true,
                'contactos_originales' => count($resultados),
                'contactos_optimizados' => count($contactosOptimizados),
                'contactos_removidos' => count($resultados) - count($contactosOptimizados),
                'probabilidad_promedio_antes' => $analisis['estadisticas']['tasa_apertura_historica_promedio'],
                'probabilidad_promedio_despues' => $analisis['estadisticas']['probabilidad_promedio'],
                'mejora_estimada' => $analisis['analisis']['mejora_vs_historico']
            ];

        } catch (\Exception $e) {
            Log::error('Error en optimizarCampana', [
                'campana_id' => $campanaId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Comparar efectividad de dos mensajes (A/B Testing)
     */
    public function compararMensajes(string $mensajeA, string $mensajeB, array $contactosIds)
    {
        try {
            // Predecir para mensaje A
            $resultadoA = $this->predecirApertura($contactosIds, $mensajeA, 'Versión A');

            // Predecir para mensaje B
            $resultadoB = $this->predecirApertura($contactosIds, $mensajeB, 'Versión B');

            if (!$resultadoA['success'] || !$resultadoB['success']) {
                return [
                    'success' => false,
                    'error' => 'Error al comparar mensajes'
                ];
            }

            $statsA = $resultadoA['data']['estadisticas_generales'];
            $statsB = $resultadoB['data']['estadisticas_generales'];

            $probA = $statsA['probabilidad_promedio'];
            $probB = $statsB['probabilidad_promedio'];

            return [
                'success' => true,
                'comparacion' => [
                    'mensaje_a' => [
                        'contenido' => substr($mensajeA, 0, 100) . '...',
                        'probabilidad_promedio' => $probA,
                        'contactos_alta_confianza' => $statsA['contactos_alta_confianza'],
                        'contactos_media_confianza' => $statsA['contactos_media_confianza'],
                        'contactos_baja_confianza' => $statsA['contactos_baja_confianza']
                    ],
                    'mensaje_b' => [
                        'contenido' => substr($mensajeB, 0, 100) . '...',
                        'probabilidad_promedio' => $probB,
                        'contactos_alta_confianza' => $statsB['contactos_alta_confianza'],
                        'contactos_media_confianza' => $statsB['contactos_media_confianza'],
                        'contactos_baja_confianza' => $statsB['contactos_baja_confianza']
                    ],
                    'ganador' => $probA > $probB ? 'A' : ($probB > $probA ? 'B' : 'Empate'),
                    'diferencia' => abs($probA - $probB),
                    'mejora_porcentual' => $probA != 0 && $probB != 0
                        ? (max($probA, $probB) / min($probA, $probB) - 1) * 100
                        : 0
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error en compararMensajes', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
