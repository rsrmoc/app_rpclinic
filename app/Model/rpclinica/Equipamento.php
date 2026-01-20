<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipamento extends Model {
   

    protected $table ='equipamentos';
    protected $primaryKey = 'cd_equipamento';

    protected $fillable = [
        'cd_equipamento',
        'nm_equipamento',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}