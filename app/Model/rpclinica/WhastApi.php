<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastApi extends Model
{
    protected $table = 'whast_api';
    protected $primaryKey = 'cd_api';
 
    protected $fillable = [
        'cd_api ',
        'key', 
        'key_acesso', 
        'server', 
        'cd_empresa', 
        'phone_connected', 
        'phone',
        'phone_name',
        'webhook', 
        'webhookMessage', 
        'webhookGroup', 
        'webhookConnection', 
        'webhookQrCode', 
        'webhookMessageFromMe', 
        'msg_agenda',
        'msg_agenda_footer',
        'updated_at', 
        'created_at',  
    ];

     
}
