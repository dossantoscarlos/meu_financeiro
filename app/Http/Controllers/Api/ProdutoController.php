<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $produtos = Produto::query()
            ->where('user_id', '=', $request->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($produtos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'descricao_curta' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|numeric|min:0',
            'tipo_medida' => 'required|string|max:50',
            'data_compra' => 'required|date',
        ]);

        $validated['user_id'] = $request->user()->id;

        $produto = Produto::create($validated);

        return response()->json($produto, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $produto = Produto::query()
            ->where('user_id', '=', $request->user()->id)
            ->findOrFail($id);

        return response()->json($produto);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $produto = Produto::query()
            ->where('user_id', '=', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'descricao_curta' => 'sometimes|required|string|max:255',
            'preco' => 'sometimes|required|numeric|min:0',
            'quantidade' => 'sometimes|required|numeric|min:0',
            'tipo_medida' => 'sometimes|required|string|max:50',
            'data_compra' => 'sometimes|required|date',
        ]);

        $produto->update($validated);

        return response()->json($produto);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $produto = Produto::query()
            ->where(fn ($q) => $q->where('user_id', '=', $request->user()->id)->orWhereNull('user_id'))
            ->findOrFail($id);

        $produto->deleteOrFail();

        return response()->json(['message' => 'Produto removido com sucesso']);
    }
}
