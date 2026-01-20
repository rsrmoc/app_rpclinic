<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class ProdutoLote extends Model
{
    protected $table = 'produto_lote';
    protected $primaryKey = 'cd_lote';

    protected $fillable = [
        'cd_produto',
        'nm_lote',
        'validade',
        'cd_usuario'
    ];
}
