<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Motivo as RpclinicaMotivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Motivo extends Controller
{
    public function index(Request $request) {
        if ($request->query('b')) {
            $motivos = RpclinicaMotivo::where('cd_motivo', $request->b)
                ->orWhere('motivo', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $motivos = RpclinicaMotivo::all();
        }

        return view('rpclinica.motivo.lista', compact('motivos'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'motivo' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $motivo = RpclinicaMotivo::create([
                'motivo' => $request->motivo,
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json([
                'message' => 'Motivo criado com sucesso!',
                'motivo' => $motivo
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Houve um erro tentar criar o motivo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'cd_motivo' => 'required|integer|exists:motivo,cd_motivo',
            'motivo' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $motivo = RpclinicaMotivo::find($request->cd_motivo);
            $motivo->update([
                'motivo' => $request->motivo,
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json([
                'message' => 'Motivo atualizado com sucesso!',
                'motivo' => $motivo
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Houve um erro tentar atualizar o motivo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(RpclinicaMotivo $motivo)
    {
        try {
            $motivo->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
