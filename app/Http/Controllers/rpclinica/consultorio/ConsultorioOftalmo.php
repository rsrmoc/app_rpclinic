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
use App\Model\rpclinica\Produto;

use App\Model\rpclinica\Oft_reserva_cirurgia;
use App\Model\rpclinica\Oft_reserva_cirurgia_opme;

class ConsultorioOftalmo extends Controller
{


    public function showOftalmo(Request $request, Agendamento $agendamento, FormulariosOftalmo $formulario)
    {
        
        try {
            $agendamento->load('paciente', 'documentos', 'profissional', 'convenio');
            if ($formulario->view) {

                $caminhoTela = $formulario->view;
                $retorno = null;
                $historico = null;
                $parametros = null;

                if ($formulario->cd_formulario == 'AUTO_REFRACAO') {

                    $retorno = Oft_auto_refracao::where('cd_agendamento', $agendamento->cd_agendamento)
                        ->selectRaw("
                    cd_auto_refracao,oft_auto_refracao.cd_agendamento,oft_auto_refracao.cd_profissional,dt_exame,cd_usuario_exame,dt_cad_exame,
                    dt_liberacao,cd_usuario_liberacao,dt_cad_liberacao,receita_dinamica,FORMAT(dp, 2, 'de_DE') dp,
                    FORMAT(od_de_dinamica, 2, 'de_DE') od_de_dinamica,FORMAT(od_dc_dinamica, 2, 'de_DE') od_dc_dinamica,
                    FORMAT(od_eixo_dinamica, 2, 'de_DE') od_eixo_dinamica,FORMAT(od_reflexo_dinamica, 2, 'de_DE') od_reflexo_dinamica,
                    FORMAT(oe_de_dinamica, 2, 'de_DE') oe_de_dinamica,FORMAT(oe_dc_dinamica, 2, 'de_DE') oe_dc_dinamica,
                    FORMAT(oe_eixo_dinamica, 2, 'de_DE') oe_eixo_dinamica,FORMAT(oe_reflexo_dinamica, 2, 'de_DE') oe_reflexo_dinamica,
                    receita_estatica,FORMAT(od_de_estatica, 2, 'de_DE') od_de_estatica,FORMAT(od_dc_estatica, 2, 'de_DE') od_dc_estatica,
                    FORMAT(od_eixo_estatica, 2, 'de_DE') od_eixo_estatica,FORMAT(od_reflexo_estatica, 2, 'de_DE') od_reflexo_estatica,
                    FORMAT(oe_de_estatica, 2, 'de_DE') oe_de_estatica,FORMAT(oe_dc_estatica, 2, 'de_DE') oe_dc_estatica,
                    FORMAT(oe_eixo_estatica, 2, 'de_DE') oe_eixo_estatica,FORMAT(oe_reflexo_estatica, 2, 'de_DE') oe_reflexo_estatica,
                    comentario,oft_auto_refracao.created_at,oft_auto_refracao.updated_at")
                        ->first();
                        
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_auto_refracao', 'oft_auto_refracao.cd_agendamento', 'agendamento.cd_agendamento')
                        ->selectRaw("agendamento.*,
                        cd_auto_refracao,oft_auto_refracao.cd_agendamento,oft_auto_refracao.cd_profissional,dt_exame,cd_usuario_exame,dt_cad_exame,
                        dt_liberacao,cd_usuario_liberacao,dt_cad_liberacao,receita_dinamica,FORMAT(dp, 2, 'de_DE') dp,
                        FORMAT(od_de_dinamica, 2, 'de_DE') od_de_dinamica,FORMAT(od_dc_dinamica, 2, 'de_DE') od_dc_dinamica,
                        FORMAT(od_eixo_dinamica, 2, 'de_DE') od_eixo_dinamica,FORMAT(od_reflexo_dinamica, 2, 'de_DE') od_reflexo_dinamica,
                        FORMAT(oe_de_dinamica, 2, 'de_DE') oe_de_dinamica,FORMAT(oe_dc_dinamica, 2, 'de_DE') oe_dc_dinamica,
                        FORMAT(oe_eixo_dinamica, 2, 'de_DE') oe_eixo_dinamica,FORMAT(oe_reflexo_dinamica, 2, 'de_DE') oe_reflexo_dinamica,
                        receita_estatica,FORMAT(od_de_estatica, 2, 'de_DE') od_de_estatica,FORMAT(od_dc_estatica, 2, 'de_DE') od_dc_estatica,
                        FORMAT(od_eixo_estatica, 2, 'de_DE') od_eixo_estatica,FORMAT(od_reflexo_estatica, 2, 'de_DE') od_reflexo_estatica,
                        FORMAT(oe_de_estatica, 2, 'de_DE') oe_de_estatica,FORMAT(oe_dc_estatica, 2, 'de_DE') oe_dc_estatica,
                        FORMAT(oe_eixo_estatica, 2, 'de_DE') oe_eixo_estatica,FORMAT(oe_reflexo_estatica, 2, 'de_DE') oe_reflexo_estatica,
                        comentario,oft_auto_refracao.created_at,oft_auto_refracao.updated_at")
                        ->orderBy('dt_agenda', 'desc')->get();

                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]); 
                     

                }

                if ($formulario->cd_formulario == 'CERATOMETRIA') {
                    $retorno = Oft_ceratometria::where('cd_agendamento', $agendamento->cd_agendamento)
                        ->selectRaw("
                    oft_ceratometria.cd_ceratometria,oft_ceratometria.cd_agendamento,oft_ceratometria.cd_profissional,oft_ceratometria.dt_exame,
                    oft_ceratometria.cd_usuario_exame,oft_ceratometria.dt_cad_exame,oft_ceratometria.dt_liberacao,oft_ceratometria.cd_usuario_liberacao,
                    oft_ceratometria.dt_cad_liberacao,FORMAT(od_curva1_ceratometria, 2, 'de_DE') od_curva1_ceratometria,FORMAT(od_curva1_milimetros, 2, 'de_DE') od_curva1_milimetros,
                    FORMAT(od_eixo1_ceratometria, 2, 'de_DE') od_eixo1_ceratometria,FORMAT(od_curva2_ceratometria, 2, 'de_DE') od_curva2_ceratometria,
                    FORMAT(od_curva2_milimetros, 2, 'de_DE') od_curva2_milimetros,FORMAT(od_eixo2_ceratometria, 2, 'de_DE') od_eixo2_ceratometria,FORMAT(od_media_ceratometria, 2, 'de_DE') od_media_ceratometria,FORMAT(od_media_milimetros, 2, 'de_DE') od_media_milimetros,FORMAT(od_cilindro_neg, 2, 'de_DE') od_cilindro_neg,FORMAT(od_eixo_neg, 2, 'de_DE') od_eixo_neg,FORMAT(od_cilindro_pos, 2, 'de_DE') od_cilindro_pos,FORMAT(od_eixo_pos, 2, 'de_DE') od_eixo_pos,FORMAT(oe_curva1_ceratometria, 2, 'de_DE') oe_curva1_ceratometria,FORMAT(oe_curva1_milimetros, 2, 'de_DE') oe_curva1_milimetros,FORMAT(oe_eixo1_ceratometria, 2, 'de_DE') oe_eixo1_ceratometria,
                    FORMAT(oe_curva2_ceratometria, 2, 'de_DE') oe_curva2_ceratometria,FORMAT(oe_curva2_milimetros, 2, 'de_DE') oe_curva2_milimetros,FORMAT(oe_eixo2_ceratometria, 2, 'de_DE') oe_eixo2_ceratometria,FORMAT(oe_media_ceratometria, 2, 'de_DE') oe_media_ceratometria,FORMAT(oe_media_milimetros, 2, 'de_DE') oe_media_milimetros,
                    FORMAT(oe_cilindro_neg, 2, 'de_DE') oe_cilindro_neg,FORMAT(oe_eixo_neg, 2, 'de_DE') oe_eixo_neg,FORMAT(oe_cilindro_pos, 2, 'de_DE') oe_cilindro_pos,
                    FORMAT(oe_eixo_pos, 2, 'de_DE') oe_eixo_pos,oft_ceratometria.obs,oft_ceratometria.created_at,oft_ceratometria.updated_at
                    ")
                        ->first();
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_ceratometria', 'oft_ceratometria.cd_agendamento', 'agendamento.cd_agendamento')
                        ->selectRaw("agendamento.*,
                        oft_ceratometria.cd_ceratometria,oft_ceratometria.cd_agendamento,oft_ceratometria.cd_profissional,oft_ceratometria.dt_exame,
                        oft_ceratometria.cd_usuario_exame,oft_ceratometria.dt_cad_exame,oft_ceratometria.dt_liberacao,oft_ceratometria.cd_usuario_liberacao,
                        oft_ceratometria.dt_cad_liberacao,FORMAT(od_curva1_ceratometria, 2, 'de_DE') od_curva1_ceratometria,FORMAT(od_curva1_milimetros, 2, 'de_DE') od_curva1_milimetros,
                        FORMAT(od_eixo1_ceratometria, 2, 'de_DE') od_eixo1_ceratometria,FORMAT(od_curva2_ceratometria, 2, 'de_DE') od_curva2_ceratometria,
                        FORMAT(od_curva2_milimetros, 2, 'de_DE') od_curva2_milimetros,FORMAT(od_eixo2_ceratometria, 2, 'de_DE') od_eixo2_ceratometria,FORMAT(od_media_ceratometria, 2, 'de_DE') od_media_ceratometria,FORMAT(od_media_milimetros, 2, 'de_DE') od_media_milimetros,FORMAT(od_cilindro_neg, 2, 'de_DE') od_cilindro_neg,FORMAT(od_eixo_neg, 2, 'de_DE') od_eixo_neg,FORMAT(od_cilindro_pos, 2, 'de_DE') od_cilindro_pos,FORMAT(od_eixo_pos, 2, 'de_DE') od_eixo_pos,FORMAT(oe_curva1_ceratometria, 2, 'de_DE') oe_curva1_ceratometria,FORMAT(oe_curva1_milimetros, 2, 'de_DE') oe_curva1_milimetros,FORMAT(oe_eixo1_ceratometria, 2, 'de_DE') oe_eixo1_ceratometria,
                        FORMAT(oe_curva2_ceratometria, 2, 'de_DE') oe_curva2_ceratometria,FORMAT(oe_curva2_milimetros, 2, 'de_DE') oe_curva2_milimetros,FORMAT(oe_eixo2_ceratometria, 2, 'de_DE') oe_eixo2_ceratometria,FORMAT(oe_media_ceratometria, 2, 'de_DE') oe_media_ceratometria,FORMAT(oe_media_milimetros, 2, 'de_DE') oe_media_milimetros,
                        FORMAT(oe_cilindro_neg, 2, 'de_DE') oe_cilindro_neg,FORMAT(oe_eixo_neg, 2, 'de_DE') oe_eixo_neg,FORMAT(oe_cilindro_pos, 2, 'de_DE') oe_cilindro_pos,
                        FORMAT(oe_eixo_pos, 2, 'de_DE') oe_eixo_pos,oft_ceratometria.obs,oft_ceratometria.created_at,oft_ceratometria.updated_at
                        ")
                        ->orderBy('dt_agenda', 'desc')->get();

                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]); 

                }

                if ($formulario->cd_formulario == 'CERATOSCOPIA_COMP') {

                    $retorno['image'] = Oft_formularios_imagens::where('cd_agendamento', $agendamento->cd_agendamento)->where('cd_formulario', 'CERATOSCOPIA_COMP')
                    ->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')->get();
                    
                    foreach ($retorno['image'] as $imgs) {
                        clearstatcache(); // Limpamos o cache de arquivos do PHP
                        $CaminhoImg = env('URL_IMG_EXAMES') . "/" . $imgs->caminho_img;
                        if (is_file($CaminhoImg)) {
                            $mime_type = mime_content_type($CaminhoImg);
                            $data = file_get_contents($CaminhoImg);
                            $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                        } else {
                            $ArrayImg['conteudo_img'] = null;
                        }
                        $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
                        $ArrayImg['data'] = $imgs->dt_exame;
                        $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
                        $retorno['array_img'][] = $ArrayImg;
                    }
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_formularios_imagens', 'oft_formularios_imagens.cd_agendamento', 'agendamento.cd_agendamento')
                        ->orderBy('dt_agenda', 'desc')->get();
                        
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]); 

                }

                if ($formulario->cd_formulario == 'ANAMNESE') {
                    $retorno = Oft_anamnese::where('cd_agendamento', $agendamento->cd_agendamento)->first();
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_anamnese', 'oft_anamnese.cd_agendamento', 'agendamento.cd_agendamento')
                        ->orderBy('dt_agenda', 'desc')->get();
 
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]); 
                }

                if ($formulario->cd_formulario == 'TONOMETRIA_APLANACAO') {
                    $retorno = Oft_tonometria_aplanacao::where('cd_agendamento', $agendamento->cd_agendamento)
                        ->selectRaw("oft_tonometria_aplanacao.*, FORMAT(pressao_od, 2, 'de_DE') pressao_od, FORMAT(pressao_oe, 2, 'de_DE') pressao_oe")
                        ->first(); 
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_tonometria_aplanacao', 'oft_tonometria_aplanacao.cd_agendamento', 'agendamento.cd_agendamento')
                        ->join('equipamentos', 'equipamentos.cd_equipamento', 'oft_tonometria_aplanacao.cd_equipamento')
                        ->selectRaw("agendamento.*,oft_tonometria_aplanacao.*, FORMAT(pressao_od, 2, 'de_DE') pressao_od, FORMAT(pressao_oe, 2, 'de_DE') pressao_oe,nm_equipamento")
                        ->orderBy('dt_agenda', 'desc')->get();
                        
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);
                }

                if ($formulario->cd_formulario == 'REFRACAO') {
                    $retorno = Oft_refracao::where('cd_agendamento', $agendamento->cd_agendamento)
                        ->selectRaw("oft_refracao.*, FORMAT(ard_od_de, 2, 'de_DE') ard_od_de,FORMAT(ard_od_dc, 2, 'de_DE') ard_od_dc,
                    FORMAT(ard_od_eixo, 2, 'de_DE') ard_od_eixo,FORMAT(ard_od_add, 2, 'de_DE') ard_od_add,FORMAT(ard_oe_de, 2, 'de_DE') ard_oe_de,
                    FORMAT(ard_oe_dc, 2, 'de_DE') ard_oe_dc,FORMAT(ard_oe_eixo, 2, 'de_DE') ard_oe_eixo,FORMAT(ard_oe_add, 2, 'de_DE') ard_oe_add,
                    FORMAT(are_od_de, 2, 'de_DE') are_od_de,FORMAT(are_od_dc, 2, 'de_DE') are_od_dc,FORMAT(are_od_eixo, 2, 'de_DE') are_od_eixo,
                    FORMAT(are_oe_de, 2, 'de_DE') are_oe_de,FORMAT(are_oe_dc, 2, 'de_DE') are_oe_dc,FORMAT(are_oe_eixo, 2, 'de_DE') are_oe_eixo
                    ")
                        ->first();
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_refracao', 'oft_refracao.cd_agendamento', 'agendamento.cd_agendamento')
                        ->selectRaw("agendamento.*,oft_refracao.*, FORMAT(ard_od_de, 2, 'de_DE') ard_od_de,FORMAT(ard_od_dc, 2, 'de_DE') ard_od_dc,
                        FORMAT(ard_od_eixo, 2, 'de_DE') ard_od_eixo,FORMAT(ard_od_add, 2, 'de_DE') ard_od_add,FORMAT(ard_oe_de, 2, 'de_DE') ard_oe_de,
                        FORMAT(ard_oe_dc, 2, 'de_DE') ard_oe_dc,FORMAT(ard_oe_eixo, 2, 'de_DE') ard_oe_eixo,FORMAT(ard_oe_add, 2, 'de_DE') ard_oe_add,
                        FORMAT(are_od_de, 2, 'de_DE') are_od_de,FORMAT(are_od_dc, 2, 'de_DE') are_od_dc,FORMAT(are_od_eixo, 2, 'de_DE') are_od_eixo,
                        FORMAT(are_oe_de, 2, 'de_DE') are_oe_de,FORMAT(are_oe_dc, 2, 'de_DE') are_oe_dc,FORMAT(are_oe_eixo, 2, 'de_DE') are_oe_eixo
                        ")
                        ->orderBy('dt_agenda', 'desc')->get();
                        
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);
                }

                if ($formulario->cd_formulario == 'RECEITA_OCULOS') {
                    $retorno = Oft_receita_oculos::where('cd_agendamento', $agendamento->cd_agendamento)
                        ->selectRaw("oft_receita_oculos.*,
                    FORMAT(longe_od_de, 2, 'de_DE') longe_od_de,FORMAT(longe_od_dc, 2, 'de_DE') longe_od_dc,FORMAT(longe_od_eixo, 2, 'de_DE') longe_od_eixo,
                    FORMAT(longe_od_add, 2, 'de_DE') longe_od_add,FORMAT(longe_oe_de, 2, 'de_DE') longe_oe_de,FORMAT(longe_oe_dc, 2, 'de_DE') longe_oe_dc,
                    FORMAT(longe_oe_eixo, 2, 'de_DE') longe_oe_eixo,FORMAT(longe_oe_add, 2, 'de_DE') longe_oe_add,FORMAT(perto_od_de, 2, 'de_DE') perto_od_de,
                    FORMAT(perto_od_dc, 2, 'de_DE') perto_od_dc,FORMAT(perto_od_eixo, 2, 'de_DE') perto_od_eixo,FORMAT(perto_oe_de, 2, 'de_DE') perto_oe_de,
                    FORMAT(perto_oe_dc, 2, 'de_DE') perto_oe_dc,FORMAT(perto_oe_eixo, 2, 'de_DE') perto_oe_eixo,FORMAT(inter_od_de, 2, 'de_DE') inter_od_de,
                    FORMAT(inter_od_dc, 2, 'de_DE') inter_od_dc,FORMAT(inter_od_eixo, 2, 'de_DE') inter_od_eixo,FORMAT(inter_oe_de, 2, 'de_DE') inter_oe_de,
                    FORMAT(inter_oe_dc, 2, 'de_DE') inter_oe_dc,FORMAT(inter_oe_eixo, 2, 'de_DE') inter_oe_eixo 
                    ")
                        ->first();
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_receita_oculos', 'oft_receita_oculos.cd_agendamento', 'agendamento.cd_agendamento')
                        ->selectRaw("oft_receita_oculos.*,agendamento.*, oft_receita_oculos.created_at created_data,
                        FORMAT(longe_od_de, 2, 'de_DE') longe_od_de,FORMAT(longe_od_dc, 2, 'de_DE') longe_od_dc,FORMAT(longe_od_eixo, 2, 'de_DE') longe_od_eixo,
                        FORMAT(longe_od_add, 2, 'de_DE') longe_od_add,FORMAT(longe_oe_de, 2, 'de_DE') longe_oe_de,FORMAT(longe_oe_dc, 2, 'de_DE') longe_oe_dc,
                        FORMAT(longe_oe_eixo, 2, 'de_DE') longe_oe_eixo,FORMAT(longe_oe_add, 2, 'de_DE') longe_oe_add,FORMAT(perto_od_de, 2, 'de_DE') perto_od_de,
                        FORMAT(perto_od_dc, 2, 'de_DE') perto_od_dc,FORMAT(perto_od_eixo, 2, 'de_DE') perto_od_eixo,FORMAT(perto_oe_de, 2, 'de_DE') perto_oe_de,
                        FORMAT(perto_oe_dc, 2, 'de_DE') perto_oe_dc,FORMAT(perto_oe_eixo, 2, 'de_DE') perto_oe_eixo,FORMAT(inter_od_de, 2, 'de_DE') inter_od_de,
                        FORMAT(inter_od_dc, 2, 'de_DE') inter_od_dc,FORMAT(inter_od_eixo, 2, 'de_DE') inter_od_eixo,FORMAT(inter_oe_de, 2, 'de_DE') inter_oe_de,
                        FORMAT(inter_oe_dc, 2, 'de_DE') inter_oe_dc,FORMAT(inter_oe_eixo, 2, 'de_DE') inter_oe_eixo 
                        ")
                        ->orderBy('dt_agenda', 'desc')->get();
                        
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);
                }

                if ($formulario->cd_formulario == 'ATESTADOS') {
                    $retorno = Oft_documento::where('cd_agendamento', $agendamento->cd_agendamento)->where('cd_formulario', 'ATESTADOS')->first();
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_documentos', 'oft_documentos.cd_agendamento', 'agendamento.cd_agendamento')
                        ->where('oft_documentos.cd_formulario', 'ATESTADOS')
                        ->selectRaw("oft_documentos.*,oft_documentos.created_at created_data ")
                        ->orderBy('dt_agenda', 'desc')->get();

                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);
                }

                if ($formulario->cd_formulario == 'RECEITAS') {
                    $retorno = Oft_documento::where('cd_agendamento', $agendamento->cd_agendamento)->where('cd_formulario', 'RECEITAS')->first();
                    //Historico do Paciente
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                        ->join('oft_documentos', 'oft_documentos.cd_agendamento', 'agendamento.cd_agendamento')
                        ->where('oft_documentos.cd_formulario', 'RECEITAS')
                        ->selectRaw("oft_documentos.*,oft_documentos.created_at created_data ")
                        ->orderBy('dt_agenda', 'desc')->get();
                        
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);
                }

                if ($formulario->cd_formulario == 'FUNDOSCOPIA') {
                    $retorno['oft_fundoscopia'] = Oft_fundoscopia::where('cd_agendamento', $agendamento->cd_agendamento)->first();
                    $retorno['imagens'] = Oft_formularios_imagens::where('cd_agendamento', $agendamento->cd_agendamento)->where('cd_formulario', 'FUNDOSCOPIA')->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')->get();
                    foreach ($retorno['imagens'] as $imgs) {
                        clearstatcache(); // Limpamos o cache de arquivos do PHP
                        $CaminhoImg = env('URL_IMG_EXAMES') . "/" . $imgs->caminho_img;
                        if (is_file($CaminhoImg)) {
                            $mime_type = mime_content_type($CaminhoImg);
                            $data = file_get_contents($CaminhoImg);
                            $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                        } else {
                            $ArrayImg['conteudo_img'] = null;
                        }
                        $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
                        $ArrayImg['data'] = $imgs->dt_exame;
                        $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
                        $retorno['array_img'][] = $ArrayImg;
                    }
   
                    $historico = Agendamento::with(['profissional'])->where('cd_paciente', $agendamento->cd_paciente)
                    ->join('oft_fundoscopia', 'oft_fundoscopia.cd_agendamento', 'agendamento.cd_agendamento')
                    ->orderBy('dt_agenda', 'desc')->get();
                        
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);

                }

                if ($formulario->cd_formulario == 'EXAME') { 

                    $historico = AgendamentoItens::with(['exame', 'img','formulario','usuario_laudo',
                    'atendimento','atendimento.profissional','atendimento.paciente','atendimento.convenio'])
                    ->whereHas('atendimento', function($query) use($agendamento) {
                        $query->where('cd_paciente', $agendamento->cd_paciente);
                     })
                     ->selectRaw("agendamento_itens.*,DATE_FORMAT(dt_laudo, '%d/%m/%Y') data_laudo,DATE_FORMAT(created_at, '%d/%m/%Y') created_data")
                     ->orderBy('created_at', 'desc')->get();

                                
                    foreach ($historico as $key => $imgs) {

                        if(isset($imgs->img))
                        foreach ($imgs->img as $keyy => $imgs) {
                           clearstatcache();  
                           $CaminhoImg = env('URL_IMG_EXAMES') . "/" . $imgs->caminho_img;
                           if (is_file($CaminhoImg)) {
                               $mime_type = mime_content_type($CaminhoImg);
                               $data = file_get_contents($CaminhoImg);
                               $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                           } else {
                               $ArrayImg['conteudo_img'] = null;
                           }  
                           $historico[$key]->img[$keyy]['img'] = $ArrayImg['conteudo_img'];
                       } 
                    }
                     
                    return response()->json(['retorno'=> null,'historico'=> $historico,'formulario'=>$formulario]);

                }

                if ($formulario->cd_formulario == 'RESERVA_CIRURGIA') {

                    $retorno['query'] = Oft_reserva_cirurgia::with(['cirurgia', 'cirurgiao', 'opme'])->where('cd_agendamento', $agendamento->cd_agendamento)->first();
                    $retorno['opme'] = null; 
                    if(isset($retorno['query']->opme)){
                        foreach($retorno['query']->opme as $item){
                            $retorno['opme'][] = $item->cd_produto;
                        }
                    }
 

                    $historico = Oft_reserva_cirurgia::with('profissional','cirurgiao','cirurgia','opme.produtos')
                    ->join('agendamento', 'oft_reserva_cirurgia.cd_agendamento', 'agendamento.cd_agendamento')
                    ->selectRaw("oft_reserva_cirurgia.*,agendamento.*,oft_reserva_cirurgia.created_at created_data ")
                    ->where('cd_paciente', $agendamento->cd_paciente)
                    ->orderBy('dt_agenda', 'desc')->get();
 
                    return response()->json(['retorno'=> $retorno,'historico'=> $historico,'formulario'=>$formulario]);

                }

                $equipamento = Equipamento::all();

                $agen = $agendamento->load('paciente', 'documentos', 'profissional', 'convenio');

                return  view($caminhoTela, ['formulario' => $formulario, 'historico' => $historico, 'agendamento' => $agen, 'retorno' => $retorno, 'equipamento' => $equipamento, 'parametros' => $parametros]);
            } else {
                return view('rpclinica.consulta_formularios.error', ['tipo' => "Formulario não Configurado", 'title' => $formulario->nm_formulario]);
            }
        } catch (\Exception $e) {
            $dados = $e->getMessage();
            return response()->json(['message' => $dados], 400);
        }
    }
 
}
