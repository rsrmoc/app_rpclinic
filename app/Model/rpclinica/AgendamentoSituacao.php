<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoSituacao extends Model
{
    protected $table = 'agendamento_situacao';
    protected $primaryKey = 'cd_situacao';
    protected $keyType = "string";

    protected $fillable = [
        'cd_situacao',
        'nm_situacao',
        'icone', 
    ];

    protected $hidden = ["arquivo"];

    protected $appends = ["url_arquivo"];

    public function getUrlArquivoAttribute() {
        return "data:image/{$this->tipo};base64,".base64_encode(utf8_decode($this->arquivo));
    }
}
