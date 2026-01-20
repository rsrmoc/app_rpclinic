<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastReceive extends Model
{
    protected $table = 'whast_receive';
    protected $primaryKey = 'cd_whast_receive';
 
    protected $fillable = [
        'cd_whast_receive ',
        'tp_acao', 
        'tp_msg', 
        'dt_msg', 
        'id_msg', 
        'nr_envio', 
        'nm_envio', 
        'msg', 
        'id_op_select', 
        'ds_op_select', 
        'id_envio', 
        'nr_resposta',  
        'updated_at', 
        'created_at', 
        'deleted_at' 
    ];

     
}
