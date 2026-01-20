<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class CID extends Model
{
    protected $table = "cid";
    protected $primaryKey = "cd_cid";
    protected $keyType = "string";

    protected $fillable = [
        "cd_grupo",
        "ds_cid",
        "ds_cid_aux",
        "tp_sexo"
    ];
}
