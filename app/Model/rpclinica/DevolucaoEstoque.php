<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class DevolucaoEstoque extends Model
{
    protected $table = 'devolucao_estoque';
    protected $primaryKey = 'cd_devolucao';

    const UPDATED_AT = null;

    protected $fillable = [
        'dt_devolucao',
        'cd_solicitacao_saida',
        'cd_usuario'
    ];

    protected static function booted() {
        static::deleting(function ($devolucao) {
            $devolucao->devolucoesProdutos()->delete();
        });
    }

    public function solicitacaoSaida() {
        return $this->hasOne(SolicitacaoSaida::class, 'cd_solicitacao', 'cd_solicitacao_saida');
    }

    public function devolucoesProdutos() {
        return $this->hasMany(DevolucaoEstoqueProduto::class, 'cd_devolucao', 'cd_devolucao');
    }
}
