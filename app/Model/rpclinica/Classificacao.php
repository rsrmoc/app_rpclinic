<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classificacao extends Model
{
    use SoftDeletes;

    protected $table = 'classificacao';
    protected $primaryKey = 'cd_classificacao';

    protected $fillable = [
        'nm_classificacao',
        'cd_categoria_receita',
        'cd_categoria_despesa',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];
}
