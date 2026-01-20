<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Formulario;
use App\Model\rpclinica\Paciente as RpclinicaPaciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Paciente extends Controller
{

    public function index(Request $request)
    {
        return view('app_rpclinic.paciente.inicial');
    }

    public function create(Request $request)
    {
        return view('app_rpclinic.paciente.create');
    }

    public function edit(Request $request, $idPaciente)
    {
        $paciente = RpclinicaPaciente::find($idPaciente);

        if (!$paciente) abort(404);

        return view('app_rpclinic.paciente.editar', compact('paciente'));
    }

    public function hist(Request $request, $idPaciente)
    {
        $paciente = RpclinicaPaciente::find($idPaciente);

        if (!$paciente) abort(404);

        $historicos = DB::select("select * from (select anamnese conteudo, 'Anamnese' as nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento.dt_anamnese,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento.dt_anamnese)) diferenca
            from agendamento
            left join usuarios on usuarios.cd_usuario=agendamento.usuario_anamnese
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where cd_paciente=".$paciente->cd_paciente." and ifnull(anamnese,'') <> ''
            and ifnull(agendamento.deleted_at,'')=''
            
            
            union all
            
            select agendamento_documentos.conteudo, nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento_documentos.created_at,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento_documentos.created_at)) diferenca
            from agendamento_documentos
            left join agendamento on agendamento_documentos.cd_agendamento=agendamento.cd_agendamento
            left join usuarios on usuarios.cd_usuario=agendamento_documentos.cd_usuario
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where ifnull(agendamento_documentos.cd_pac,agendamento.cd_paciente)=".$paciente->cd_paciente."
            
            ) xx
            order by dt_anamnese desc");

        return view('app_rpclinic.paciente.historico', compact('paciente', 'historicos'));
    }

    public function doc(Request $request, $idPaciente)
    {
        $paciente = RpclinicaPaciente::find($idPaciente);

        if (!$paciente) abort(404);

        $formularios = Formulario::where('cd_profissional', Auth::guard('rpclinica')->user()->cd_profissional)
            ->where('sn_ativo', 'S')
            ->get();
        $profissional = Profissional::find( (Auth::guard('rpclinica')->user()->cd_profissional) ? Auth::guard('rpclinica')->user()->cd_profissional : 0 );
        foreach($formularios as $val){
 
            $val->conteudo_atual=camposInteligentesPac($val->conteudo,$paciente,$profissional);
        }     
         

        return view('app_rpclinic.paciente.documento', compact('paciente', 'formularios'));
    }

    public function pacientesLista(Request $request) {
        $request->validate([
            'search' => 'required|string'
        ]);

        $pacientes = RpclinicaPaciente::where('nm_paciente', 'LIKE', "{$request->search}%")
            ->orderBy('created_at', 'DESC')->paginate(25)->appends(['search' => $request->search]);

        return response()->json($pacientes);
    }

    public function createPaciente(Request $request) {
        $validated = $request->validate([
            'nm_paciente' => 'required|string',
            'dt_nasc' => 'nullable|string|date_format:Y-m-d',
            'nm_mae' => 'nullable|string',
            'nm_pai' => 'nullable|string',
            'sexo' => 'nullable|string|in:H,M',
            'cpf' => 'nullable|string|unique:paciente,cpf',
            'rg' => 'nullable|string|unique:paciente,rg',
        ]);

        try {
            $validated['sn_ativo'] = 'S';
            $validated['cd_usuario'] = Auth::guard('rpclinica')->user()->cd_usuario;

            $paciente = RpclinicaPaciente::create($validated);

            return response()->json([
                'message' => 'Paciente cadastrado!',
                'paciente' => $paciente
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePaciente(Request $request, $idPaciente) {
        $paciente = RpclinicaPaciente::find($idPaciente);

        if (!$paciente) abort(404);

        $validated = $request->validate([
            'nm_paciente' => 'required|string',
            'dt_nasc' => 'nullable|string|date_format:Y-m-d',
            'nm_mae' => 'nullable|string',
            'nm_pai' => 'nullable|string',
            'sexo' => 'nullable|string|in:H,M',
            'cpf' => 'nullable|string',
            'rg' => 'nullable|string',
        ]);

        try {
            if (RpclinicaPaciente::where('cpf', $validated['cpf'])->where('cd_paciente', '<>', $paciente->cd_paciente)->first())
                throw new Exception('CPF já está cadastrado em outro paciente.');

            $paciente->update($validated);

            return response()->json(['message' => 'Paciente atualizado!']);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveDocPaciente(Request $request) {
        $validated = $request->validate([
            'cd_pac' => 'required|integer|exists:paciente,cd_paciente',
            'cd_formulario' => 'required|integer|exists:formulario,cd_formulario',
            'nm_formulario' => 'required|string',
            'conteudo' => 'required|string'
        ]);

        try {
            $validated['cd_usuario'] = Auth::guard('rpclinica')->user()->cd_usuario;
            $validated['cd_prof'] = Auth::guard('rpclinica')->user()->cd_profissional;

            $doc = AgendamentoDocumentos::create($validated);

            return response()->json(['message' => 'Documento cadastrado!', 'doc' => $doc]);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
