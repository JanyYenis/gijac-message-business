<?php

namespace App\Traits;

use App\Models\Usuario;
use ErrorException;

trait Modelable
{
    /**
     * Retorna el nombre de la tabla
     */
    public static function darNombreTabla()
    {
        return with(new static)->getTable();
    }

    /**
     * Función que permite obtener los conceptos de estados del modelo.
     * @param bool $infoTipo Boolean que indica si debe de incluir el tipo concepto.
     * @param bool $validarPermiso Boolean que indica que si el string de permiso está definido en el concepto, que valide si el usuario tiene permiso.
     * @return array|Collection Retorna un array vacío si no hay conceptos. Retorna una colección de Concepto si están definidos.
     */
    public static function darEstados($infoTipo = false, $validarPermiso = false)
    {
        $clase = self::darClaseEstatica();

        if (!defined("$clase::TC_ESTADO")) {
            // para evitar errores de toArray.
            return collect([]);
        }

        $conceptos = darConceptos($clase::TC_ESTADO, $infoTipo);
        if ($validarPermiso) {
            // $conceptos = $conceptos->filter(function ($concepto) {
            //     // si está definido el string de permiso, que valide si el usuario tiene permiso
            //     // del resto, se podrá ver.
            //     if ($concepto?->permiso) {
            //         return can($concepto->permiso);
            //     }
            //     return true;
            // });
        }
        return $conceptos;
    }

    /**
     * Función que retorna el concepto de estado.
     */
    public function infoEstado()
    {
        $clase = self::darClaseEstatica();
        if ($clase) {
            return darInfoConcepto($this, $clase::TC_ESTADO, 'estado')->selectRaw('conceptos.*');
        }
    }

    public function esEditor()
    {
        // return $this?->editores()
        //     ->accesoUsuario(auth(darGuard())->user())
        //     ->exists();
    }

    public function esEvaluador()
    {
        // return $this?->evaluadores()
        //     ->accesoUsuario(auth(darGuard())->user())
        //     ->exists();
    }

    public function esLector()
    {
        // return $this?->lectores()
        //     ->accesoUsuario(auth(darGuard())->user())
        //     ->exists();
    }

    public function estaEliminado()
    {
        $clase = self::darClaseEstatica();
        $eliminado = defined("$clase::ELIMINADO") ? $clase::ELIMINADO : -1;
        return $this->estado == $eliminado;
    }

    /**
     * @return mixed
     * @deprecated funcion mal nombrada
     */
    public function estadoEditable()
    {
        // TODO Eliminar todos los llamados a esta función cambiar por estadosEditables
        return $this->estadosEditables();
    }

    public function estadosEditables()
    {
        $clase = self::darClaseEstatica();
        $estados = defined("$clase::ESTADOS_EDITABLES") ? $clase::ESTADOS_EDITABLES : [];
        return in_array($this->estado, $estados);
    }

    public function puedeEditar()
    {
        $clase = self::darClaseEstatica();
        $permiso = defined("$clase::PERMISO_EDITAR") ? $clase::PERMISO_EDITAR : '';
        return $this->estadoEditable() /* || can($permiso) */;
    }

    /**
     *
     */
    public function puedeVer()
    {
        // $clase = self::darClaseEstatica();
        // $permiso = defined("$clase::PERMISO_VER") ? $clase::PERMISO_VER : '';
        // return ($this->esPropietario()) || can($permiso);
    }

    /**
     * Función que determina si puede cambiar el estado del registro del modelo.
     */
    public function puedeCambiarEstado()
    {
        // $clase = self::darClaseEstatica();
        // $permiso = defined("$clase::PERMISO_CAMBIAR_ESTADO") ? $clase::PERMISO_CAMBIAR_ESTADO : '';
        // return can($permiso);
    }

    /**
     * Función que determina si puede eliminar el registro del modelo.
     */
    public function puedeEliminar()
    {
        $clase = self::darClaseEstatica();

        $permiso = defined("$clase::PERMISO_ELIMINAR") ? $clase::PERMISO_ELIMINAR : '';
        return /* can($permiso) || */ !$this->estaEliminado();
    }

    public function getPuedeVerAttribute()
    {
        return $this->puedeVer();
    }

    public function getPuedeEliminarAttribute()
    {
        return $this->puedeEliminar();
    }

    public function getPuedeCambiarEstadoAttribute()
    {
        return $this->puedeCambiarEstado();
    }

    public function getPuedeEditarEstadoAttribute()
    {
        return $this->puedeEditar();
    }

    /**
     * Determine if the user is an administrator.
     *
     * @return bool
     */
    public function getAccionesAttribute()
    {
        $info['config_boton'] = [
            "id" => $this->id,
            "modelo" => $this->getTable()
        ];
        $info['model'] =$this->getTable();
        $info['puede_editar'] = $this->puedeEditar();
        $info['puede_cambiar_estado'] = $this->puedeEditar();
        $info['puede_eliminar'] = $this->puedeEliminar();
        $info['puede_ver'] = $this->puedeVer();
        $info['puede_ver_soportes'] = $this->puedeVerSoportes();
        $info['estados_enviables'] = $this->estadosEnviables();
        $info['estados_editables'] = $this->estadosEditables();
        return $info;
    }

    /**
     * Función que permite realizar la eliminación de este registro. Si el modelo tiene la constante
     * ESTADOS_ELIMINABLES definida, eliminará totalmente el registro, de lo contrario, solo lo marcará
     * como eliminado.
     * @return boolean Retorna un boolean indicando si el registro fue eliminado.
     */
    public function eliminar()
    {
        $clase = self::darClaseEstatica();
        $estados = defined("$clase::ESTADOS_ELIMINABLES") ? $clase::ESTADOS_ELIMINABLES : [];

        if (in_array($this->estado, $estados)) {
            $guardo = $this->delete();
        } else {
            $eliminado = defined("$clase::ELIMINADO") ? $clase::ELIMINADO : -1;
            if ($eliminado == -1) {
                return [
                    'estado'  => 'error',
                    'mensaje' => 'No se ha podido cambiar a estado eliminado en la base de datos.'
                ];
                // throw new ErrorException("No se ha podido cambiar a estado eliminado en la base de datos.");

            }
            $this->estado = $eliminado;
            $guardo = $this->save();
        }

        if (!$guardo) {
            return [
                'estado'  => 'error',
                'mensaje' => 'Ha ocurrido un problema al intentar eliminar el registro.'
            ];
            // throw new ErrorException("Ha ocurrido un problema al intentar eliminar el registro.");
        }
        return $guardo;
    }

    public static function traducir($atributo)
    {
        // $clase = self::darClaseEstatica();
        // $clase = str_replace("App" . '\\', "", $clase);
        // $clase = str_replace("\\", "/", $clase);
        // $clase = Str::lower($clase);

        // $lang = "$clase.$atributo";
        // return __($lang);
    }
}

