<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WebhookErro extends Model
{
    protected $table = 'webhook_erro';
    protected $primaryKey = 'cd_webhook';
 
    protected $fillable = [
        'cd_webhook ',
        'ds_erro', 
        'dados',
        'dt_erro', 
        'updated_at', 
        'created_at',   
    ];
 
}
