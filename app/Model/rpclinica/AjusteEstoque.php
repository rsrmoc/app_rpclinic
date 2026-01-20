<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AjusteEstoque extends Model
{
    protected $table = 'ajuste_estoque';
    protected $primaryKey = 'cd_ajuste';

    const UPDATED_AT = null;

    protected $fillable = [
        'cd_estoque',
        'cd_tipo_ajuste',
        'cd_setor',
        'nr_doc',
        'dt_ajuste',
        'cd_usuario',
    ];

    public static function booted() {
        static::deleting(function($ajuste) {
            $ajuste->ajustesProdutos()->delete();
        });
    }

    public function estoque() {
        return $this->hasOne(Estoque::class, 'cd_estoque', 'cd_estoque');
    }

    public function setor() {
        return $this->hasOne(Setores::class, 'cd_setor', 'cd_setor');
    }

    public function tipoAjuste() {
        return $this->hasOne(TipoAjuste::class, 'cd_tipo_ajuste', 'cd_tipo_ajuste');
    }

    public function ajustesProdutos() {
        return $this->hasMany(AjusteEstoqueProduto::class, 'cd_ajuste', 'cd_ajuste');
    }
}
