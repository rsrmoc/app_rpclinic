<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Marca;
use Exception;
use Illuminate\Support\Facades\Validator;

class Marcas extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $marcas = Marca::where('cd_marca', $request->b)
                ->orWhere('nm_marca', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $marcas = Marca::all();
        }

        return view('rpclinica.marcas.lista', \compact('marcas'));
    }

    public function create()
    {
        return view('rpclinica.marcas.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
          
            Marca::create([
                'nm_marca' => $request->post('descricao'),
                'sn_ativo' => $request->post('ativo'), 
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('marca.listar')->with('success', 'Marca cadastrada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a Marca.'. $e]);
        }
    }

    public function edit(Marca $marca) {
        return view('rpclinica.marcas.edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $marca->update([
                'nm_marca' => $request->post('descricao'),
                'sn_ativo' => $request->post('ativo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('marca.listar')->with('success', 'Marca atualizada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a forma de pagameto.']);
        }
    }

    public function delete(Marca $marca) {
        try {
            $marca->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }
}
