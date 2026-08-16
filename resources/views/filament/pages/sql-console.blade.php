<x-filament-panels::page>
    <style>
        .sql-console-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .sql-console-hero {
            background-color: #0f172a;
            border: 1px solid #1e293b;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .sql-console-hero {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .sql-console-hero-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .sql-console-hero-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, #6366f1, #9333ea);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-family: ui-monospace, monospace;
            font-weight: 700;
            font-size: 1.25rem;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            flex-shrink: 0;
        }
        .sql-console-hero-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #f8fafc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }
        .sql-console-hero-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 0.25rem;
            margin-bottom: 0;
        }
        .sql-console-badge {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: rgba(99, 102, 241, 0.15);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.3);
            padding: 2px 8px;
            border-radius: 9999px;
            display: inline-block;
        }
        .sql-console-card {
            background-color: #0f172a;
            border: 1px solid #1e293b;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .sql-console-presets-card {
            background-color: rgba(15, 23, 42, 0.8);
            border: 1px solid #1e293b;
            padding: 1rem;
            border-radius: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .sql-console-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }
        .sql-console-preset-btn {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            background-color: #1e293b;
            color: #a5b4fc;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        .sql-console-preset-btn:hover {
            background-color: #334155;
            border-color: rgba(99, 102, 241, 0.5);
            color: #ffffff;
        }
        .sql-console-textarea {
            width: 100%;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 14px;
            padding: 1rem;
            background-color: #020617;
            color: #a5b4fc;
            border-radius: 0.75rem;
            border: 1px solid #1e293b;
            outline: none;
            line-height: 1.6;
            resize: vertical;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .sql-console-textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3), inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .sql-console-btn-run {
            padding: 10px 20px;
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 600;
            font-size: 12px;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
            transition: all 0.2s ease;
        }
        .sql-console-btn-run:hover {
            background-color: #6366f1;
        }
        .sql-console-btn-clear {
            padding: 10px 16px;
            background-color: #1e293b;
            color: #cbd5e1;
            font-weight: 600;
            font-size: 12px;
            border-radius: 0.75rem;
            border: 1px solid #334155;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .sql-console-btn-clear:hover {
            background-color: #334155;
            color: #ffffff;
        }
        .sql-console-warning {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #fbbf24;
            background-color: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            padding: 8px 14px;
            border-radius: 0.75rem;
        }
        .sql-console-error-card {
            background-color: rgba(69, 10, 10, 0.4);
            border: 1px solid rgba(153, 27, 27, 0.6);
            padding: 1.25rem;
            border-radius: 1rem;
            color: #fecdd3;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .sql-console-affecting-card {
            background-color: rgba(6, 78, 59, 0.3);
            border: 1px solid rgba(6, 95, 70, 0.5);
            padding: 1.25rem;
            border-radius: 1rem;
            color: #a7f3d0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .sql-console-results-card {
            background-color: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 1rem;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .sql-console-table-container {
            overflow-x: auto;
            max-height: 500px;
            border: 1px solid #1e293b;
            border-radius: 0.75rem;
        }
        .sql-console-table {
            width: 100%;
            text-align: left;
            font-size: 12px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            border-collapse: collapse;
        }
        .sql-console-table th {
            position: sticky;
            top: 0;
            background-color: #020617;
            color: #cbd5e1;
            padding: 12px 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #1e293b;
            border-right: 1px solid #1e293b;
            z-index: 10;
        }
        .sql-console-table td {
            padding: 10px 16px;
            border-bottom: 1px solid rgba(30, 41, 59, 0.6);
            border-right: 1px solid rgba(30, 41, 59, 0.6);
            color: #e2e8f0;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sql-console-table tr:hover td {
            background-color: rgba(30, 41, 59, 0.8);
        }
    </style>

    <div class="sql-console-wrapper">
        <!-- Header Hero Card -->
        <div class="sql-console-hero">
            <div class="sql-console-hero-left">
                <div class="sql-console-hero-icon">
                    &gt;_
                </div>
                <div>
                    <h1 class="sql-console-hero-title">
                        Console SQL Direto
                        <span class="sql-console-badge">
                            API REST • FILAMENT
                        </span>
                    </h1>
                    <p class="sql-console-hero-subtitle">
                        Execute consultas e atualizações SQL diretamente no banco de dados da aplicação.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Presets -->
        <div class="sql-console-presets-card">
            <div class="sql-console-section-title">
                <svg style="width: 1rem; height: 1rem; color: #fbbf24; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                QUERIES RÁPIDAS (PRESETS)
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <button type="button"
                    wire:click="loadPreset('SELECT * FROM users LIMIT 10;')"
                    class="sql-console-preset-btn">
                    📋 Users (10)
                </button>
                <button type="button"
                    wire:click="loadPreset('SELECT * FROM despesas LIMIT 10;')"
                    class="sql-console-preset-btn">
                    💸 Despesas (10)
                </button>
                <button type="button"
                    wire:click="loadPreset('SELECT * FROM planos LIMIT 10;')"
                    class="sql-console-preset-btn">
                    📊 Planos (10)
                </button>
                <button type="button"
                    wire:click="loadPreset('SELECT * FROM rendas LIMIT 10;')"
                    class="sql-console-preset-btn">
                    💰 Rendas (10)
                </button>
                <button type="button"
                    wire:click="loadPreset('SELECT * FROM produtos LIMIT 10;')"
                    class="sql-console-preset-btn">
                    📦 Produtos (10)
                </button>
            </div>
        </div>

        <!-- SQL Editor Form -->
        <div class="sql-console-card">
            <div>
                <label for="sql" class="sql-console-section-title" style="color: #cbd5e1; margin-bottom: 0.5rem;">
                    <svg style="width: 1rem; height: 1rem; color: #818cf8; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    COMANDO SQL
                </label>
                <textarea id="sql"
                    wire:model="sql"
                    rows="5"
                    class="sql-console-textarea"
                    placeholder="Digite seu comando SQL aqui... Ex: SELECT * FROM despesas;"></textarea>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; padding-top: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <button type="button"
                        wire:click="runQuery"
                        wire:loading.attr="disabled"
                        class="sql-console-btn-run">
                        <svg style="width: 1rem; height: 1rem; fill: currentColor; flex-shrink: 0;" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <span>Executar Query</span>
                    </button>

                    <button type="button"
                        wire:click="resetResults"
                        class="sql-console-btn-clear">
                        <svg style="width: 1rem; height: 1rem; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Limpar</span>
                    </button>
                </div>

                <div class="sql-console-warning">
                    <svg style="width: 1rem; height: 1rem; color: #fbbf24; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Atenção: Queries de alteração (`UPDATE`, `DELETE`) modificam os dados no banco.</span>
                </div>
            </div>
        </div>

        <!-- Output / Error Display -->
        @if ($errorMessage)
        <div class="sql-console-error-card">
            <div style="display: flex; align-items: center; justify-content: space-between; font-weight: 700; font-size: 0.875rem; color: #f87171;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: #ef4444; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Erro na Execução da Query
                </div>
                @if ($executionTime !== null)
                <span style="font-size: 11px; font-family: monospace; background-color: rgba(153, 27, 27, 0.4); padding: 2px 10px; border-radius: 9999px; border: 1px solid #991b1b; color: #fca5a5;">
                    ⏱️ {{ $executionTime }} ms
                </span>
                @endif
            </div>
            <pre style="font-family: monospace; font-size: 12px; background-color: rgba(2, 6, 23, 0.8); padding: 1rem; border-radius: 0.75rem; border: 1px solid rgba(153, 27, 27, 0.4); color: #fca5a5; white-space: pre-wrap; overflow-x: auto; margin: 0;">{{ $errorMessage }}</pre>
        </div>
        @endif

        @if ($queryType === 'affecting')
        <div class="sql-console-affecting-card">
            <div style="display: flex; align-items: center; gap: 0.75rem; font-weight: 700; font-size: 0.875rem; color: #34d399;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #34d399; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Comando SQL executado com sucesso!
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; font-size: 12px; font-weight: 600;">
                <span style="background-color: rgba(6, 95, 70, 0.4); border: 1px solid #065f46; padding: 6px 12px; border-radius: 0.75rem; font-family: monospace; color: #6ee7b7;">
                    Linhas Afetadas: <strong style="color: #ffffff; font-size: 14px;">{{ $affectedRows }}</strong>
                </span>
                <span style="color: #94a3b8; font-family: monospace;">
                    ⏱️ {{ $executionTime }} ms
                </span>
            </div>
        </div>
        @endif

        @if ($queryType === 'select' && is_array($results))
        <div class="sql-console-results-card">
            <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 700; color: #e2e8f0;">
                    <svg style="width: 1rem; height: 1rem; color: #818cf8; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s-8-1.79-8-4" />
                    </svg>
                    Resultados da Consulta
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 12px;">
                    <span style="font-family: monospace; background-color: rgba(99, 102, 241, 0.1); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); padding: 4px 12px; border-radius: 9999px; font-weight: 600;">
                        {{ count($results) }} registro(s) retornado(s)
                    </span>
                    <span style="font-family: monospace; color: #94a3b8;">
                        ⏱️ {{ $executionTime }} ms
                    </span>
                </div>
            </div>

            @if (empty($results))
            <div style="padding: 3rem 0; text-align: center; font-size: 12px; color: #64748b; font-family: monospace;">
                A consulta foi executada com sucesso, mas nenhum registro foi retornado.
            </div>
            @else
            <div class="sql-console-table-container">
                <table class="sql-console-table">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                            <th>
                                {{ $column }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $row)
                        <tr>
                            @foreach ($columns as $column)
                            <td title="{{ is_array($row[$column] ?? null) || is_object($row[$column] ?? null) ? json_encode($row[$column]) : (string) ($row[$column] ?? '') }}">
                                @if (is_null($row[$column] ?? null))
                                <span style="color: #475569; font-style: italic;">NULL</span>
                                @elseif (is_array($row[$column]) || is_object($row[$column]))
                                <span style="color: #c084fc;">{{ json_encode($row[$column]) }}</span>
                                @else
                                <span>{{ (string) ($row[$column] ?? '') }}</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif
    </div>
</x-filament-panels::page>
