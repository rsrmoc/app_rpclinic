<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class GuiaSituacao extends Model
{ 
    protected $table = 'guia_situacao';
    protected $primaryKey = 'cd_situacao_guia';
    protected $keyType = "string";

    protected $fillable = [
        'cd_situacao_guia',
        'nm_situacao',
        'icone', 
        'cor',  
        'cd_usuario', 
        'created_at',
        'updated_at', 
    ];
 
 
   
}
