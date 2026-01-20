<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaPagamento extends Model {
    use SoftDeletes;

    protected $table = 'forma_pagamento';
    protected $primaryKey = 'cd_forma_pag';

    protected $fillable = [
        'nm_forma_pag',
        'sn_ativo',
        'tipo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];
}
