<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    protected $table = "rota";
    protected $primaryKey = "rota";
    protected $keyType = "string";

    protected $fillable = [
        "rota",
        "nm_rota",
        "menu",
        "grupo",
        "ordem",
        "sub_menu",
        "controla_rota", 
        "ds_cid_aux", 
        'created_at',
        'updated_at',
    ];

    public function perfil_rotas() {
        return $this->hasOne(PerfilRota::class, 'nm_rota', 'grupo');
    }
}
