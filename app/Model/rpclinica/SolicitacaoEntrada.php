<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoEntrada extends Model
{
    protected $table = 'solicitacao_entrada';
    protected $primaryKey = 'cd_solicitacao';

    public $timestamps = false;

    protected $fillable = [
        'dt_solicitacao',
        'cd_estoque',
        'cd_motivo',
        'cd_ord_Com',
        'nr_doc',
        'cd_fornecedor',
        'cd_usuario'
    ];

    protected static function booted() {
        static::deleting(function($entrada) {
            $entrada->entradaProdutos()->delete();
        });
    }

    public function fornecedor() {
        return $this->hasOne(Fornecedor::class, 'cd_fornecedor', 'cd_fornecedor');
    }

    public function estoque() {
        return $this->hasOne(Estoque::class, 'cd_estoque', 'cd_estoque');
    }

    public function entradaProdutos() {
        return $this->hasMany(SolicitacaoEntradaProduto::class, 'cd_solicitacao', 'cd_solicitacao');
    }
}
