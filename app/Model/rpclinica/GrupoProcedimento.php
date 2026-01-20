<?php


namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class GrupoProcedimento extends Model
{
 
    use Notifiable;

    protected $table = 'grupo_procedimento';
    protected $primaryKey = 'cd_grupo';

    protected $fillable = [
        'cd_grupo',
        'nm_grupo',
        'tp_grupo',
        'sn_ativo', 
        'sn_produto',
        'cd_usuario' 
    ];

 
}
