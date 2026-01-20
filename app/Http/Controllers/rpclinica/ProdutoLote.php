<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\ProdutoLote as RpclinicaProdutoLote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProdutoLote extends Controller
{
    protected function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'cd_produto' => 'required|integer|exists:produto,cd_produto',
            'nm_lote' => 'required|string|max:100',
            'validade' => 'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $data = $validator->validated();
            $data['cd_usuario'] = $request->user()->cd_usuario;
            $lote = RpclinicaProdutoLote::create($data);

            return response()->json([
                'message' => 'Lode criado com sucesso!',
                'lote' => $lote
            ]);
        }
        catch (Throwable $th) {
            return response()->json([
                'message' => 'Houve um erro ao criar o lote.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
