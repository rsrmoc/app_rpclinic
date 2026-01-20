<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaBancariaEmpresa extends Model {
    use SoftDeletes;

    protected $table = 'conta_bancaria_empresa';
    protected $primaryKey = 'cd_empresa_conta';

    protected $fillable = [
        'cd_empresa_conta',
        'cd_conta',
        'cd_empresa',  
        'cd_usuario',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function empresa() {
        return $this->hasOne(Empresa::class, 'cd_empresa', 'cd_empresa');
    }
}
