<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastOpcoes extends Model
{
    protected $table = 'whast_opcoes';
    protected $primaryKey = 'cd_situacao';
    protected $keyType = 'string';

    protected $fillable = [
        'cd_situacao',
        'chave' 
    ];
 

}
