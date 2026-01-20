<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAjuste extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_ajuste';
    protected $primaryKey = 'cd_tipo_ajuste';

    protected $fillable = [
        'nm_tipo_ajuste',
        'tp_ajuste',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];
}
