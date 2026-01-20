<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaTipo extends Model { 

    protected $table = 'conta_tipo';
    protected $primaryKey = 'cd_tipo_conta';
    protected $keyType = "string";

    protected $fillable = [
        'cd_tipo_conta',
        'nm_tipo_conta', 
    ];

 

    public function tab_conta() {
        return $this->hasMany(ContaBancaria::class, 'tp_conta', 'cd_tipo_conta');
    }
}
