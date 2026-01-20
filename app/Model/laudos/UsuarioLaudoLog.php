<?php

namespace App\Model\laudos;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Notifications\Notifiable;

class UsuarioLaudoLog extends Model
{ 
    use Notifiable;

    protected $table = 'usuarios_laudos_logs';
 
    protected $primaryKey = 'cd_usuario_laudo_log';
 
    protected $fillable = [
        'cd_usuario_laudo_log', 
        'cd_usuario',
        'ip', 
        'created_at',
        'updated_at', 
    ];

   
    
}
