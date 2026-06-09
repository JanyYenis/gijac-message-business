<?php

namespace App\Models;

use App\Classes\Models\Model;

class WhatsappCall extends Model
{
    protected $fillable = [
        'call_id',
        'from',
        'to',
        'status',
        'started_at',
        'answered_at',
        'ended_at'
    ];
}
