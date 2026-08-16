<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $planos = Plano::query()
            ->where('user_id', '=', $request->user()->id)
            ->withCount('despesas')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($planos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'descricao_simples' => 'nullable|string|max:255',
            'mes_ano' => 'required|string|max:50',
        ]);

        $validated['user_id'] = $request->user()->id;

        $plano = Plano::create($validated);

        return response()->json($plano, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $plano = Plano::query()
            ->where('user_id', '=', $request->user()->id)
            ->with(['despesas.statusDespesa', 'despesas.tipoDespesa', 'gastos'])
            ->findOrFail($id);

        return response()->json($plano);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $plano = Plano::query()->where('user_id', '=', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'descricao_simples' => 'nullable|string|max:255',
            'mes_ano' => 'required|string|max:50',
        ]);

        $plano->update($validated);

        return response()->json($plano);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $plano = Plano::query()
            ->where(fn($q) => $q->where('user_id', '=', $request->user()->id)->orWhereNull('user_id'))
            ->findOrFail($id);

        $plano->deleteOrFail();

        return response()->json(['message' => 'Plano removido com sucesso']);
    }
}
