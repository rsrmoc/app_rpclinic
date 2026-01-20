<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $table = 'certificado';
    protected $primaryKey = 'cd_profissional';

    protected $fillable = [
        "cd_profissional",
        "pfx",
        "pfx_validade",
        "pfx_razao",
        "pfx_nome",
        "pfx_cpf",
        "pfx_hash",
        "pfx_emissor",
        "pfx_email",
        "pfx_situacao",
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
