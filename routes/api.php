<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CaixinhaController;
use App\Http\Controllers\Api\DespesaController;
use App\Http\Controllers\Api\GastoController;
use App\Http\Controllers\Api\HistoricoDespesaController;
use App\Http\Controllers\Api\PlanoController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\RelatorioController;
use App\Http\Controllers\Api\RendaController;
use App\Http\Controllers\Api\SqlConsoleController;
use App\Http\Controllers\Api\StatusDespesaController;
use App\Http\Controllers\Api\TipoDespesaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Meu Financeiro (Client-Server Architecture)
|--------------------------------------------------------------------------
*/

// Rotas públicas de autenticação
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Rotas protegidas (Auth Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticação
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Ferramentas & Administração
    Route::post('/sql-console', [SqlConsoleController::class, 'execute']);

    // Módulo: Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/me', [UserController::class, 'update']);

    // Módulo: Pagamentos (Planos, Caixinhas & Gastos)
    Route::apiResource('planos', PlanoController::class);
    Route::apiResource('caixinhas', CaixinhaController::class);
    Route::apiResource('gastos', GastoController::class)->only(['index', 'store']);
    Route::get('status-despesas', [StatusDespesaController::class, 'index']);
    Route::post('status-despesas', [StatusDespesaController::class, 'store']);

    // Módulo: Receita (Rendas, Saldos & Resumo Financeiro)
    Route::get('rendas/summary', [RendaController::class, 'summary']);
    Route::apiResource('rendas', RendaController::class);

    // Módulo: Despesas (Despesas, Categorias & Histórico)
    Route::apiResource('despesas', DespesaController::class);
    Route::get('tipo-despesas', [TipoDespesaController::class, 'index']);
    Route::post('tipo-despesas', [TipoDespesaController::class, 'store']);
    Route::get('historico-despesas', [HistoricoDespesaController::class, 'index']);

    // Módulo: Produto
    Route::apiResource('produtos', ProdutoController::class);

    // Módulo: Relatórios
    Route::get('/relatorioDespesasCustos', [RelatorioController::class, 'relatorioDespesasCustoMensal']);
    Route::get('/relatorios/despesas-custo-mensal', [RelatorioController::class, 'relatorioDespesasCustoMensal']);
    Route::get('/relatorios/balanco-mensal', [RelatorioController::class, 'relatorioBalancoMensal']);
    Route::get('/relatorios/categorias', [RelatorioController::class, 'relatorioCategorias']);
    Route::get('/relatorios/auditoria-despesas', [RelatorioController::class, 'relatorioAuditoriaDespesas']);
    Route::get('/relatorios/produtos', [RelatorioController::class, 'relatorioProdutos']);
});
