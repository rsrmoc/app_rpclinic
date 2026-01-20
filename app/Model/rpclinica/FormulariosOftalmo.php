<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormulariosOftalmo extends Model
{
  

    protected $table = 'oft_formularios';
    protected $primaryKey = 'cd_formulario';
    protected $keyType = "string";
    protected $fillable = [
        'cd_formulario',
        'nm_formulario',
        'tipo',
        'sn_laudo', 
        'view'
    ];
}
