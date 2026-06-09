<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;

class TipoConcepto extends Model
{
    const ACTIVO = 1;
    const INACTIVO = 0;

    const COLUMNA_PRINCIPAL = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipos_conceptos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        // 'tabla',
    ];

    /**
     * Get the phone record associated with the user.
     */
    public function conceptos()
    {
        return $this->hasMany(Concepto::class, 'id_tipo', 'id');
    }

    /**
     * Get the phone record associated with the user.
     */
    public function conceptosActivos()
    {
        return $this->hasMany(Concepto::class, 'id_tipo', 'id')
            ->whereEstado(Concepto::ACTIVO)
            ->orderBy('conceptos.id', 'asc');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('activo', $estado);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCodigo($query, $codigo)
    {
        return $query->firstwhere('id', $codigo);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param string codigo
     * @return mixed
     */
    public static function buscarPorCodigo($codigo)
    {
        return static::whereCodigo($codigo);
    }

    /**
     * @param $tipo
     * @param string $columna
     * @return mixed
     */
    public static function array($tipo, string $columna = self::COLUMNA_PRINCIPAL)
    {
        $concepts = self::with('conceptosActivos')
                ->codigo($tipo)?->conceptosActivos ?? [];

        return $concepts->pluck($columna)->toArray();
    }
}
