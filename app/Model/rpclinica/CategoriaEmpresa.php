<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaEmpresa extends Model {
   // use SoftDeletes;

    protected $table = 'categoria_empresa';
    protected $primaryKey = 'cd_categoria_empresa';

    protected $fillable = [
        'cd_categoria_empresa',
        'cd_categoria',
        'cd_empresa',  
        'cd_setor',
        'cd_usuario',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function empresa() {
        return $this->hasOne(Empresa::class, 'cd_empresa', 'cd_empresa');
    }
}
