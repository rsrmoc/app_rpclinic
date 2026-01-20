<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\LocalAtendimento as RpclinicaLocalAtendimento;
use App\Model\rpclinica\Setores;
use Exception;
use Illuminate\Support\Facades\Validator;

class LocalAtendimento extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $locais = RpclinicaLocalAtendimento::where('cd_local', $request->b)
                ->orWhere('nm_local', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $locais = RpclinicaLocalAtendimento::all();
        }

        return view('rpclinica.local_de_atendimento.lista', compact('locais'));
    }

    public function create()
    {
        $setores = Setores::all();

        return view('rpclinica.local_de_atendimento.add', compact('setores'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'setor' => 'required|integer|exists:setor,cd_setor'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            RpclinicaLocalAtendimento::create([
                'nm_local' => $request->post('nome'),
                'cd_setor' => $request->post('setor'),
                'sn_ativo' => 'S',
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('local.atend.listar')->with(['success' => 'Local cadastrado com sucesso!']);
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o local.']);
        }
    }

    public function edit(RpclinicaLocalAtendimento $local) {
        $setores = Setores::all();

        return view('rpclinica.local_de_atendimento.edit', compact('local', 'setores'));
    }

    public function update(Request $request, RpclinicaLocalAtendimento $local) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'setor' => 'required|integer|exists:setor,cd_setor'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $local->update([
                'nm_local' => $request->post('nome'),
                'cd_setor' => $request->post('setor'),
                'up_cadastro' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->route('local.atend.listar')->with(['success' => 'Local atualizado com sucesso!']);
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o local.']);
        }
    }

    public function delete(RpclinicaLocalAtendimento $local)
    {
        try {
            $local->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
