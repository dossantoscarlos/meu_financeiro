<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoDespesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipoDespesaController extends Controller
{
    public function index(): JsonResponse
    {
        $tipos = TipoDespesa::all();

        return response()->json($tipos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tipo_despesas,nome',
        ]);

        $tipo = TipoDespesa::create($validated);

        return response()->json($tipo, 201);
    }
}
