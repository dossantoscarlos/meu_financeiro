'use client';

import { useState, useEffect } from 'react';
import { pagamentosService } from '../services/pagamentosService';
import { Plano, Caixinha, Gasto } from '@/shared/types';
import { CreditCard, Calendar, Plus, Trash2, Calculator, Layers, RefreshCw, DollarSign } from 'lucide-react';

export default function PagamentosModule() {
  const [planos, setPlanos] = useState<Plano[]>([]);
  const [caixinhas, setCaixinhas] = useState<Caixinha[]>([]);
  const [gastos, setGastos] = useState<Gasto[]>([]);
  const [loading, setLoading] = useState(true);

  // Modais e inputs
  const [showPlanoModal, setShowPlanoModal] = useState(false);
  const [mesAno, setMesAno] = useState('');
  const [descricaoPlano, setDescricaoPlano] = useState('');

  const [showCaixinhaModal, setShowCaixinhaModal] = useState(false);
  const [descricaoCaixinha, setDescricaoCaixinha] = useState('');
  const [valorProduto, setValorProduto] = useState('');
  const [parcelas, setParcelas] = useState('1');

  useEffect(() => {
    loadAllData();
  }, []);

  async function loadAllData() {
    try {
      setLoading(true);
      const [resPlanos, resCaixinhas, resGastos] = await Promise.all([
        pagamentosService.getPlanos(),
        pagamentosService.getCaixinhas(),
        pagamentosService.getGastos(),
      ]);
      setPlanos(resPlanos);
      setCaixinhas(resCaixinhas);
      setGastos(resGastos);
    } catch (err: any) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  async function handleCreatePlano(e: React.FormEvent) {
    e.preventDefault();
    try {
      await pagamentosService.createPlano({ mes_ano: mesAno, descricao_simples: descricaoPlano });
      setMesAno('');
      setDescricaoPlano('');
      setShowPlanoModal(false);
      loadAllData();
    } catch (err: any) {
      alert(err.message || 'Erro ao criar plano');
    }
  }

  async function handleDeletePlano(id: number) {
    if (!confirm('Deseja realmente remover este plano?')) return;
    try {
      await pagamentosService.deletePlano(id);
      loadAllData();
    } catch (err: any) {
      alert(err.message || 'Erro ao deletar plano');
    }
  }

  async function handleCreateCaixinha(e: React.FormEvent) {
    e.preventDefault();
    try {
      await pagamentosService.createCaixinha({
        descricao: descricaoCaixinha,
        valor_produto: parseFloat(valorProduto),
        parcelas: parseInt(parcelas, 10),
      });
      setDescricaoCaixinha('');
      setValorProduto('');
      setParcelas('1');
      setShowCaixinhaModal(false);
      loadAllData();
    } catch (err: any) {
      alert(err.message || 'Erro ao criar caixinha');
    }
  }

  async function handleDeleteCaixinha(id: number) {
    if (!confirm('Deseja remover esta caixinha?')) return;
    try {
      await pagamentosService.deleteCaixinha(id);
      loadAllData();
    } catch (err: any) {
      alert(err.message || 'Erro ao deletar caixinha');
    }
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center p-12 text-slate-400">
        <RefreshCw className="w-6 h-6 animate-spin mr-2" />
        Carregando dados de pagamentos e planos...
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Header Corporativo */}
      <div className="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-700/50 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <CreditCard className="w-7 h-7 text-indigo-400" />
            Módulo de Pagamentos, Planos & Caixinhas
          </h1>
          <p className="text-sm text-slate-400 mt-1">
            Planejamento mensal de obrigações financeiras, parcelamentos de caixinhas e acompanhamento de gastos.
          </p>
        </div>
        <div className="flex items-center gap-3">
          <button
            onClick={() => setShowPlanoModal(true)}
            className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-lg shadow-indigo-600/30 flex items-center gap-1.5 transition-all"
          >
            <Plus className="w-4 h-4" /> Novo Plano Mensal
          </button>
          <button
            onClick={() => setShowCaixinhaModal(true)}
            className="bg-slate-700 hover:bg-slate-600 text-slate-100 text-xs font-semibold px-4 py-2 rounded-lg border border-slate-600 flex items-center gap-1.5 transition-all"
          >
            <Calculator className="w-4 h-4 text-emerald-400" /> Nova Caixinha
          </button>
        </div>
      </div>

      {/* Seção 1: Planos Mensais */}
      <div>
        <h2 className="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
          <Calendar className="w-5 h-5 text-indigo-400" />
          Planos Mensais Vigentes ({planos.length})
        </h2>

        {planos.length === 0 ? (
          <div className="bg-slate-800/40 border border-slate-700/60 rounded-xl p-8 text-center text-slate-400">
            Nenhum plano mensal cadastrado. Clique em "Novo Plano Mensal" para iniciar.
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            {planos.map((plano) => (
              <div
                key={plano.id}
                className="bg-slate-800/90 border border-slate-700 rounded-xl p-5 shadow-lg flex flex-col justify-between hover:border-slate-600 transition-colors"
              >
                <div>
                  <div className="flex justify-between items-start mb-3">
                    <span className="text-xs font-bold uppercase tracking-wider bg-indigo-950/80 border border-indigo-700/60 text-indigo-300 px-2.5 py-1 rounded-md">
                      {plano.mes_ano}
                    </span>
                    <button
                      onClick={() => handleDeletePlano(plano.id)}
                      className="text-slate-400 hover:text-rose-400 transition-colors"
                      title="Deletar Plano"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                  <h3 className="text-base font-bold text-slate-100">
                    {plano.descricao_simples || `Plano ${plano.mes_ano}`}
                  </h3>
                  <p className="text-xs text-slate-400 mt-2 flex items-center gap-2">
                    <Layers className="w-4 h-4 text-slate-500" />
                    Despesas associadas: <strong className="text-slate-200">{plano.despesas_count ?? 0}</strong>
                  </p>
                </div>

                <div className="mt-4 pt-3 border-t border-slate-700/60 flex justify-between items-center text-xs text-slate-400">
                  <span>ID: #{plano.id}</span>
                  <span className="text-emerald-400 font-semibold">Ativo</span>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Seção 2: Caixinhas (Parcelamentos) */}
      <div>
        <h2 className="text-lg font-semibold text-slate-200 mb-4 flex items-center gap-2">
          <Calculator className="w-5 h-5 text-emerald-400" />
          Caixinhas e Parcelamentos Ativos ({caixinhas.length})
        </h2>

        {caixinhas.length === 0 ? (
          <div className="bg-slate-800/40 border border-slate-700/60 rounded-xl p-8 text-center text-slate-400">
            Nenhuma caixinha cadastrada.
          </div>
        ) : (
          <div className="bg-slate-800/80 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm text-slate-300">
                <thead className="bg-slate-900/80 text-xs font-semibold uppercase text-slate-400 border-b border-slate-700">
                  <tr>
                    <th className="px-5 py-3.5">Descrição</th>
                    <th className="px-5 py-3.5">Valor Total (R$)</th>
                    <th className="px-5 py-3.5">Parcelas</th>
                    <th className="px-5 py-3.5">Valor da Parcela (R$)</th>
                    <th className="px-5 py-3.5 text-right">Ações</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-700/60">
                  {caixinhas.map((caixinha) => (
                    <tr key={caixinha.id} className="hover:bg-slate-700/30 transition-colors">
                      <td className="px-5 py-4 font-medium text-slate-100">{caixinha.descricao}</td>
                      <td className="px-5 py-4 font-semibold text-slate-200">
                        R$ {Number(caixinha.valor_produto).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                      </td>
                      <td className="px-5 py-4 text-slate-300">
                        <span className="bg-slate-900 px-2 py-1 rounded text-xs border border-slate-700">
                          {caixinha.parcelas}x
                        </span>
                      </td>
                      <td className="px-5 py-4 font-bold text-emerald-400">
                        R$ {typeof caixinha.valor_parcela === 'number' ? caixinha.valor_parcela.toFixed(2) : caixinha.valor_parcela}
                      </td>
                      <td className="px-5 py-4 text-right">
                        <button
                          onClick={() => handleDeleteCaixinha(caixinha.id)}
                          className="text-slate-400 hover:text-rose-400 transition-colors"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>

      {/* Modal Criar Plano */}
      {showPlanoModal && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
          <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-lg font-bold text-slate-100 mb-4">Novo Plano Mensal</h3>
            <form onSubmit={handleCreatePlano} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Mês / Ano (Ex: Jan/2026)</label>
                <input
                  type="text"
                  required
                  value={mesAno}
                  onChange={(e) => setMesAno(e.target.value)}
                  placeholder="01/2026"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Descrição Simples</label>
                <input
                  type="text"
                  value={descricaoPlano}
                  onChange={(e) => setDescricaoPlano(e.target.value)}
                  placeholder="Orçamento Família"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button
                  type="button"
                  onClick={() => setShowPlanoModal(false)}
                  className="bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs px-4 py-2 rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg"
                >
                  Salvar Plano
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal Criar Caixinha */}
      {showCaixinhaModal && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
          <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-lg font-bold text-slate-100 mb-4">Nova Caixinha / Parcelamento</h3>
            <form onSubmit={handleCreateCaixinha} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Descrição</label>
                <input
                  type="text"
                  required
                  value={descricaoCaixinha}
                  onChange={(e) => setDescricaoCaixinha(e.target.value)}
                  placeholder="Notebook Corporativo"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Valor do Produto (R$)</label>
                <input
                  type="number"
                  step="0.01"
                  required
                  value={valorProduto}
                  onChange={(e) => setValorProduto(e.target.value)}
                  placeholder="3500.00"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Quantidade de Parcelas</label>
                <input
                  type="number"
                  min="1"
                  required
                  value={parcelas}
                  onChange={(e) => setParcelas(e.target.value)}
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button
                  type="button"
                  onClick={() => setShowCaixinhaModal(false)}
                  className="bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs px-4 py-2 rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg"
                >
                  Salvar Caixinha
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
