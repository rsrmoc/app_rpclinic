<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\TipoAjuste;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class TiposAjuste extends Controller
{

    public function index(Request $request) {


        if ($request->has('b') && $request->query('b')) {
            $tipos = TipoAjuste::where('cd_tipo_ajuste', $request->b)
                ->orWhere('nm_tipo_ajuste', 'LIKE', "%$request->b%")
                ->get();
        } else {
            $tipos = TipoAjuste::all();
        }

        return view('rpclinica.tipo_de_ajuste.lista', compact('tipos'));
    }

    public function create(Request $request) {
        return view('rpclinica.tipo_de_ajuste.add');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'tipo' => 'required|in:+,-',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            TipoAjuste::create([
                'nm_tipo_ajuste' => $request->post('descricao'),
                'tp_ajuste' => $request->post('tipo'),
                'sn_ativo' => $request->post('ativo'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario,
            ]);

            return redirect()->route('tipoaj.ajuste.listar')->with('success', 'Tipo de ajuste cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o tipo de ajuste. '.$e->getMessage()]);
        }
    }

    public function edit(TipoAjuste $tipo) {
        return view('rpclinica.tipo_de_ajuste.edit', compact('tipo'));
    }

    public function update(Request $request, TipoAjuste $tipo){
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'tipo' => 'required|in:+,-',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $tipo->update([
                'nm_tipo_ajuste' => $request->post('descricao'),
                'tp_ajuste' => $request->post('tipo'),
                'sn_ativo' => $request->post('ativo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario,
            ]);

            return redirect()->route('tipoaj.ajuste.listar')->with('success', 'Tipo de ajuste atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o tipo de ajuste. '.$e->getMessage()]);
        }
    }

    public function delete(TipoAjuste $tipo){
        try {
            $tipo->delete();
        }
        catch(Exception $e) {
            abort(500);
        }
    }
}
