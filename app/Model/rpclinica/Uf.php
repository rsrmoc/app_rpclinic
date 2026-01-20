<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Uf extends Model
{ 

    protected $table = 'uf';
    protected $primaryKey = 'cd_uf';
    protected $keyType = "string";

    protected $fillable = [
        'cd_uf',
        'nm_uf', 
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
