<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class WhastLogErro extends Model
{ 

    protected $table = 'whast_log_erro';
    protected $primaryKey = 'cd_whast_log_erro';

    protected $fillable = [
        'cd_whast_log_erro',
        'dados',
        'erro',   
        'created_at',
        'updated_at'
    ];

 
}
