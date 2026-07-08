<?php

namespace App\Services\Campanas;

use App\Models\Contacto;

class PrediccionContenidoService
{
    public function predecir(
        Contacto $contacto,
        string $contenido
    ): array {

        $tasaHistorica = $this->obtenerTasaHistorica(
            $contacto
        );

        $scoreContenido = $this->analizarContenido(
            $contenido
        );

        $probabilidad =
            ($tasaHistorica * 0.60)
            +
            ($scoreContenido * 0.40);

        return [
            'contacto_id' => $contacto->id,
            'probabilidad' => round($probabilidad, 2),
            'nivel' => $this->nivelConfianza(
                $probabilidad
            ),
            'mejor_hora' => $this->obtenerMejorHora(
                $contacto
            ),
            'tasa_historica' => round(
                $tasaHistorica * 100,
                2
            ),
        ];
    }

    private function obtenerTasaHistorica(
        Contacto $contacto
    ): float {

        $total = $contacto->enviosCampanas()
            ->count();

        if (!$total) {
            return 0.5;
        }

        $abiertos = $contacto->enviosCampanas()
            ->where('apertura', 1)
            ->count();

        return $abiertos / $total;
    }

    private function nivelConfianza(
        float $score
    ): string {

        if ($score >= 0.7) {
            return 'Alta';
        }

        if ($score >= 0.4) {
            return 'Media';
        }

        return 'Baja';
    }

    private function analizarContenido(
        string $contenido
    ): float {

        $score = 0.50;

        $texto = mb_strtolower($contenido);

        $palabrasOferta = [
            'descuento',
            'oferta',
            'promoción',
            'gratis',
            'regalo'
        ];

        $palabrasUrgencia = [
            'hoy',
            'ahora',
            'último',
            'urgente'
        ];

        if (preg_match('/[\x{1F300}-\x{1FAFF}]/u', $texto)) {
            $score += 0.08;
        }

        foreach ($palabrasOferta as $palabra) {
            if (str_contains($texto, $palabra)) {
                $score += 0.12;
                break;
            }
        }

        foreach ($palabrasUrgencia as $palabra) {
            if (str_contains($texto, $palabra)) {
                $score += 0.05;
                break;
            }
        }

        if (mb_strlen($texto) < 200) {
            $score += 0.05;
        }

        return min(1, $score);
    }

    private function obtenerMejorHora(
        Contacto $contacto
    ): string {

        $horas = $contacto->enviosCampanas()
            ->where('apertura', 1)
            ->selectRaw('HOUR(fecha_apertura) hora')
            ->pluck('hora');

        if ($horas->isEmpty()) {
            return '09:00';
        }

        $hora = $horas
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();

        return str_pad($hora, 2, '0', STR_PAD_LEFT)
            . ':00';
    }

    public function predecirCampana(
        array $contactos,
        string $contenido
    ): array {

        $resultados = [];

        foreach ($contactos as $contacto) {

            $resultados[] = $this->predecir(
                $contacto,
                $contenido
            );
        }

        usort(
            $resultados,
            fn ($a, $b) =>
                $b['probabilidad']
                <=>
                $a['probabilidad']
        );

        return $resultados;
    }
}
