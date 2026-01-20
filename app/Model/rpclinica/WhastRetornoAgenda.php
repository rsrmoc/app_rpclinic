<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class WhastRetornoAgenda extends Model
{
    protected $table = 'whast_retorno_agenda';
    protected $primaryKey = 'cd_retorno';
    protected $keyType = 'string';

    protected $fillable = [
        'cd_retorno',
        'msg' 
    ];
 

}
