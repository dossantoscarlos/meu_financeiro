'use client';

import { useState } from 'react';
import { apiFetch } from '@/shared/lib/api';
import {
  Terminal,
  Play,
  RotateCcw,
  AlertTriangle,
  CheckCircle2,
  AlertCircle,
  Clock,
  Database,
  Sparkles,
} from 'lucide-react';

interface SqlResponse {
  success: boolean;
  type?: 'select' | 'affecting';
  columns?: string[];
  results?: Record<string, any>[];
  total_returned?: number;
  affected_rows?: number | null;
  execution_time_ms?: number;
  error?: string;
}

export function SqlConsoleModule() {
  const [sql, setSql] = useState<string>('SELECT * FROM users LIMIT 10;');
  const [loading, setLoading] = useState<boolean>(false);
  const [response, setResponse] = useState<SqlResponse | null>(null);

  const presets = [
    { label: '📋 Users (10)', query: 'SELECT * FROM users LIMIT 10;' },
    { label: '💸 Despesas (10)', query: 'SELECT * FROM despesas LIMIT 10;' },
    { label: '📊 Planos (10)', query: 'SELECT * FROM planos LIMIT 10;' },
    { label: '💰 Rendas (10)', query: 'SELECT * FROM rendas LIMIT 10;' },
    { label: '📦 Produtos (10)', query: 'SELECT * FROM produtos LIMIT 10;' },
  ];

  async function handleRunQuery(customSql?: string) {
    const queryToRun = customSql ?? sql;
    if (!queryToRun.trim()) return;

    setLoading(true);
    setResponse(null);

    try {
      const res = await apiFetch<SqlResponse>('/sql-console', {
        method: 'POST',
        body: JSON.stringify({ sql: queryToRun }),
      });
      setResponse(res);
    } catch (err: any) {
      setResponse({
        success: false,
        error: err.message || 'Erro ao executar a query SQL.',
      });
    } finally {
      setLoading(false);
    }
  }

  function handlePresetClick(presetQuery: string) {
    setSql(presetQuery);
    handleRunQuery(presetQuery);
  }

  function handleClear() {
    setSql('');
    setResponse(null);
  }

  return (
    <div className="space-y-6">
      {/* Header do Módulo */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div className="flex items-center gap-4">
          <div className="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
            <Terminal className="w-6 h-6" />
          </div>
          <div>
            <h1 className="text-xl font-bold text-slate-100 flex items-center gap-2">
              Console SQL Direto
              <span className="text-[10px] font-extrabold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/30 px-2 py-0.5 rounded-full">
                API REST • Next.js
              </span>
            </h1>
            <p className="text-xs text-slate-400 mt-1">
              Execute consultas e atualizações SQL diretamente no banco de dados da aplicação.
            </p>
          </div>
        </div>
      </div>

      {/* Quick Presets */}
      <div className="bg-slate-900/80 border border-slate-800 p-4 rounded-xl space-y-3">
        <div className="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400">
          <Sparkles className="w-4 h-4 text-amber-400" />
          Queries Rápidas (Presets)
        </div>
        <div className="flex flex-wrap gap-2">
          {presets.map((preset) => (
            <button
              key={preset.label}
              type="button"
              onClick={() => handlePresetClick(preset.query)}
              className="px-3 py-1.5 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-indigo-300 border border-slate-700 hover:border-indigo-500/50 rounded-lg transition-all flex items-center gap-1.5 shadow-sm"
            >
              {preset.label}
            </button>
          ))}
        </div>
      </div>

      {/* SQL Editor Form */}
      <div className="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
        <div>
          <label className="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 flex items-center gap-2">
            <Database className="w-4 h-4 text-indigo-400" />
            Comando SQL
          </label>
          <textarea
            value={sql}
            onChange={(e) => setSql(e.target.value)}
            rows={5}
            placeholder="Digite sua query SQL... Ex: SELECT * FROM despesas WHERE valor_documento > 100;"
            className="w-full font-mono text-sm p-4 bg-slate-950 text-indigo-300 rounded-xl border border-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 leading-relaxed shadow-inner"
          />
        </div>

        <div className="flex flex-wrap items-center justify-between gap-4 pt-2">
          <div className="flex items-center gap-3">
            <button
              type="button"
              onClick={() => handleRunQuery()}
              disabled={loading}
              className="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2"
            >
              {loading ? (
                <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
              ) : (
                <Play className="w-4 h-4 fill-current" />
              )}
              {loading ? 'Executando...' : 'Executar Query'}
            </button>

            <button
              type="button"
              onClick={handleClear}
              className="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition-all flex items-center gap-2"
            >
              <RotateCcw className="w-4 h-4" />
              Limpar
            </button>
          </div>

          <div className="flex items-center gap-2 text-xs text-amber-400/90 bg-amber-500/10 border border-amber-500/20 px-3.5 py-2 rounded-xl">
            <AlertTriangle className="w-4 h-4 shrink-0 text-amber-400" />
            <span>Atenção: Queries de alteração (`UPDATE`, `DELETE`) modificam os dados no banco.</span>
          </div>
        </div>
      </div>

      {/* Response Results */}
      {response && (
        <div className="space-y-4">
          {/* Error Card */}
          {!response.success && response.error && (
            <div className="bg-rose-950/40 border border-rose-900/60 p-5 rounded-2xl text-rose-200 space-y-3 shadow-xl">
              <div className="flex items-center justify-between font-bold text-sm text-rose-400">
                <div className="flex items-center gap-2">
                  <AlertCircle className="w-5 h-5 text-rose-500" />
                  Erro na Execução da Query
                </div>
                {response.execution_time_ms !== undefined && (
                  <span className="text-xs font-mono bg-rose-900/40 px-2.5 py-1 rounded-full border border-rose-800 text-rose-300 flex items-center gap-1">
                    <Clock className="w-3 h-3" /> {response.execution_time_ms} ms
                  </span>
                )}
              </div>
              <pre className="font-mono text-xs bg-slate-950/80 p-4 rounded-xl border border-rose-900/40 text-rose-300 whitespace-pre-wrap overflow-x-auto">
                {response.error}
              </pre>
            </div>
          )}

          {/* Affecting Statement Card */}
          {response.success && response.type === 'affecting' && (
            <div className="bg-emerald-950/30 border border-emerald-900/50 p-5 rounded-2xl text-emerald-200 flex items-center justify-between shadow-xl">
              <div className="flex items-center gap-3 font-bold text-sm text-emerald-400">
                <CheckCircle2 className="w-6 h-6 text-emerald-400" />
                Comando SQL executado com sucesso!
              </div>
              <div className="flex items-center gap-4 text-xs font-semibold">
                <span className="bg-emerald-900/40 border border-emerald-800 px-3 py-1.5 rounded-xl font-mono text-emerald-300">
                  Linhas Afetadas: <strong className="text-white text-sm">{response.affected_rows}</strong>
                </span>
                <span className="text-slate-400 flex items-center gap-1 font-mono">
                  <Clock className="w-3.5 h-3.5" /> {response.execution_time_ms} ms
                </span>
              </div>
            </div>
          )}

          {/* Select Query Results Table */}
          {response.success && response.type === 'select' && response.results && (
            <div className="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl space-y-4 p-5">
              <div className="flex items-center justify-between pb-3 border-b border-slate-800">
                <div className="flex items-center gap-2 text-sm font-bold text-slate-200">
                  <Database className="w-4 h-4 text-indigo-400" />
                  Resultados da Consulta
                </div>
                <div className="flex items-center gap-3 text-xs">
                  <span className="font-mono bg-indigo-500/10 text-indigo-300 border border-indigo-500/30 px-3 py-1 rounded-full font-semibold">
                    {response.results.length} de {response.total_returned ?? response.results.length} registros
                  </span>
                  <span className="font-mono text-slate-400 flex items-center gap-1">
                    <Clock className="w-3.5 h-3.5" /> {response.execution_time_ms} ms
                  </span>
                </div>
              </div>

              {response.results.length === 0 ? (
                <div className="py-12 text-center text-xs text-slate-500 font-mono">
                  A consulta foi executada com sucesso, mas nenhum registro foi retornado.
                </div>
              ) : (
                <div className="overflow-x-auto max-h-[500px] border border-slate-800 rounded-xl">
                  <table className="w-full text-left text-xs font-mono border-collapse">
                    <thead className="sticky top-0 bg-slate-950 text-slate-300 border-b border-slate-800 z-10">
                      <tr>
                        {response.columns?.map((col) => (
                          <th
                            key={col}
                            className="px-4 py-3 font-semibold uppercase tracking-wider whitespace-nowrap border-r border-slate-800 last:border-0"
                          >
                            {col}
                          </th>
                        ))}
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-800 text-slate-200 bg-slate-900/60">
                      {response.results.map((row, idx) => (
                        <tr
                          key={idx}
                          className="hover:bg-slate-800/80 transition-colors"
                        >
                          {response.columns?.map((col) => {
                            const val = row[col];
                            const isNull = val === null || val === undefined;
                            const isComplex = typeof val === 'object' && val !== null;
                            const displayVal = isComplex ? JSON.stringify(val) : String(val);

                            return (
                              <td
                                key={col}
                                className="px-4 py-2.5 border-r border-slate-800/60 last:border-0 max-w-xs truncate"
                                title={displayVal}
                              >
                                {isNull ? (
                                  <span className="text-slate-600 italic">NULL</span>
                                ) : isComplex ? (
                                  <span className="text-purple-400">{displayVal}</span>
                                ) : (
                                  <span>{displayVal}</span>
                                )}
                              </td>
                            );
                          })}
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          )}
        </div>
      )}
    </div>
  );
}
