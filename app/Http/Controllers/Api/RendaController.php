<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Renda;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RendaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $rendas = Renda::query()
            ->where('user_id', '=', $request->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($rendas);
    }

    public function summary(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $totalReceita = Renda::query()->where('user_id', '=', $userId)->sum('saldo');
        $totalCusto = Renda::query()->where('user_id', '=', $userId)->sum('custo');
        $saldoLiquido = $totalReceita - $totalCusto;

        return response()->json([
            'total_receita' => (float) $totalReceita,
            'total_custo' => (float) $totalCusto,
            'saldo_liquido' => (float) $saldoLiquido,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'saldo' => 'required|numeric',
            'custo' => 'required|numeric',
        ]);

        $validated['user_id'] = $request->user()->id;

        $renda = Renda::create($validated);

        return response()->json($renda, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $renda = Renda::query()->where('user_id', '=', $request->user()->id)->findOrFail($id);

        return response()->json($renda);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $renda = Renda::query()->where('user_id', '=', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'saldo' => 'sometimes|required|numeric',
            'custo' => 'sometimes|required|numeric',
        ]);

        $renda->update($validated);

        return response()->json($renda);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $renda = Renda::query()
            ->where(fn ($q) => $q->where('user_id', '=', $request->user()->id)->orWhereNull('user_id'))
            ->findOrFail($id);

        $renda->deleteOrFail();

        return response()->json(['message' => 'Receita/Renda removida com sucesso']);
    }
}
