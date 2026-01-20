<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WebhookMessage extends Model
{
    protected $table = 'webhookMessage';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'conteudo', 
        'tipo',
        'created_at',
        'updated_at'
    ];
}
