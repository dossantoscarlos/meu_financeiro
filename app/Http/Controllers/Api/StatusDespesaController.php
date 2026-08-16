<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StatusDespesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusDespesaController extends Controller
{
    public function index(): JsonResponse
    {
        $status = StatusDespesa::all();

        return response()->json($status);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:status_despesas,nome',
        ]);

        $status = StatusDespesa::create($validated);

        return response()->json($status, 201);
    }
}
