'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { receitaService } from '@/modules/receita/services/receitaService';
import { despesasService } from '@/modules/despesas/services/despesasService';
import { pagamentosService } from '@/modules/pagamentos/services/pagamentosService';
import { produtoService } from '@/modules/produto/services/produtoService';
import { RendaSummary, Despesa, Plano, Caixinha, Produto } from '@/shared/types';
import {
  TrendingUp,
  Receipt,
  CreditCard,
  Package,
  Users,
  ArrowUpRight,
  ArrowDownRight,
  Wallet,
  CheckCircle,
  Clock,
  AlertTriangle,
  ChevronRight,
  RefreshCw,
} from 'lucide-react';

export default function OverviewDashboard() {
  const [summary, setSummary] = useState<RendaSummary | null>(null);
  const [despesas, setDespesas] = useState<Despesa[]>([]);
  const [planos, setPlanos] = useState<Plano[]>([]);
  const [caixinhas, setCaixinhas] = useState<Caixinha[]>([]);
  const [produtos, setProdutos] = useState<Produto[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadDashboardData();
  }, []);

  async function loadDashboardData() {
    try {
      setLoading(true);
      const [resSummary, resDespesas, resPlanos, resCaixinhas, resProdutos] = await Promise.all([
        receitaService.getSummary().catch(() => ({ total_receita: 0, total_custo: 0, saldo_liquido: 0 })),
        despesasService.getDespesas().catch(() => []),
        pagamentosService.getPlanos().catch(() => []),
        pagamentosService.getCaixinhas().catch(() => []),
        produtoService.getProdutos().catch(() => []),
      ]);

      setSummary(resSummary);
      setDespesas(resDespesas);
      setPlanos(resPlanos);
      setCaixinhas(resCaixinhas);
      setProdutos(resProdutos);
    } catch (err: any) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  const totalDespesasValor = despesas.reduce((acc, curr) => acc + Number(curr.valor_documento), 0);
  const despesasPendentes = despesas.filter((d) => d.status_despesa?.nome?.toLowerCase().includes('pendente')).length;
  const despesasPago = despesas.filter((d) => d.status_despesa?.nome?.toLowerCase().includes('pago')).length;

  if (loading) {
    return (
      <div className="flex items-center justify-center p-12 text-slate-400">
        <RefreshCw className="w-6 h-6 animate-spin mr-2" />
        Carregando painel financeiro consolidado...
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Banner Boas Vindas Corporativo */}
      <div className="bg-gradient-to-r from-indigo-900/40 via-slate-900 to-slate-900 border border-indigo-500/30 rounded-2xl p-6 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <span className="text-xs font-bold uppercase tracking-wider text-indigo-400">Visão Geral Executiva</span>
          <h1 className="text-2xl font-black text-slate-100 mt-1">Painel Consolidado - Meu Financeiro</h1>
          <p className="text-sm text-slate-400 mt-1">
            Métricas de fluxo de caixa, projeções de planos, caixinhas e despesas integradas à API Laravel REST.
          </p>
        </div>
        <div className="flex items-center gap-3">
          <Link
            href="/receita"
            className="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-emerald-600/30 flex items-center gap-1.5 transition-all"
          >
            <TrendingUp className="w-4 h-4" /> Lançar Receita
          </Link>
          <Link
            href="/despesas"
            className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-indigo-600/30 flex items-center gap-1.5 transition-all"
          >
            <Receipt className="w-4 h-4" /> Nova Despesa
          </Link>
        </div>
      </div>

      {/* Corporate KPI Cards Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {/* Receita Total */}
        <div className="bg-slate-900/90 border border-slate-800 rounded-xl p-5 shadow-lg">
          <div className="flex justify-between items-start mb-3">
            <span className="text-xs font-bold uppercase text-slate-400">Receita Bruta</span>
            <div className="p-2 bg-emerald-950/80 border border-emerald-500/40 rounded-lg text-emerald-400">
              <ArrowUpRight className="w-5 h-5" />
            </div>
          </div>
          <h3 className="text-2xl font-black text-slate-100">
            R$ {(summary?.total_receita ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
          </h3>
          <p className="text-xs text-slate-400 mt-2 flex items-center gap-1">
            <span className="text-emerald-400 font-bold">Módulo Receita</span> • Entradas ativas
          </p>
        </div>

        {/* Total Despesas */}
        <div className="bg-slate-900/90 border border-slate-800 rounded-xl p-5 shadow-lg">
          <div className="flex justify-between items-start mb-3">
            <span className="text-xs font-bold uppercase text-slate-400">Despesas Registradas</span>
            <div className="p-2 bg-rose-950/80 border border-rose-500/40 rounded-lg text-rose-400">
              <ArrowDownRight className="w-5 h-5" />
            </div>
          </div>
          <h3 className="text-2xl font-black text-rose-400">
            R$ {totalDespesasValor.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
          </h3>
          <p className="text-xs text-slate-400 mt-2">
            <strong className="text-amber-400">{despesasPendentes} pendentes</strong> • {despesas.length} total
          </p>
        </div>

        {/* Saldo Líquido */}
        <div className="bg-slate-900/90 border border-slate-800 rounded-xl p-5 shadow-lg">
          <div className="flex justify-between items-start mb-3">
            <span className="text-xs font-bold uppercase text-slate-400">Saldo Consolidado</span>
            <div className="p-2 bg-indigo-950/80 border border-indigo-500/40 rounded-lg text-indigo-400">
              <Wallet className="w-5 h-5" />
            </div>
          </div>
          <h3 className={`text-2xl font-black ${(summary?.saldo_liquido ?? 0) >= 0 ? 'text-indigo-400' : 'text-rose-500'}`}>
            R$ {(summary?.saldo_liquido ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
          </h3>
          <p className="text-xs text-slate-400 mt-2">Balanço do usuário logado</p>
        </div>

        {/* Caixinhas & Produtos */}
        <div className="bg-slate-900/90 border border-slate-800 rounded-xl p-5 shadow-lg">
          <div className="flex justify-between items-start mb-3">
            <span className="text-xs font-bold uppercase text-slate-400">Recursos Ativos</span>
            <div className="p-2 bg-purple-950/80 border border-purple-500/40 rounded-lg text-purple-400">
              <Package className="w-5 h-5" />
            </div>
          </div>
          <h3 className="text-2xl font-black text-slate-100">{caixinhas.length + produtos.length}</h3>
          <p className="text-xs text-slate-400 mt-2">
            {caixinhas.length} caixinhas • {produtos.length} produtos
          </p>
        </div>
      </div>

      {/* Grid dos Módulos Principais */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Card Módulo Despesas Recentes */}
        <div className="bg-slate-900/90 border border-slate-800 rounded-xl p-6 shadow-xl flex flex-col justify-between">
          <div>
            <div className="flex justify-between items-center pb-4 border-b border-slate-800">
              <h2 className="text-base font-bold text-slate-100 flex items-center gap-2">
                <Receipt className="w-5 h-5 text-indigo-400" />
                Despesas Recentes
              </h2>
              <Link href="/despesas" className="text-xs text-indigo-400 hover:underline flex items-center gap-1">
                Ver Todas <ChevronRight className="w-3.5 h-3.5" />
              </Link>
            </div>

            <div className="divide-y divide-slate-800/60 mt-2">
              {despesas.slice(0, 4).map((d) => (
                <div key={d.id} className="py-3 flex justify-between items-center text-xs">
                  <div>
                    <p className="font-bold text-slate-200">{d.descricao}</p>
                    <p className="text-slate-400 text-[11px] mt-0.5">
                      Vencimento: {new Date(d.data_vencimento).toLocaleDateString('pt-BR')}
                    </p>
                  </div>
                  <div className="text-right">
                    <span className="font-bold text-slate-100 block">
                      R$ {Number(d.valor_documento).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </span>
                    <span className="text-[10px] uppercase font-bold text-indigo-400">{d.status_despesa?.nome}</span>
                  </div>
                </div>
              ))}
              {despesas.length === 0 && (
                <p className="text-xs text-slate-500 py-4 text-center">Nenhuma despesa cadastrada.</p>
              )}
            </div>
          </div>
        </div>

        {/* Card Módulo Pagamentos & Caixinhas */}
        <div className="bg-slate-900/90 border border-slate-800 rounded-xl p-6 shadow-xl flex flex-col justify-between">
          <div>
            <div className="flex justify-between items-center pb-4 border-b border-slate-800">
              <h2 className="text-base font-bold text-slate-100 flex items-center gap-2">
                <CreditCard className="w-5 h-5 text-purple-400" />
                Planos & Caixinhas
              </h2>
              <Link href="/pagamentos" className="text-xs text-purple-400 hover:underline flex items-center gap-1">
                Gerenciar <ChevronRight className="w-3.5 h-3.5" />
              </Link>
            </div>

            <div className="divide-y divide-slate-800/60 mt-2">
              {caixinhas.slice(0, 4).map((c) => (
                <div key={c.id} className="py-3 flex justify-between items-center text-xs">
                  <div>
                    <p className="font-bold text-slate-200">{c.descricao}</p>
                    <p className="text-slate-400 text-[11px] mt-0.5">Parcelas: {c.parcelas}x</p>
                  </div>
                  <div className="text-right">
                    <span className="font-bold text-emerald-400 block">
                      R$ {typeof c.valor_parcela === 'number' ? c.valor_parcela.toFixed(2) : c.valor_parcela} /mês
                    </span>
                    <span className="text-[10px] text-slate-500">
                      Total: R$ {Number(c.valor_produto).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </span>
                  </div>
                </div>
              ))}
              {caixinhas.length === 0 && (
                <p className="text-xs text-slate-500 py-4 text-center">Nenhuma caixinha cadastrada.</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
