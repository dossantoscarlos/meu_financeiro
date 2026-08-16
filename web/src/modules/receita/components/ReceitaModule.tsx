'use client';

import { useState, useEffect } from 'react';
import { receitaService } from '../services/receitaService';
import { Renda, RendaSummary } from '@/shared/types';
import { TrendingUp, DollarSign, Wallet, ArrowDownRight, ArrowUpRight, Plus, Trash2, RefreshCw } from 'lucide-react';

export default function ReceitaModule() {
  const [rendas, setRendas] = useState<Renda[]>([]);
  const [summary, setSummary] = useState<RendaSummary | null>(null);
  const [loading, setLoading] = useState(true);

  const [showModal, setShowModal] = useState(false);
  const [saldoInput, setSaldoInput] = useState('');
  const [custoInput, setCustoInput] = useState('');

  useEffect(() => {
    loadData();
  }, []);

  async function loadData() {
    try {
      setLoading(true);
      const [resRendas, resSummary] = await Promise.all([
        receitaService.getRendas(),
        receitaService.getSummary(),
      ]);
      setRendas(resRendas);
      setSummary(resSummary);
    } catch (err: any) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  async function handleCreate(e: React.FormEvent) {
    e.preventDefault();
    try {
      await receitaService.createRenda({
        saldo: parseFloat(saldoInput),
        custo: parseFloat(custoInput),
      });
      setSaldoInput('');
      setCustoInput('');
      setShowModal(false);
      loadData();
    } catch (err: any) {
      alert(err.message || 'Erro ao registrar receita');
    }
  }

  async function handleDelete(id: number) {
    if (!confirm('Deseja remover este registro de receita?')) return;
    try {
      await receitaService.deleteRenda(id);
      loadData();
    } catch (err: any) {
      alert(err.message || 'Erro ao deletar receita');
    }
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center p-12 text-slate-400">
        <RefreshCw className="w-6 h-6 animate-spin mr-2" />
        Carregando balanço financeiro e receitas...
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Header Corporativo */}
      <div className="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-700/50 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <TrendingUp className="w-7 h-7 text-emerald-400" />
            Módulo de Receita & Balanço Financeiro
          </h1>
          <p className="text-sm text-slate-400 mt-1">
            Gestão de entradas, custos operacionais e consolidação do saldo líquido em tempo real.
          </p>
        </div>
        <button
          onClick={() => setShowModal(true)}
          className="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-emerald-600/30 flex items-center gap-2 transition-all self-start md:self-auto"
        >
          <Plus className="w-4 h-4" /> Nova Receita / Entrada
        </button>
      </div>

      {/* Corporate KPI Summary Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {/* Receita Total */}
        <div className="bg-slate-800/80 border border-slate-700 rounded-xl p-6 shadow-xl relative overflow-hidden">
          <div className="flex justify-between items-start mb-4">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Receita Bruta Total</span>
              <h2 className="text-2xl font-black text-slate-100 mt-1">
                R$ {(summary?.total_receita ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
              </h2>
            </div>
            <div className="w-10 h-10 bg-emerald-950/80 border border-emerald-500/40 rounded-lg flex items-center justify-center text-emerald-400">
              <ArrowUpRight className="w-6 h-6" />
            </div>
          </div>
          <div className="text-xs text-slate-400 flex items-center gap-1.5 pt-2 border-t border-slate-700/50">
            <span className="text-emerald-400 font-semibold">+ Entrada ativa</span> registrada no sistema
          </div>
        </div>

        {/* Custo Total */}
        <div className="bg-slate-800/80 border border-slate-700 rounded-xl p-6 shadow-xl relative overflow-hidden">
          <div className="flex justify-between items-start mb-4">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Custos Operacionais</span>
              <h2 className="text-2xl font-black text-rose-400 mt-1">
                R$ {(summary?.total_custo ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
              </h2>
            </div>
            <div className="w-10 h-10 bg-rose-950/80 border border-rose-500/40 rounded-lg flex items-center justify-center text-rose-400">
              <ArrowDownRight className="w-6 h-6" />
            </div>
          </div>
          <div className="text-xs text-slate-400 flex items-center gap-1.5 pt-2 border-t border-slate-700/50">
            <span className="text-rose-400 font-semibold">Dedução direta</span> de receitas
          </div>
        </div>

        {/* Saldo Líquido */}
        <div className="bg-slate-800/80 border border-slate-700 rounded-xl p-6 shadow-xl relative overflow-hidden">
          <div className="flex justify-between items-start mb-4">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Saldo Líquido Consolidado</span>
              <h2 className={`text-2xl font-black mt-1 ${(summary?.saldo_liquido ?? 0) >= 0 ? 'text-indigo-400' : 'text-rose-500'}`}>
                R$ {(summary?.saldo_liquido ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
              </h2>
            </div>
            <div className="w-10 h-10 bg-indigo-950/80 border border-indigo-500/40 rounded-lg flex items-center justify-center text-indigo-400">
              <Wallet className="w-6 h-6" />
            </div>
          </div>
          <div className="text-xs text-slate-400 flex items-center gap-1.5 pt-2 border-t border-slate-700/50">
            <span className="text-indigo-400 font-semibold">Balanço líquido</span> disponível
          </div>
        </div>
      </div>

      {/* Tabela de Lançamentos de Receita */}
      <div className="bg-slate-800/80 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
        <div className="px-6 py-4 border-b border-slate-700 flex justify-between items-center bg-slate-900/60">
          <h3 className="text-base font-bold text-slate-100 flex items-center gap-2">
            <DollarSign className="w-5 h-5 text-emerald-400" />
            Histórico de Lançamentos de Renda ({rendas.length})
          </h3>
        </div>

        {rendas.length === 0 ? (
          <div className="p-8 text-center text-slate-400 text-sm">
            Nenhuma receita cadastrada.
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="bg-slate-900/80 text-xs font-semibold uppercase text-slate-400 border-b border-slate-700">
                <tr>
                  <th className="px-6 py-3.5"># ID</th>
                  <th className="px-6 py-3.5">Saldo / Receita (R$)</th>
                  <th className="px-6 py-3.5">Custo (R$)</th>
                  <th className="px-6 py-3.5">Resultado Líquido (R$)</th>
                  <th className="px-6 py-3.5 text-right">Ações</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-700/60">
                {rendas.map((item) => {
                  const liquido = Number(item.saldo) - Number(item.custo);
                  return (
                    <tr key={item.id} className="hover:bg-slate-700/30 transition-colors">
                      <td className="px-6 py-4 font-mono text-slate-400 text-xs">#{item.id}</td>
                      <td className="px-6 py-4 font-bold text-emerald-400">
                        R$ {Number(item.saldo).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                      </td>
                      <td className="px-6 py-4 font-semibold text-rose-400">
                        R$ {Number(item.custo).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                      </td>
                      <td className={`px-6 py-4 font-bold ${liquido >= 0 ? 'text-indigo-300' : 'text-rose-400'}`}>
                        R$ {liquido.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                      </td>
                      <td className="px-6 py-4 text-right">
                        <button
                          onClick={() => handleDelete(item.id)}
                          className="text-slate-400 hover:text-rose-400 transition-colors"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Modal Nova Receita */}
      {showModal && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
          <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-lg font-bold text-slate-100 mb-4">Lançar Nova Receita</h3>
            <form onSubmit={handleCreate} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Valor da Receita / Saldo (R$)</label>
                <input
                  type="number"
                  step="0.01"
                  required
                  value={saldoInput}
                  onChange={(e) => setSaldoInput(e.target.value)}
                  placeholder="5000.00"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-emerald-500"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Custo Operacional Associado (R$)</label>
                <input
                  type="number"
                  step="0.01"
                  required
                  value={custoInput}
                  onChange={(e) => setCustoInput(e.target.value)}
                  placeholder="1200.00"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-emerald-500"
                />
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button
                  type="button"
                  onClick={() => setShowModal(false)}
                  className="bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs px-4 py-2 rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-4 py-2 rounded-lg"
                >
                  Salvar Receita
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
