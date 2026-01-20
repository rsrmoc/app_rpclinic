<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAtendimentoProc extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_atendimento_proc';
    protected $primaryKey = 'cd_tipo_atendimento_proc'; 

    protected $fillable = [
        'cd_tipo_atendimento_proc',
        'cd_tipo_atendimento',
        'cd_proc',
        'cod_proc', 
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
