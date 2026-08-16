<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoricoDespesa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoricoDespesaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $historico = HistoricoDespesa::with(['despesa', 'statusDespesa'])
            ->whereHas('despesa.plano', fn ($q) => $q->where('user_id', $userId))
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($historico);
    }
}
