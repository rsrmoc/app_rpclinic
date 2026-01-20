<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table = 'categoria';
    protected $primaryKey = 'cd_categoria';

    protected $fillable = [
        'cd_categoria',
        'cd_categoria_pai',
        'cod_estrutural',
        'nm_categoria',
        'espacos',
        'sn_ativo',
        'cd_usuario',
        'created_at',
        'updated_at',
        'deleted_at',
        'sn_lancamento',
        'tp_lancamento',
        'descricao',
        'cd_conta',
        'cd_forma',
        'cd_setor',
        'cd_fornecedor',
        'cd_empresa'
    ];

    public function contaPai() {
        return $this->where('tp_categoria', 'G')->where('cod_categoria', $this->cod_categoria)->first();
    }
}
