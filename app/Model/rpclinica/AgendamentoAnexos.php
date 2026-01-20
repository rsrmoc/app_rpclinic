<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoAnexos extends Model
{
    protected $table = 'agendamento_anexos';
    protected $primaryKey = 'cd_anexo';

    protected $fillable = [
        'cd_agendamento',
        'nome',
        'tipo',
        'tamanho',
        'arquivo'
    ];

    protected $hidden = ["arquivo"];

    protected $appends = ["url_arquivo"];

    public function getUrlArquivoAttribute() {
        return "data:image/{$this->tipo};base64,".base64_encode(utf8_decode($this->arquivo));
    }
}
