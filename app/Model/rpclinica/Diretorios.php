<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Diretorios extends Model
{
    protected $table = 'diretorios';
    protected $primaryKey = 'cd_diretorio';

    protected $fillable = [
        'cd_diretorio',
        'dt_diretorio',
        'sn_sistema',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
