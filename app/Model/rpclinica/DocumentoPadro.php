<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class DocumentoPadro extends Model
{
    protected $table = "documento_padrao";
    protected $primaryKey = "cd_doc_padrao";

    protected $fillable = [
        'nm_documento',
        'conteudo',
        'sn_ativo'
    ];
}
