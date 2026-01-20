<?php


namespace App\Http\Controllers\rpclinica;

use App\Exports\RelatorioExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\rpclinica\FluxoCaixa;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\PerfilRelatorio;
use App\Model\rpclinica\Relatorio;
use App\Model\rpclinica\RelatorioCalculos;
use App\Model\rpclinica\RelatorioCampos;
use App\Model\rpclinica\RelatorioOrdem;
use App\Model\rpclinica\RelatorioParametros;
use App\Model\rpclinica\SituacaoItem;
use App\Model\rpclinica\ViewsRelatorios;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class Relatorios extends Controller
{

    public function index() {
        $cd_empresa = auth()->user()->cd_empresa;

        $relatoriosPerfil=PerfilRelatorio::where('cd_perfil',auth()->user()->cd_perfil)->selectRaw("cd_relatorio")->get();
        $cdRelatorio =  array_column($relatoriosPerfil->toArray() , "cd_relatorio");
    
        $relatorios = Relatorio::where('cd_empresa', $cd_empresa)
                    ->whereIn('id',$cdRelatorio)
                    ->get();
     
        return view('rpclinica.relatorios.relatorios', compact('relatorios'));
    }

    public function listarRelatorios(Request $request) {
        $cd_empresa = auth()->user()->cd_empresa;

        $relatorios = Relatorio::where('cd_empresa', $cd_empresa)
                    ->get();

        return view('rpclinica.relatorios.list', compact('relatorios'));
    }

    public function relatorios(Request $request) {

          
        $relatorio_id = $request->relatorio_id;

        $relatorio = Relatorio::find($relatorio_id);
        $parametros = RelatorioParametros::where('relatorio_id', $relatorio->id)->get();
        $profissional = Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get();
        $exame = Exame::where('sn_ativo','S')->orderBy('nm_exame')->get();
        return view('rpclinica.relatorios.gerar_relatorio', compact('parametros', 'relatorio_id', 'relatorio','profissional','exame'));
    }

    private function getDadosRelatorio(Relatorio $relatorio, $parametros_front = [], $limit = null){
        $nome_colunas = RelatorioCampos::where('relatorio_id', $relatorio->id)->get();
        $calculos = RelatorioCalculos::where('relatorio_id', $relatorio->id)->get();
        $parametros = RelatorioParametros::where('relatorio_id', $relatorio->id)->get();
        $ordenar = RelatorioOrdem::where('relatorio_id', $relatorio->id)->get();

        $colunas = [];
        $view_nome = $relatorio->conteudo;

        $query = DB::table($view_nome);

        foreach($nome_colunas as $coluna){
            $query->addSelect($coluna->nome_coluna);

            $colunas[$coluna->nome_coluna]['alinhamento'] = $coluna->alinhamento;
            $colunas[$coluna->nome_coluna]['mascara'] = $coluna->mascara;
        }

        foreach($calculos as $calculo){
            $aliasCalculo = $calculo->funcao . '_' . $calculo->nome_coluna;
            $query->addSelect(DB::raw($calculo->funcao . '(' . $calculo->nome_coluna . ') as ' . $aliasCalculo));
            $colunasGroupBy = array_column($nome_colunas->toArray(), 'nome_coluna');
            $query->groupBy($colunasGroupBy);

            $colunas[$aliasCalculo]['alinhamento'] = 'left';
            $colunas[$aliasCalculo]['mascara'] = '';
        }
        
        foreach($parametros as $parametro){
   
            if(isset($parametros_front[$parametro->id]) && $parametros_front[$parametro->id]){
                
                if ($this->isDateFormat($parametros_front[$parametro->id])) {
                    // Converte para o padrão de data do banco
                    $data_obj = DateTime::createFromFormat('d/m/Y', $parametros_front[$parametro->id]);
                    $parametros_front[$parametro->id] = $data_obj->format('Y-m-d');
                }
                
                if($parametro->operador == 'between'){ 
                    
                    $values = explode(', ', $parametros_front[$parametro->id]);
                    $query->whereBetween($parametro->nome_coluna, [$values[0], $values[1]]);
                } else {
                    $query->where($parametro->nome_coluna, $parametro->operador, $parametros_front[$parametro->id]);
                }
            }
        }

        foreach($ordenar as $ordem){
            $query->orderBy($ordem->nome_coluna, $ordem->tipo);
        }

        if($limit){
            $query->limit($limit);
        }
  

        if($relatorio->restricao=='PROF'){
            if(isset($parametros_front['user_cd_profissional'])){
                $query->where("cd_profissional", ($parametros_front['user_cd_profissional']) ? $parametros_front['user_cd_profissional'] : '-999');
            }
        }
 
        $dados_view = $query->get();

        return [
            'colunas' => $colunas,
            'dados_view' => $dados_view
        ];
    }

    public function getInfoRelatorio(Request $request){
        $relatorio_id = $request->relatorio_id;
        $parametros_front = $request->all();
        $parametros_front['user_cd_profissional'] = $request->user()->cd_profissional;

        $relatorio = Relatorio::find($relatorio_id);
        $relatorio_titulo = $relatorio->titulo;
  
        $dados = $this->getDadosRelatorio($relatorio, $parametros_front);
        $colunas = $dados['colunas'];
        $dados_view = $dados['dados_view'];

        $layout = $relatorio->layout == 'P' ? 'landscape' : 'portrait';
 
        if($relatorio->tipo_relatorio == 'PDF'){
            $pdf = Pdf::loadView('rpclinica.relatorios.imprimir_relatorio', compact('colunas', 'dados_view', 'relatorio_titulo')); 
            return $pdf->setPaper('a4', $layout)->stream('relatorio.pdf');
        } else if ($relatorio->tipo_relatorio == 'XLS') {
            return Excel::download(new RelatorioExport($colunas, $dados_view, $relatorio_titulo), 'relatorio.xls', \Maatwebsite\Excel\Excel::XLS);
        }else if ($relatorio->tipo_relatorio == 'GCOL') { 
            return view('rpclinica.relatorios.grafico_colunas', compact('colunas', 'dados_view','relatorio'));
        }else  if ($relatorio->tipo_relatorio == 'GPIZ') {
            return view('rpclinica.relatorios.grafico_piza', compact('colunas', 'dados_view','relatorio')); 
        }else if ($relatorio->tipo_relatorio == 'GCOLC') {
            return view('rpclinica.relatorios.grafico_colunas_duplas', compact('colunas', 'dados_view','relatorio')); 
        }else { 
            return view('rpclinica.relatorios.relacao', compact('colunas', 'dados_view','relatorio'));
        }
    }

    public function agendamento(Request $request) {

        if($request['tipo']=='AG'){
            $profissional = Profissional::all();
            $empresa = Empresa::find($request->user()->cd_empresa);
            $relatorio=RpclinicaAgendamento::with('agenda', 'profissional', 'paciente','convenio', 'especialidade', 'procedimento')->where('dt_agenda','>=', $request->dti)->where('dt_agenda','<=', $request->dtf)->whereNotNull('cd_paciente');
            if($request->profissional){
                $relatorio =$relatorio->where('cd_profissional',  $request->profissional);
            }
            $relatorio =$relatorio->orderBy('dt_agenda')->get();

            return view('rpclinica.relatorios.relatorio_agendamento', compact('profissional','empresa','relatorio','request'));
        }

        if($request['tipo']=='FI'){
            $profissional = Profissional::all();
            $empresa = Empresa::find($request->user()->cd_empresa);
            $relatorio=RpclinicaAgendamento::with('agenda', 'profissional', 'paciente','convenio', 'especialidade', 'procedimento')->where('dt_agenda','>=', $request->dti)->where('dt_agenda','<=', $request->dtf)->whereNotNull('cd_paciente');
            if($request->profissional){
                $relatorio =$relatorio->where('cd_profissional',  $request->profissional);
            }
            $relatorio =$relatorio->orderBy('dt_agenda')->get();

            return view('rpclinica.relatorios.relatorio_financeiro', compact('profissional','empresa','relatorio','request'));
        }
    }
 
    public function create() {
        $views = ViewsRelatorios::all(); 
        return view('rpclinica.relatorios.add', compact('views'));
    }

    public function addRelatorio(Request $request){
        $titulo = $request->titulo;
        $area = $request->area;
        $conteudo = $request->conteudo;
        $tipo_relatorio = $request->tipo_relatorio;
        $layout = $request->layout;
        $restricao = $request->restricao;
        $empresa_id = auth()->user()->cd_empresa;

        $relatorio = Relatorio::create([
            'titulo' => $titulo,
            'area' => $area,
            'conteudo' => $conteudo,
            'restricao' => $restricao,
            'tipo_relatorio' => $tipo_relatorio,
            'layout' => $layout,
            'cd_empresa' => $empresa_id,
        ]);

        $this->addRelatorioCampos($request->relatorio_campos, $relatorio->id);
        $this->addRelatorioCalculos($request->relatorio_calculos, $relatorio->id);
        $this->addRelatorioOrdem($request->relatorio_ordem, $relatorio->id);
        $this->addRelatorioParametros($request->relatorio_parametros, $relatorio->id);

        return response()->json([
            'message' => 'Relatório criado com sucesso!',
            'status' => 200
        ]);
    }

    public function addRelatorioCampos($request, $relatorio_id){
        $relatorio_id = $relatorio_id;
        $empresa_id = auth()->user()->cd_empresa;

        foreach($request as $value){
            $nome_coluna = $value['nome_coluna'];
            $alinhamento = $value['alinhamento'];
            $mascara = $value['mascara'];
            $limite = $value['limite'];

            RelatorioCampos::create([
                'nome_coluna' => $nome_coluna,
                'alinhamento' => $alinhamento,
                'mascara' => $mascara,
                'limite' => $limite,
                'relatorio_id' => $relatorio_id,
                'cd_empresa' => $empresa_id,
            ]);
        }
    }

    public function addRelatorioCalculos($request, $relatorio_id){
        $relatorio_id = $relatorio_id;
        $empresa_id = auth()->user()->cd_empresa;

        foreach($request as $value){
            $nome_coluna = $value['nome_coluna'];
            $funcao = $value['funcao'];

            RelatorioCalculos::create([
                'nome_coluna' => $nome_coluna,
                'funcao' => $funcao,
                'relatorio_id' => $relatorio_id,
                'cd_empresa' => $empresa_id,
            ]);
        }
    }

    public function addRelatorioOrdem($request, $relatorio_id){
        $relatorio_id = $relatorio_id;
        $empresa_id = auth()->user()->cd_empresa;

        foreach($request as $value){
            $nome_coluna = $value['nome_coluna'];
            $tipo = $value['tipo'];

            RelatorioOrdem::create([
                'nome_coluna' => $nome_coluna,
                'tipo' => $tipo,
                'relatorio_id' => $relatorio_id,
                'cd_empresa' => $empresa_id,
            ]);
        }
    }

    public function addRelatorioParametros($request, $relatorio_id){
        $relatorio_id = $relatorio_id;
        $empresa_id = auth()->user()->cd_empresa;

        foreach($request as $value){
            $nome_coluna = $value['nome_coluna'];
            $operador = $value['operador'];
            $obrigatorio = $value['obrigatorio'];
            $p_padrao = (isset($value['p_padrao'])) ? $value['p_padrao'] : null;

            RelatorioParametros::create([
                'nome_coluna' => $nome_coluna,
                'operador' => $operador,
                'obrigatorio' => $obrigatorio,
                'relatorio_id' => $relatorio_id,
                'cd_empresa' => $empresa_id,
                'cd_param_padrao' => $p_padrao,
            ]);
        }
    }

    public function getRelatorio(Request $request){
        $relatorio_id = $request->relatorio_id;

        $relatorio = Relatorio::find($relatorio_id);

        $campos = RelatorioCampos::where('relatorio_id', $relatorio->id)->get();
        $parametros = RelatorioParametros::where('relatorio_id', $relatorio->id)->get();
        $calculos = RelatorioCalculos::where('relatorio_id', $relatorio->id)->get();
        $ordenar = RelatorioOrdem::where('relatorio_id', $relatorio->id)->get();
        $views = ViewsRelatorios::all();

        $dados = $this->getDadosRelatorio($relatorio, [], 5);
        $colunas = $dados['colunas'];
        $dados_view = $dados['dados_view'];

        return view('rpclinica.relatorios.edit', compact('relatorio', 'campos', 'parametros', 'calculos', 'ordenar', 'views', 'colunas', 'dados_view'));
    }

    public function updateRelatorio(Request $request){
        $relatorio = Relatorio::find($request->relatorio_id);

        $relatorio->titulo = $request->titulo;
        $relatorio->area = $request->area;
        $relatorio->conteudo = $request->conteudo;
        $relatorio->tipo_relatorio = $request->tipo_relatorio;
        $relatorio->layout = $request->layout;
        $relatorio->restricao = $request->restricao; 

        $relatorio->save();

        RelatorioCampos::where('relatorio_id', $relatorio->id)->delete();
        RelatorioOrdem::where('relatorio_id', $relatorio->id)->delete();
        RelatorioCalculos::where('relatorio_id', $relatorio->id)->delete();
        RelatorioParametros::where('relatorio_id', $relatorio->id)->delete();

        $this->addRelatorioCampos($request->relatorio_campos, $relatorio->id);
        $this->addRelatorioOrdem($request->relatorio_ordem, $relatorio->id);
        $this->addRelatorioCalculos($request->relatorio_calculos, $relatorio->id);
        $this->addRelatorioParametros($request->relatorio_parametros, $relatorio->id);

        return response()->json([
            'message' => 'Relatório editado com sucesso!',
            'status' => 200
        ]);
    }

    public function deleteRelatorio(Request $request){
        $relatorio = Relatorio::find($request->relatorio_id);

        $relatorio->delete();

        RelatorioCampos::where('relatorio_id', $relatorio->id)->delete();
        RelatorioOrdem::where('relatorio_id', $relatorio->id)->delete();
        RelatorioCalculos::where('relatorio_id', $relatorio->id)->delete();
        RelatorioParametros::where('relatorio_id', $relatorio->id)->delete();

        return response()->json([
            'message' => 'Relatório deletado com sucesso!',
            'status' => 200
        ]);
    }

    public function getConteudoView(Request $request){
        $colunas = Schema::getColumnListing($request->conteudo);

        return response()->json([
            'colunas' => $colunas
        ]);
    }

    public function fluxo_caixa() {
        return FluxoCaixa::index();
    }

    private function isDateFormat($date){
        $pattern = "/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/";
        return preg_match($pattern, $date);
    }

    public function producao(Request $request){
          

        $dados['query']= [];
        $dados['exame']=Exame::where('sn_ativo','S')->orderBy('nm_exame')->get(); 
        $dados['situacao']=SituacaoItem::where('tipo','central_laudos')->orderBy('nm_situacao_itens')->get();
        $dados['convenio']=Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();

        $dados['profissional']=Profissional::where('sn_ativo','S');
        if(!$request->user()->visualizar_exame=='S'){
            $dados['profissional']=$dados['profissional']->where('cd_profissional', ( ($request->user()->sn_todos_gendamentos) ? $request->user()->sn_todos_gendamentos : '0' ) );
            $dados['setar_prof']='S';
        }
        $dados['profissional']=$dados['profissional']->orderBy('nm_profissional')->get(); 
 
        $parametros = '';
        if($request['relatorio']=='S'){

            if($request['tipo_relatorio']=='REL'){
                $dados['query']=AgendamentoItens::  
                join('agendamento','agendamento.cd_agendamento','agendamento_itens.cd_agendamento') 
                ->join('paciente','paciente.cd_paciente','agendamento.cd_paciente')
                ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
                ->join('convenio','convenio.cd_convenio','agendamento.cd_convenio')
                ->join('situacao_itens','agendamento_itens.situacao','situacao_itens.cd_situacao_itens')
                ->where('situacao_itens.tipo','central_laudos')
                ->selectRaw("agendamento_itens.*, agendamento.*, date_format(agendamento_itens.dt_laudo,'%d/%m/%Y %H:%i') data_laudo, 
                date_format(dt_atendimento,'%d/%m/%Y') data_atendimento,  nm_paciente,nm_convenio,nm_profissional,nm_situacao_itens ");
                 
                if($request['tp_data']=='dt_laudo'){
                    $parametros = $parametros . ' [ Data do Laudo ]  ' . $request['dti'] . ' - ' . $request['dtf'];
                    $dados['query']=$dados['query']->whereRaw("date(agendamento_itens.dt_laudo) >= '".$request['dti']."'")
                    ->whereRaw("date(agendamento_itens.dt_laudo) <= '".$request['dtf']."'");
                }
                if($request['tp_data']=='dt_atendimento'){
                    $parametros = $parametros . ' [ Data do Atendimento ]  ' . $request['dti'] . ' - ' . $request['dtf'];
                    $dados['query']=$dados['query']->whereRaw("date(agendamento.dt_atendimento) >= '".$request['dti']."'")
                    ->whereRaw("date(agendamento.dt_atendimento) <= '".$request['dtf']."'");
                }
                if($request['profissional']){
    
                    $parametros = $parametros . ' [ Profissional ]  ' . $request['profissional'].' - '. Profissional::where('cd_profissional',$request['profissional'])->select('nm_profissional')->first()->nm_profissional;
                    $dados['query']=$dados['query']->whereRaw(" agendamento.cd_profissional = '".$request['profissional']."'");
                }
                if($request['exame']){
                    $parametros = $parametros . ' | [ Exame ]  ' . $request['exame'].' - '. Exame::where('cd_exame',$request['exame'])->select('nm_exame')->first()->nm_exame;
                    $dados['query']=$dados['query']->whereRaw(" agendamento_itens.cd_exame = '".$request['exame']."'");
                }
                if($request['paciente']){
                    $parametros = $parametros . ' | [ Paciente ]  ' . $request['paciente'];
                    $dados['query']=$dados['query']->whereRaw(" upper(paciente.nm_paciente) like '".mb_strtoupper($request['paciente'])."%'");
                }
                if($request['convenio']){
                    $parametros = $parametros . ' | [ Convênio ]  ' . $request['convenio'].' - '. Convenio::where('cd_convenio',$request['convenio'])->select('nm_convenio')->first()->nm_convenio;
                    $dados['query']=$dados['query']->whereRaw(" agendamento.cd_convenio = '".$request['convenio']."'");
                }
                if($request['situacao']){
                    $parametros = $parametros . ' | [ Situacao ]  ' . $request['situacao'];
                    $dados['query']=$dados['query']->whereRaw(" agendamento_itens.situacao = '".$request['situacao']."'");
                }
                $dados['query']=$dados['query']->with(['exame','usuario_laudo'])
                ->orderBy('dt_atendimento')
                ->get();  

            }
                
            if($request['tipo_relatorio']=='GRA'){

                $dados['paleta']=array('#db277f','#319d8f','#e9ec5c','#f2a267','#6a2c6f','#a05ab4','#e04d31','#be4938','#e57056');

                $dados['laudo']=AgendamentoItens::  
                join('agendamento','agendamento.cd_agendamento','agendamento_itens.cd_agendamento')   
                ->whereRaw("date(agendamento_itens.dt_laudo) >= '".$request['dti']."'")
                ->whereRaw("date(agendamento_itens.dt_laudo) <= '".$request['dtf']."'");
                if($request['profissional']){
    
                    $parametros = $parametros . ' [ Profissional ]  ' . $request['profissional'].' - '. Profissional::where('cd_profissional',$request['profissional'])->select('nm_profissional')->first()->nm_profissional;
                    $dados['laudo']=$dados['laudo']->whereRaw(" agendamento.cd_profissional = '".$request['profissional']."'");
                }
                if($request['exame']){
                    $parametros = $parametros . ' | [ Exame ]  ' . $request['exame'].' - '. Exame::where('cd_exame',$request['exame'])->select('nm_exame')->first()->nm_exame;
                    $dados['laudo']=$dados['laudo']->whereRaw(" agendamento_itens.cd_exame = '".$request['exame']."'");
                }
                if($request['paciente']){
                    $parametros = $parametros . ' | [ Paciente ]  ' . $request['paciente'];
                    $dados['laudo']=$dados['laudo']->whereRaw(" upper(paciente.nm_paciente) like '".mb_strtoupper($request['paciente'])."%'");
                }
                if($request['convenio']){
                    $parametros = $parametros . ' | [ Convênio ]  ' . $request['convenio'].' - '. Convenio::where('cd_convenio',$request['convenio'])->select('nm_convenio')->first()->nm_convenio;
                    $dados['laudo']=$dados['laudo']->whereRaw(" agendamento.cd_convenio = '".$request['convenio']."'");
                }
                if($request['situacao']){
                    $parametros = $parametros . ' | [ Situacao ]  ' . $request['situacao'];
                    $dados['laudo']=$dados['laudo']->whereRaw(" agendamento_itens.situacao = '".$request['situacao']."'");
                }
                $dados['laudo']=$dados['laudo']->orderBy('dt_atendimento')
                ->with('exame')
                ->where('sn_laudo','1')
                ->selectRaw("cd_exame,count(*) qtde")
                ->groupByRaw('cd_exame')
                ->orderByRaw("2")
                ->get();  

                
                $dados['atendimento']=AgendamentoItens::  
                join('agendamento','agendamento.cd_agendamento','agendamento_itens.cd_agendamento')   
                ->whereRaw("date(agendamento.dt_atendimento) >= '".$request['dti']."'")
                ->whereRaw("date(agendamento.dt_atendimento) <= '".$request['dtf']."'");
                if($request['profissional']){
    
                    $parametros = $parametros . ' [ Profissional ]  ' . $request['profissional'].' - '. Profissional::where('cd_profissional',$request['profissional'])->select('nm_profissional')->first()->nm_profissional;
                    $dados['atendimento']=$dados['atendimento']->whereRaw(" agendamento.cd_profissional = '".$request['profissional']."'");
                }
                if($request['exame']){
                    $parametros = $parametros . ' | [ Exame ]  ' . $request['exame'].' - '. Exame::where('cd_exame',$request['exame'])->select('nm_exame')->first()->nm_exame;
                    $dados['atendimento']=$dados['atendimento']->whereRaw(" agendamento_itens.cd_exame = '".$request['exame']."'");
                }
                if($request['paciente']){
                    $parametros = $parametros . ' | [ Paciente ]  ' . $request['paciente'];
                    $dados['atendimento']=$dados['atendimento']->whereRaw(" upper(paciente.nm_paciente) like '".mb_strtoupper($request['paciente'])."%'");
                }
                if($request['convenio']){
                    $parametros = $parametros . ' | [ Convênio ]  ' . $request['convenio'].' - '. Convenio::where('cd_convenio',$request['convenio'])->select('nm_convenio')->first()->nm_convenio;
                    $dados['atendimento']=$dados['atendimento']->whereRaw(" agendamento.cd_convenio = '".$request['convenio']."'");
                }
                if($request['situacao']){
                    $parametros = $parametros . ' | [ Situacao ]  ' . $request['situacao'];
                    $dados['atendimento']=$dados['atendimento']->whereRaw(" agendamento_itens.situacao = '".$request['situacao']."'");
                }
                $dados['atendimento']=$dados['atendimento']->orderBy('dt_atendimento')
                ->with('exame') 
                ->selectRaw("cd_exame, sum(case when sn_laudo='1' then 1 else 0 end) qtde_laudo, sum(case when sn_laudo='1' then 0 else 1 end) qtde_pendente")
                ->groupByRaw('cd_exame')
                ->orderByRaw("2")
                ->get();  


                $dados['profissional']=AgendamentoItens::  
                join('agendamento','agendamento.cd_agendamento','agendamento_itens.cd_agendamento')   
                ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
                ->whereRaw("date(agendamento.dt_atendimento) >= '".$request['dti']."'")
                ->whereRaw("date(agendamento.dt_atendimento) <= '".$request['dtf']."'");
                if($request['profissional']){ 
                    $parametros = $parametros . ' [ Profissional ]  ' . $request['profissional'].' - '. Profissional::where('cd_profissional',$request['profissional'])->select('nm_profissional')->first()->nm_profissional;
                    $dados['profissional']=$dados['profissional']->whereRaw(" agendamento.cd_profissional = '".$request['profissional']."'");
                }
                if($request['exame']){
                    $parametros = $parametros . ' | [ Exame ]  ' . $request['exame'].' - '. Exame::where('cd_exame',$request['exame'])->select('nm_exame')->first()->nm_exame;
                    $dados['profissional']=$dados['profissional']->whereRaw(" agendamento_itens.cd_exame = '".$request['exame']."'");
                }
                if($request['paciente']){
                    $parametros = $parametros . ' | [ Paciente ]  ' . $request['paciente'];
                    $dados['profissional']=$dados['profissional']->whereRaw(" upper(paciente.nm_paciente) like '".mb_strtoupper($request['paciente'])."%'");
                }
                if($request['convenio']){
                    $parametros = $parametros . ' | [ Convênio ]  ' . $request['convenio'].' - '. Convenio::where('cd_convenio',$request['convenio'])->select('nm_convenio')->first()->nm_convenio;
                    $dados['profissional']=$dados['profissional']->whereRaw(" agendamento.cd_convenio = '".$request['convenio']."'");
                }
                if($request['situacao']){
                    $parametros = $parametros . ' | [ Situacao ]  ' . $request['situacao'];
                    $dados['profissional']=$dados['profissional']->whereRaw(" agendamento_itens.situacao = '".$request['situacao']."'");
                }
                $dados['profissional']=$dados['profissional']->orderBy('dt_atendimento') 
                ->selectRaw("nm_profissional, sum(case when sn_laudo='1' then 1 else 0 end) qtde_laudo, sum(case when sn_laudo='1' then 0 else 1 end) qtde_pendente")
                ->groupByRaw('nm_profissional')
                ->orderByRaw("2")
                ->get(); 
 
            }
 

        }

        if($request['tp_relatorio']=='PDF'){
            $layout = 'portrait'; 
            $pdf = Pdf::loadView('rpclinica.relatorios.rpsys.producao_pdf', compact('parametros', 'dados')); 
            return $pdf->setPaper('a4', $layout)->stream('relatorio_produção.pdf');
        }

        if($request['tp_relatorio']=='EXCEL'){
            return view('rpclinica.relatorios.rpsys.producao_excel', compact('dados','request'));
        } 

        $dados['request']=$_SERVER['REQUEST_URI'];
        return view('rpclinica.relatorios.rpsys.producao', compact('dados','request'));
         
    }

 

    
}
