<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastRotina extends Model
{
    protected $table = 'whast_rotina';
    protected $primaryKey = 'tipo';
    protected $keyType = "string";

    protected $fillable = [
        'tipo',
        'dt_rotina', 
        'updated_at',
        'created_at'
    ];
}
