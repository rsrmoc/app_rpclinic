<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_texto_padrao extends Model
{ 

    protected $table = 'oft_texto_padrao';
    protected $primaryKey = 'cd_texto_padrao';

    protected $fillable = [
        'cd_texto_padrao',
        'titulo',
        'ds_texto_padrao',  
        'created_at',
        'updated_at'
    ];

 
}
