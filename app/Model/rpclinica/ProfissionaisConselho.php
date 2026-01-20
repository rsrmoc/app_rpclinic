<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfissionaisConselho extends Model
{
  

    protected $table = 'profissional_conselho';
    protected $primaryKey = 'conselho';
    protected $keyType = 'string';
    protected $fillable = [
        'conselho',
        'nm_conselho' 
    ];

 
}
