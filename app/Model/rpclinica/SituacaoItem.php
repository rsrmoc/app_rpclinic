<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class SituacaoItem extends Model
{ 

    protected $table = 'situacao_itens';
    protected $primaryKey = 'cd_situacao_itens';
    protected $keyType = "string";

    protected $fillable = [
        'cd_situacao_itens',
        'nm_situacao_itens',
        'html',
        'tipo',
        'classe',   
        'created_at',
        'updated_at'
    ];

 
}
