<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class RelatorioOrdem extends Model
{
    //
    protected $table = 'relatorio_ordem';

    protected $fillable = [
        'nome_coluna',
        'tipo',
        'relatorio_id',
        'cd_empresa',
    ];
}
