<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convenio extends Model
{
    use SoftDeletes;

    protected $table = 'convenio';
    protected $primaryKey = 'cd_convenio';

    protected $fillable = [
        'nm_convenio',
        'tp_convenio',
        'sn_sancoop',
        'prazo_retorno',
        'sn_ativo',
        'cd_usuario',
        'up_usuario',
        'cnpj',
        'registro_ans',
        'endereco',
        'email',
        'dt_contrato',
        'telefone',
        'obs',
        'sn_financeiro',
        'cd_fornecedor',
        'cd_empresa',
        'cd_categoria',
        'cd_conta',
        'cd_setor',
        'cd_forma',
        'cd_marca', 
        'link_autorizacao',
        'user_autorizacao',
        'senha_autorizacao',
        'prazo_guia'
    ];

    public function procedimentosConvenio() {
        return $this->hasMany(ProcedimentoConvenio::class, 'cd_convenio', 'cd_convenio')
        ->SelectRaw("procedimento_convenio.*, format(valor, 2, 'de_DE') valor_formatado");
    }
}
