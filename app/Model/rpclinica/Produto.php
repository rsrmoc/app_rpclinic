<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    protected $table = 'produto';
    protected $primaryKey = 'cd_produto';

    protected $fillable = [
        'nm_produto',
        'cd_classificacao',
        'sn_medicamento',
        'cd_mestre',
        'cd_proc',
        'classificacao_abc',
        'sn_opme',
        'sn_lote',
        'classificacao_xyz',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];

    public function classificacao() {
        return $this->hasOne(Classificacao::class, 'cd_classificacao', 'cd_classificacao');
    }
}
