<?php

namespace App\Services\AI;

use App\Models\ClasificacionIa;
use App\Models\Etiqueta;
use App\Models\EtiquetaContacto;
use ErrorException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MessageClassifierService
{
    public function clasificar(string $uuid, string $mensaje, string $contactoId)
    {
        $clasificacion = ClasificacionIa::where('estado', ClasificacionIa::ACTIVO)
            ->where('cod_usuario', $uuid)
            ->first();

        try {
            $response = Http::timeout(30)->post('http://127.0.0.1:8001/etiquetar', [
                'mensaje' => $mensaje,
                'prompt_usuario' => $clasificacion?->prompt ?: "Clasificación de Sentimientos: Analiza el sentimiento del mensaje y clasifícalo como 'positivo', 'negativo' o 'neutral'. Considera el tono, las palabras utilizadas y el contexto general.",
                'model_name' => 'gpt-oss:120b-cloud',
                'temperature' => 0.3,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al etiquetar el mensaje',
                    'details' => $response->json(),
                ], $response->status());
            }

            $data = $response->json();

            $etiqueta = Etiqueta::updateOrCreate([
                'slug' => Str::slug($data['etiqueta'], '.'),
                'uuid' => $uuid,
                'cod_empresa' => $uuid,
            ], [
                'nombre' => $data['etiqueta'],
                'estado' => Etiqueta::ACTIVO,
            ])->refresh();

            if (empty($etiqueta->id)) {
                throw new ErrorException('La etiqueta no tiene un ID asignado.');
            }

            EtiquetaContacto::updateOrCreate([
                'cod_contacto' => $contactoId,
                'cod_etiqueta' => $etiqueta->id,
            ], [
                'estado' => EtiquetaContacto::ACTIVO,
            ]);

            return response()->json([
                'success' => true,
                'etiqueta' => $data['etiqueta'],
                'mensaje_original' => $data['mensaje_original'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión con la API',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
