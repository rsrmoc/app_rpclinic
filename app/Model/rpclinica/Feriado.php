<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Feriado extends Model
{ 

    protected $table = 'feriados';
    protected $primaryKey = 'cd_feriado';

    protected $fillable = [
        'cd_feriado',
        'nm_feriado',
        'ano',
        'dt_feriado',
        'tp_feriado',
        'sn_bloqueado',
        'nivel',
        'sn_api',
        'cd_usuario',  
        'created_at',
        'updated_at'
    ];

 
}
