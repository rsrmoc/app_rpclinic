<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\CBOs;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Especialidade;
use Exception;

class Especialidades extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $especialidades = Especialidade::where('cd_especialidade', $request->b)
                ->orWhere('nm_especialidade', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $especialidades = Especialidade::all();
        }

        return view('rpclinica.especialidade.lista', \compact('request', 'especialidades'));
    }

    public function create(Request $request)
    {
        $cbos = CBOs::all();

        return view('rpclinica.especialidade.add', compact('cbos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'ativo' => 'required|in:S,N',
            'cbo' => 'required|integer|exists:cbos,cd_cbos'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $especialidade = new Especialidade();

            $especialidade->create([
                'nm_especialidade' => $request->post('nome'),
                'sn_ativo' => $request->post('ativo'),
                'cd_cbos' => $request->post('cbo'),
                'cd_usuario' => $request->user()->cd_usuario,
                'dt_cadastro' => date('Y-m-d H:i:s')
            ]);

            return redirect()->route('especialidade.listar')->with('success', 'Especialidade cadastrada.');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar.']);
        }
    }

    public function edit(Especialidade $especialidade) {
        $cbos = CBOs::all();

        return view('rpclinica.especialidade.edit', compact('especialidade', 'cbos'));
    }

    public function update(Request $request, Especialidade $especialidade) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $especialidade->update([
                'nm_especialidade' => $request->post('nome'),
                'sn_ativo' => $request->post('ativo'),
                'cd_cbos' => $request->post('cbo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('especialidade.listar')->with('success', 'Especialidade atualizada.');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a especialidade.']);
        }
    }

    public function delete(Especialidade $especialidade)
    {
        try {
            $especialidade->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
