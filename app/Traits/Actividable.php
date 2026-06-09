<?php

namespace App\Traits;

use App\Models\Sistema\Actividad;
use Illuminate\Support\Facades\Auth;

trait Actividable
{
    public static function bootActividable()
    {
        static::created(function ($model) {
            $model->startRecordingActivity('created');
        });

        static::updated(function ($model) {
            $model->startRecordingActivity('updated');
        });

        static::deleted(function ($model) {
            $model->startRecordingActivity('deleted');
        });
    }

    protected function startRecordingActivity($event)
    {
        // Asegúrate de que el usuario esté autenticado
        if (Auth::check()) {
            $this->recordActivity($event);
        }
    }

    protected function recordActivity($event)
    {
        $activity = [
            'uuid' => Auth::user()->uuid,
            'accion' => $event,
            'actividable_id' => $this->id,
            'actividable_type' => get_class($this),
            'changes' => $this->activityChanges($event),
        ];

        Actividad::create($activity);
    }

    protected function activityChanges($event)
    {
        if ($event === 'created') {
            return json_encode(['after' => $this->getAttributes()]);
        }

        if ($event === 'deleted') {
            return json_encode(['before' => $this->getOriginal()]);
        }

        if ($event === 'updated') {
            $before = [];
            $after = [];

            foreach ($this->getChanges() as $attribute => $newValue) {
                $before[$attribute] = $this->getOriginal($attribute);
                $after[$attribute] = $newValue;
            }

            return json_encode([
                'before' => $before,
                'after' => $after,
            ]);
        }

        return json_encode([]);
    }
}
