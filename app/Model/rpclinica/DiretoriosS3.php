<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class DiretoriosS3 extends Model
{
    protected $table = 'diretorios_s3';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'cd_diretorio',
        'caminho_local',
        'caminho_s3',
        'situacao',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
