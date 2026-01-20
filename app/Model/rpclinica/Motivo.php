<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivo extends Model {
    use SoftDeletes;

    protected $table ='motivo';
    protected $primaryKey = 'cd_motivo';

    protected $fillable = [
        'motivo',
        'cd_usuario',
        'up_usuario'
    ];
}