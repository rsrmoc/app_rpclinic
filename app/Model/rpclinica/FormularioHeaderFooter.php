<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioHeaderFooter extends Model { 

    protected $table = 'formulario_header_footer';
    protected $primaryKey = 'cd_doc_header_footer';

    protected $fillable = [
        'cd_doc_header_footer',
        'endereco',
        'nome', 
    ];
 
}
