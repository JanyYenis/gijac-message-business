<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];
}
