<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use SoftDeletes;

    protected $table = 'estoque';
    protected $primaryKey = 'cd_estoque';

    protected $fillable = [
        'nm_estoque',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];
}
