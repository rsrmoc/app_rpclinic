<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastSituacao extends Model
{
    protected $table = 'whast_situacao';
    protected $primaryKey = 'situacao';
    protected $keyType = 'string';

    protected $fillable = [
        'situacao',
        'valor',
        'tipo'
    ];
 

}
