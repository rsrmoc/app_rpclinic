<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class RelatorioCalculos extends Model
{
    //
    protected $fillable = [
        'nome_coluna',
        'funcao',
        'relatorio_id',
        'cd_empresa',
    ];
}
