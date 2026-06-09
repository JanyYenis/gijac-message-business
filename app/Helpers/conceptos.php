<?php

use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;

if (!function_exists('darConceptos')) {
    /**
     * @param $codigoTipo
     * @param $infoTipo
     * @return array
     */
    function darConceptos($codigoTipo, $infoTipo = true)
    {
        $info = TipoConcepto::with('conceptosActivos')->where('nombre', $codigoTipo)->first();
        if (!$infoTipo) {
            $info = $info?->conceptosActivos;
        }
        return $info ?? [];
    }
}

if (!function_exists('darInfoConcepto')) {

    /**
     * @param $model
     * @param $tipoConcepto
     * @param $campo
     * @return mixed
     */
    function darInfoConcepto($model, $tipoConcepto, $campo)
    {
        // TODO A futuro se movera a un trait
        return $model->belongsTo(Concepto::class, $campo, 'codigo')
            ->join('tipos_conceptos', 'tipos_conceptos.id', 'conceptos.id_tipo')
            ->where('tipos_conceptos.nombre', $tipoConcepto);
    }
}

if (!function_exists('darConceptoPorTipo')) {
    function darConceptoPorTipo($tipoConcepto, $concepto)
    {
        return Concepto::select('conceptos.*')
            ->join('tipos_conceptos', 'tipos_conceptos.id', 'conceptos.id_tipo')
            ->where('conceptos.codigo', $concepto)
            ->where('tipos_conceptos.nombre', $tipoConcepto)
            ->first();
    }
}

if (!function_exists('whereInSafe')) {
    function whereInSafe($query, $campo, $valor)
    {
        if (is_array($valor)) {
            $query->whereIn($campo, $valor);
        } elseif (!is_array($valor)) {
            $query->where($campo, $valor);
        }
        return $query;
    }
}

if (!function_exists('orWhereInSafe')) {
    function orWhereInSafe($query, $campo, $valor)
    {
        if (is_array($valor)) {
            $query->orWhereIn($campo, $valor);
        } elseif (!is_array($valor)) {
            $query->orWhere($campo, $valor);
        }
        return $query;
    }
}
