<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaBancaria extends Model {
    use SoftDeletes;

    protected $table = 'conta_bancaria';
    protected $primaryKey = 'cd_conta';

    protected $fillable = [
        'nm_conta',
        'sn_investimento',
        'sn_exibir_resumo',
        'saldo_inicial',
        'cd_empresa',
        'tp_saldo',
        'dt_saldo',
        'tp_conta',
        'sn_cartao',
        'sn_ativo',
        'vl_limite',
        'dia_vencimento',
        'dia_fechamento',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro'
    ];

    public function empresa() {
        return $this->hasOne(Empresa::class, 'cd_empresa', 'cd_empresa');
    }

    public function lancamentos() {
        return $this->hasMany(DocumentoBoleto::class, 'cd_conta', 'cd_conta');
    }

    public function tab_tipo() {
        return $this->hasOne(ContaTipo::class, 'cd_tipo_conta', 'tp_conta');
    }
}
