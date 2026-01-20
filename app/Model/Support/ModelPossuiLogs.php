<?php

namespace App\Model\Support;

use App\Model\Support\Log;

trait ModelPossuiLogs {

    public function logs() {
        return $this->morphMany(Log::class, "registro");
    }
 
    public function saveLog($usuario, $descricao, $dados = null,$modulo,$rotina,$info = null,$url=null) {
         
        if($descricao=='Cadastro'){ $dados = $this->getAttributes(); }
        if($descricao=='Edicao'){ $dados = $this->getChanges(); } 
        
        if(isset($dados['cd_paciente'])){ $info['paciente'] = $dados['cd_paciente']; }
        if(isset($dados['cd_agendamento'])){ $info['agendamento'] = $dados['cd_agendamento']; }


        return $this->logs()->create([
            'usuario_id' => $usuario->cd_usuario, 
            'descricao' => $descricao,
            'modulo' => $modulo,
            'rotina' => $rotina,
            'agendamento_id' => (isset($info['agendamento'])) ? $info['agendamento'] : null,
            'paciente_id' => (isset($info['paciente'])) ? $info['paciente'] : null,
            'agendamento_item' => (isset($info['item'])) ? $info['item'] : null,
            'exame_id' => (isset($info['exame'])) ? $info['exame'] : null,
            'dados' => $dados,
            'url'=>$url
        ]); 

    }

    public function criarLog($usuario, $descricao, $dados = null,$modulo,$rotina,$cdAgednda = null) {
        return $this->logs()->create([
            'usuario_id' => $usuario->cd_usuario, 
            'descricao' => $descricao,
            'modulo' => $modulo,
            'rotina' => $rotina,
            'agendamento_id' => $cdAgednda,
            'dados' => $dados,
        ]);
    }

    public function criarLogCadastro($usuario,$modulo,$rotina,$cdAgednda = null) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes(),$modulo,$rotina,$cdAgednda);
    }

    public function criarLogEdicao($usuario,$modulo,$rotina) {
        return $this->criarLog($usuario, 'Edicao', $this->getChanges(),$modulo,$rotina);
    }

    public function criarLogExclusao($usuario = null,$modulo= null,$rotina= null) {
        return $this->criarLog($usuario, 'Exclusao', null,$modulo,$rotina);
    }

    public function teste() {
        return true;
    }
}