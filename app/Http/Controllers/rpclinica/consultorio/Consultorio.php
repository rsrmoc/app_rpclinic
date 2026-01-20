<?php

namespace App\Http\Controllers\rpclinica\consultorio;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoAnexos;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\AgendamentoLog;
use App\Model\rpclinica\ClassificacaoTriagem;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Formulario;
use App\Model\rpclinica\FormularioHeaderFooter;
use App\Model\rpclinica\LogExamesPaciente;
use App\Model\rpclinica\LogProblemasPaciente;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\Usuario;
use App\Model\rpclinica\Empresa;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Bibliotecas\PDFSign;
use App\Model\rpclinica\AgendamentoAnotacao;
use App\Model\rpclinica\AgendamentoImg;
use App\Model\rpclinica\Certificado;
use App\Model\rpclinica\Equipamento;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\FormulariosOftalmo;
use App\Model\rpclinica\Oft_auto_refracao;
use App\Model\rpclinica\Oft_ceratometria;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Oft_anamnese;
use App\Model\rpclinica\Oft_tonometria_aplanacao;
use App\Model\rpclinica\Oft_refracao;
use App\Model\rpclinica\Oft_documento;
use App\Model\rpclinica\Oft_receita_oculos;
use App\Model\rpclinica\Oft_fundoscopia;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\LinksUteis;
use App\Model\rpclinica\Produto;

use App\Model\rpclinica\Oft_reserva_cirurgia;
use App\Model\rpclinica\Oft_reserva_cirurgia_opme;
use DateTime;

class Consultorio extends Controller
{


    public function show(Request $request, Agendamento $agendamento)
    { 
     
        $agendamento->load('situacao'); 
  
        if($request->user()->cd_profissional<>$agendamento->cd_profissional){  
            return  redirect()->route('consultorio')->with('error', 'Esse profissional não esta permitido atender esse Atendimento!');
        }
         
        if($agendamento->tab_situacao?->finalizado=='S'){  

            $data1 = new DateTime(date('Y-m-d'));
            $data2 = new DateTime($agendamento->dt_agenda); 
            $intervalo = $data1->diff($data2);  
            if($intervalo->days>0){
                return redirect()->route('consultorio')->with('error', 'Atendimento Finalizado!  Não é permitido alteração do Atendimento após 24 Hs.');
            }
 
        }
 
        if($agendamento->tab_situacao?->em_atend<>'S'){ 

            $situacao = AgendamentoSituacao::where('em_atend','S')->first();
            if(empty($situacao)){ 
                return redirect()->back()->withInput()->withErrors(['message' => 'Situação não configurada para essa ação!'], 400);
            }
            
            $agendamento->update(array(
                'dt_inicio' => date('Y-m-d H:i'),
                'usuario_inicio' => $request->user()->cd_usuario,
                'situacao' =>  $situacao->cd_situacao,
                'cd_profissional' => $request->user()->cd_profissional
            ));

        }


        $empresa = Empresa::find($request->user()->cd_empresa);
        $tabelas['tp_prontuario'] = $empresa->tp_prontuario_eletronico;
        if(empty($tabelas['tp_prontuario'])){
            return redirect()->route('consultorio')->with('error', 'Tipo de Prontuario não configurado!');
        }
        $formulario = FormulariosOftalmo::where('sn_ativo', 'S')->orderBy('ordem')->get();
        $tabelas['equipamento'] = Equipamento::orderBy('nm_equipamento')->get();
        $tabelas['cirurgia'] = Exame::where('tp_item', 'CI')->where('sn_ativo', 'S')->orderBy('nm_exame')->get();
        $tabelas['profissional'] = Profissional::where('sn_ativo', 'S')->orderBy('nm_profissional')->get();
        $tabelas['opme'] = Produto::where('sn_ativo', 'S')->where('sn_opme', 'S')->orderBy('nm_produto')->get();
        $opcoes = FormulariosOftalmo::where('sn_ativo', 'S')->selectRaw('distinct(tipo) tipo')->get();
        $menu=[];
        foreach($opcoes as $val){
            $menu[$val->tipo] = $val->tipo;
        }
        $tabelas['links'] = LinksUteis::where('sn_ativo','S')->orderByRaw("nm_link")->get();
        return view('rpclinica.consultorio.prontuario-eletronico', [
            'agendamento' => $agendamento->load('paciente', 'documentos', 'profissional', 'convenio', 'agenda'),
            'historicoAgentamentos' => null,
            'formulario' => $formulario,
            'menu' => $menu,
            'tabelas' => $tabelas 
        ]); 

    }
  
    public function storeAnexos(Request $request, $cd_agendamento) {
        $request->validate([
            "files" => "required|array",
            "files.*" => "required|file|mimes:webp,jpeg,jpg,bmp,png,pdf|max:1024",
        ]);

        try {
            
            DB::transaction(function() use($request, $cd_agendamento) {
                foreach($request->file("files") as $file) {
                    $data = [
                        "cd_agendamento" => $cd_agendamento,
                        "nome" => $file->getClientOriginalName(),
                        "tipo" => $file->extension(),
                        "tamanho" => $file->getSize(),
                        "cd_usuario" => $request->user()->cd_usuario,
                        "arquivo" => utf8_encode( file_get_contents($file->getPathName()) ),
                        "created_at" => Carbon::now()
                    ];

                    funcLogsAtendimentoHelpers($cd_agendamento,'SUARIO ANEXOU ARQUIVO');


                    DB::table('agendamento_anexos')->insert($data);
                }
            });

            return response()->json([
                "message" => "Arquivos salvos com sucesso!",
                // "anexos" => AgendamentoAnexos::where('cd_agendamento', $cd_agendamento)->get()
            ]);
        }
        catch (Throwable $th) {
            return response()->json(["message" => $th->getMessage()], 500);
        }
    }
 
}
