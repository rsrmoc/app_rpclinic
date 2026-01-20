<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setores extends Model {
    use SoftDeletes;

    protected $table = 'setor';
    protected $primaryKey = 'cd_setor';

    protected $fillable = [
        'nm_setor',
        'cd_empresa',
        'sn_ativo',
        'cod_hierarquico',
        'grupo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro'
    ];

    public function empresa() {
        return $this->hasOne(Empresa::class, 'cd_empresa', 'cd_empresa');
    }
}
