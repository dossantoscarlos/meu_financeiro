<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Caixinha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CaixinhaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Caixinha::class);

        $caixinhas = $request->user()->caixinhas()
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($caixinhas);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Caixinha::class);

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_produto' => 'required|numeric|min:0',
            'parcelas' => 'required|integer|min:1',
        ]);

        $caixinha = $request->user()->caixinhas()->create($validated);

        return response()->json($caixinha, 201);
    }

    public function show(Caixinha $caixinha): JsonResponse
    {
        Gate::authorize('view', $caixinha);

        return response()->json($caixinha);
    }

    public function update(Request $request, Caixinha $caixinha): JsonResponse
    {
        Gate::authorize('update', $caixinha);

        $validated = $request->validate([
            'descricao' => 'sometimes|required|string|max:255',
            'valor_produto' => 'sometimes|required|numeric|min:0',
            'parcelas' => 'sometimes|required|integer|min:1',
        ]);

        $caixinha->update($validated);

        return response()->json($caixinha);
    }

    public function destroy(Caixinha $caixinha): JsonResponse
    {
        Gate::authorize('delete', $caixinha);

        $caixinha->deleteOrFail();

        return response()->json(['message' => 'Caixinha removida com sucesso']);
    }
}
