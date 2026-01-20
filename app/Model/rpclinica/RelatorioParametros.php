<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class RelatorioParametros extends Model
{
    //
    protected $fillable = [
        'nome_coluna',
        'operador',
        'obrigatorio',
        'relatorio_id',
        'cd_empresa',
        'cd_param_padrao',
    ];
}
