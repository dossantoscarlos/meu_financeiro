<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SqlConsoleController extends Controller
{
    public function execute(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sql' => ['required', 'string'],
        ]);

        $query = trim((string) $validated['sql']);

        if ($query === '') {
            return response()->json([
                'success' => false,
                'error' => 'Por favor, informe uma query SQL válida.',
            ], 422);
        }

        $startTime = microtime(true);

        try {
            $firstWord = strtoupper(strtok($query, " \n\r\t") ?: '');
            $isReadQuery = in_array($firstWord, ['SELECT', 'EXPLAIN', 'SHOW', 'PRAGMA', 'WITH'], true);

            if ($isReadQuery) {
                /** @var array<int, object|array<string, mixed>> $rawResults */
                $rawResults = DB::select($query);
                $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

                $formattedResults = [];
                foreach ($rawResults as $row) {
                    $formattedResults[] = (array) $row;
                }

                $columns = !empty($formattedResults) ? array_keys($formattedResults[0]) : [];
                $limitedResults = array_slice($formattedResults, 0, 500);

                return response()->json([
                    'success' => true,
                    'type' => 'select',
                    'columns' => $columns,
                    'results' => $limitedResults,
                    'total_returned' => count($formattedResults),
                    'affected_rows' => null,
                    'execution_time_ms' => $executionTimeMs,
                ]);
            }

            $affectedRows = DB::affectingStatement($query);
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            return response()->json([
                'success' => true,
                'type' => 'affecting',
                'columns' => [],
                'results' => [],
                'total_returned' => 0,
                'affected_rows' => $affectedRows,
                'execution_time_ms' => $executionTimeMs,
            ]);
        } catch (Throwable $e) {
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionTimeMs,
            ], 400);
        }
    }
}
