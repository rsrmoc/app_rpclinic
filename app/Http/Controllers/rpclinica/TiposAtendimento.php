<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Setores as RpclinicaSetores;
use App\Model\rpclinica\AgendaIntervalo;
use App\Model\rpclinica\TipoAtendimento;
use App\Model\rpclinica\TipoAtendimentoProc;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class TiposAtendimento extends Controller
{

    public function index(Request $request)
    {

        if ($request->query('b')) {
            $setores = TipoAtendimento::where('cd_tipo_atendimento', $request->b)
            ->leftJoin('agenda_intervalo','agenda_intervalo.cd_intervalo','tipo_atendimento.tempo')
                ->orWhere('nm_tipo_atendimento', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $setores = TipoAtendimento::leftJoin('agenda_intervalo','agenda_intervalo.cd_intervalo','tipo_atendimento.tempo')
            ->orderBy("nm_tipo_atendimento")->get();
        }

        return view('rpclinica.tipo_atend.lista', \compact('setores'));
    }

    public function create(Request $request)
    {

        $intervalos = AgendaIntervalo::orderBy('mn_intervalo')->get();
        $procedimentos = Procedimento::whereRaw("sn_ativo='S'")->orderby("nm_proc")->get();
        return view('rpclinica.tipo_atend.add', compact('intervalos','procedimentos'));

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nm_tipo' => 'required|string',
            'intervalo' => 'nullable|integer|exists:agenda_intervalo,cd_intervalo',
            'qtde_sessao' => 'nullable|integer',
            'sn_sessao' => 'nullable|string',
            'conta' => 'nullable|string',
            'cirurgia' => 'nullable|string',
            'retorno' => 'nullable|string',
            'exame' => 'nullable|string',
            'consulta' => 'nullable|string',
            'telemedicina' => 'nullable|string',
            'cor' => 'required|string',


        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $cod=time();
            $Tipo=TipoAtendimento::create([
                'cd_tipo_atendimento' => $cod,
                'nm_tipo_atendimento' => $request->post('nm_tipo'),
                'tempo' => $request->post('intervalo'),
                'cd_empresa' => $request->post('empresa'),
                'sn_ativo' => 'S',
                'cor' => $request->post('cor'),
                'sn_sessao' => $request->post('sn_sessao'),
                'qtde_sessao' => $request->post('qtde_sessao'),
                'sn_cirurgia' => $request->post('cirurgia'),
                'sn_retorno' => $request->post('retorno'),
                'sn_exame' => $request->post('exame'),
                'sn_consulta' => $request->post('consulta'),
                'sn_conta' => $request->post('conta'),
                'sn_telemedicina' => $request->post('telemedicina'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            if ($request->has('procedimento')) {
                foreach($request['procedimento'] as $proc){
                    $procedimento = Procedimento::find($proc);

                    $array=array(
                        'cd_tipo_atendimento'=>$cod,
                        'cod_proc'=>$procedimento->cod_proc,
                        'cd_proc'=>$proc,
                    );
                    TipoAtendimentoProc::create($array);

                }
             }

            return redirect()->route('tipo.atend.listar')->with('success', 'Tipo de Atendimento cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o Tipo de Atendimento.'.$e->getMessage()]);
        }
    }

    public function edit(TipoAtendimento $tipo) {
        $empresas = Empresa::all();
        $intervalos = AgendaIntervalo::orderBy('mn_intervalo')->get();
        $procedimentos = Procedimento::whereRaw("sn_ativo='S'")->orderby("nm_proc")->get();
        $tipoProc = TipoAtendimentoProc::whereRaw("cd_tipo_atendimento='".$tipo->cd_tipo_atendimento."'")->get();
        $Procs=null;
        foreach($tipoProc as $proc){
            $Procs[]=$proc->cd_proc;
        }

        return view('rpclinica.tipo_atend.edit', compact('tipo', 'intervalos','Procs','procedimentos','intervalos'));
    }

    public function update(Request $request, TipoAtendimento $tipo) {

        $validator = Validator::make($request->all(), [
            'nm_tipo' => 'required|string',
            'intervalo' => 'nullable|integer|exists:agenda_intervalo,cd_intervalo',
            'qtde_sessao' => 'nullable|integer',
            'sn_sessao' => 'nullable|string',
            'conta' => 'nullable|string',
            'cirurgia' => 'nullable|string',
            'retorno' => 'nullable|string',
            'exame' => 'nullable|string',
            'consulta' => 'nullable|string',
            'telemedicina' => 'nullable|string',
            'cor' => 'required|string',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $cod=$tipo->cd_tipo_atendimento;
            $tipo->update([
                'nm_tipo_atendimento' => $request->post('nm_tipo'),
                'tempo' => $request->post('intervalo'),
                'cd_empresa' => $request->post('empresa'),
                'sn_ativo' => 'S',
                'cor' => $request->post('cor'),
                'sn_sessao' => $request->post('sn_sessao'),
                'qtde_sessao' => $request->post('qtde_sessao'),
                'sn_cirurgia' => $request->post('cirurgia'),
                'sn_retorno' => $request->post('retorno'),
                'sn_conta' => $request->post('conta'),
                'sn_exame' => $request->post('exame'),
                'sn_consulta' => $request->post('consulta'),
                'sn_telemedicina' => $request->post('telemedicina'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            if ($request->has('procedimento')) {
                foreach($request['procedimento'] as $proc){
                    $ArrayProc[]=$proc;
                    $procedimento = Procedimento::find($proc);

                    $array=array(
                        'cd_tipo_atendimento'=>$cod,
                        'cod_proc'=>$procedimento->cod_proc,
                        'cd_proc'=>$proc,
                    );
                    TipoAtendimentoProc::updateOrInsert($array,
                        ['cd_tipo_atendimento' => $cod, 'cd_proc' => $proc]
                    );

                }
                if(isset($ArrayProc)){
                    TipoAtendimentoProc::whereNotIn('cd_proc',$ArrayProc)->whereRaw("cd_tipo_atendimento='".$cod."'")->delete();
                }
            }

            return redirect()->route('tipo.atend.listar')->with('success', 'Tipo de Atendimento atualizado com sucesso!');

        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o Tipo de Atendimento.'.$e->getMessage()]);
        }

    }

    public function delete(TipoAtendimento $tipo)
    {
        try {
            $tipo->delete();
            TipoAtendimentoProc::where('cd_tipo_atendimento',$tipo->cd_tipo_atendimento)->delete();
        }
        catch(Exception $e) {
            dd($e);
            abort(500);
        }
    }
}
