<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use SoftDeletes;

    protected $table = 'fornecedor';
    protected $primaryKey = 'cd_fornecedor';

    protected $fillable = [
        'nm_fornecedor',
        'nm_razao',
        'tp_cadastro',
        'tp_pessoa',
        'documento',
        'contato',
        'telefone',
        'celular',
        'email',
        'end',
        'conta_bancaria',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];
}
