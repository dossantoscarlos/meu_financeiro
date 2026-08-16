<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Despesa;
use App\Models\HistoricoDespesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DespesaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $query = Despesa::with(['statusDespesa', 'tipoDespesa', 'plano'])
            ->whereHas('plano', fn ($q) => $q->where('user_id', $userId));

        if ($request->has('status_id')) {
            $query->where('status_despesa_id', $request->query('status_id'));
        }

        if ($request->has('tipo_id')) {
            $query->where('tipo_despesa_id', $request->query('tipo_id'));
        }

        if ($request->has('plano_id')) {
            $query->where('plano_id', $request->query('plano_id'));
        }

        $despesas = $query->orderBy('data_vencimento', 'desc')->get();

        return response()->json($despesas);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_documento' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'plano_id' => 'required|exists:planos,id',
            'status_despesa_id' => 'required|exists:status_despesas,id',
            'tipo_despesa_id' => 'required|exists:tipo_despesas,id',
        ]);

        $despesa = Despesa::create($validated);

        // Registrar histórico
        HistoricoDespesa::create([
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $despesa->status_despesa_id,
            'data' => now(),
        ]);

        return response()->json($despesa->load(['statusDespesa', 'tipoDespesa', 'plano']), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $despesa = Despesa::with(['statusDespesa', 'tipoDespesa', 'plano'])
            ->whereHas('plano', fn ($q) => $q->where('user_id', $userId))
            ->findOrFail($id);

        return response()->json($despesa);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $despesa = Despesa::whereHas('plano', fn ($q) => $q->where('user_id', $userId))
            ->findOrFail($id);

        $validated = $request->validate([
            'descricao' => 'sometimes|required|string|max:255',
            'valor_documento' => 'sometimes|required|numeric|min:0',
            'data_vencimento' => 'sometimes|required|date',
            'plano_id' => 'sometimes|required|exists:planos,id',
            'status_despesa_id' => 'sometimes|required|exists:status_despesas,id',
            'tipo_despesa_id' => 'sometimes|required|exists:tipo_despesas,id',
        ]);

        $oldStatus = $despesa->status_despesa_id;

        $despesa->update($validated);

        if (isset($validated['status_despesa_id']) && $validated['status_despesa_id'] !== $oldStatus) {
            HistoricoDespesa::create([
                'despesa_id' => $despesa->id,
                'status_despesa_id' => $despesa->status_despesa_id,
                'data' => now(),
            ]);
        }

        return response()->json($despesa->load(['statusDespesa', 'tipoDespesa', 'plano']));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $despesa = Despesa::whereHas('plano', fn ($q) => $q->where('user_id', $userId)->orWhereNull('user_id'))
            ->orWhereDoesntHave('plano')
            ->findOrFail($id);

        $despesa->delete();

        return response()->json(['message' => 'Despesa removida com sucesso']);
    }
}
