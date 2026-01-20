<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    //
    protected $fillable = [
        'titulo',
        'area',
        'conteudo',
        'tipo_relatorio',
        'layout',
        'restricao',
        'cd_empresa'
    ];
}
