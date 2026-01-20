<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoSaida extends Model
{
    protected $table = 'solicitacao_saida';
    protected $primaryKey = 'cd_solicitacao';

    protected $fillable = [
        'dt_saida',
        'cd_estoque',
        'cd_setor',
        'nr_doc',
        'cd_usuario',
    ];

    protected static function booted() {
        static::deleting(function($saida) {
            $saida->saidaProdutos()->delete();
        });
    }

    public function estoque() {
        return $this->hasOne(Estoque::class, 'cd_estoque', 'cd_estoque');
    }

    public function setor() {
        return $this->hasOne(Setores::class, 'cd_setor', 'cd_setor');
    }

    public function saidaProdutos() {
        return $this->hasMany(SolicitacaoSaidaProd::class, 'cd_solicitacao', 'cd_solicitacao');
    }
}
