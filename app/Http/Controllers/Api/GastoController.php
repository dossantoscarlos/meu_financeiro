<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gasto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $gastos = Gasto::with('plano')
            ->whereHas('plano', fn ($q) => $q->where('user_id', $userId))
            ->get();

        return response()->json($gastos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plano_id' => 'required|exists:planos,id',
            'valor' => 'required|numeric|min:0',
        ]);

        $gasto = Gasto::create($validated);

        return response()->json($gasto, 201);
    }
}
