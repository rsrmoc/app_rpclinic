<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfissionaisTipo extends Model
{
  

    protected $table = 'profissional_tipo';
    protected $primaryKey = 'cd_tipo';

    protected $fillable = [
        'cd_tipo',
        'nm_tipo' 
    ];

 
}
