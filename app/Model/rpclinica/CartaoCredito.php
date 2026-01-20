<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoCredito extends Model {
    use SoftDeletes;

    protected $table = 'cartao_credito';
    protected $primaryKey = 'cd_cartao';

    protected $fillable = [
        'nm_cartao',
        'dia_fechamento',
        'dia_vencimento',
        'vl_limite',
        'sn_exibe_resumo',
        'cd_empresa',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro',
        'up_usuario'
    ];

    public function empresa() {
        return $this->hasOne(Empresa::class, 'cd_empresa', 'cd_empresa');
    }
}
