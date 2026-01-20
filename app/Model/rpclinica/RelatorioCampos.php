<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class RelatorioCampos extends Model
{
    //
    protected $fillable = [
        'nome_coluna',
        'alinhamento',
        'mascara',
        'limite',
        'relatorio_id',
        'cd_empresa',
    ];
}
