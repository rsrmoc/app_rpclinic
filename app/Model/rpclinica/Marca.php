<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model {
    use SoftDeletes;

    protected $table = 'marca';
    protected $primaryKey = 'cd_marca';

    protected $fillable = [
        'cd_marca',
        'nm_marca',
        'sn_ativo', 
        'cd_usuario',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
