<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class EmpresaEmail extends Model
{
    protected $table = 'empresa_email';
    protected $primaryKey = 'tipo';
 
    protected $fillable = [
        'tipo ',
        'nome', 
        'assunto',
        'link',    
    ];

     
}
