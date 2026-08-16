'use client';

import { useState, useEffect } from 'react';
import { despesasService } from '../services/despesasService';
import { pagamentosService } from '@/modules/pagamentos/services/pagamentosService';
import { Despesa, TipoDespesa, StatusDespesa, Plano, HistoricoDespesa } from '@/shared/types';
import { Receipt, Plus, Filter, Trash2, Edit2, Calendar, Tag, CheckCircle2, Clock, AlertCircle, History, RefreshCw } from 'lucide-react';

export default function DespesasModule() {
  const [despesas, setDespesas] = useState<Despesa[]>([]);
  const [tipos, setTipos] = useState<TipoDespesa[]>([]);
  const [statusList, setStatusList] = useState<StatusDespesa[]>([]);
  const [planos, setPlanos] = useState<Plano[]>([]);
  const [historico, setHistorico] = useState<HistoricoDespesa[]>([]);
  const [loading, setLoading] = useState(true);

  // Filtros
  const [selectedStatus, setSelectedStatus] = useState<string>('');
  const [selectedTipo, setSelectedTipo] = useState<string>('');
  const [selectedPlano, setSelectedPlano] = useState<string>('');

  // Modais
  const [showModal, setShowModal] = useState(false);
  const [showHistoricoModal, setShowHistoricoModal] = useState(false);
  const [editingDespesa, setEditingDespesa] = useState<Despesa | null>(null);

  // Form State
  const [descricao, setDescricao] = useState('');
  const [valorDocumento, setValorDocumento] = useState('');
  const [dataVencimento, setDataVencimento] = useState('');
  const [planoId, setPlanoId] = useState('');
  const [statusId, setStatusId] = useState('');
  const [tipoId, setTipoId] = useState('');

  useEffect(() => {
    loadAllData();
  }, []);

  useEffect(() => {
    fetchFilteredDespesas();
  }, [selectedStatus, selectedTipo, selectedPlano]);

  async function loadAllData() {
    try {
      setLoading(true);
      const [resDespesas, resTipos, resStatus, resPlanos] = await Promise.all([
        despesasService.getDespesas(),
        despesasService.getTipos(),
        despesasService.getStatus(),
        pagamentosService.getPlanos(),
      ]);
      setDespesas(resDespesas);
      setTipos(resTipos);
      setStatusList(resStatus);
      setPlanos(resPlanos);
    } catch (err: any) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  async function fetchFilteredDespesas() {
    try {
      const res = await despesasService.getDespesas({
        status_id: selectedStatus ? parseInt(selectedStatus) : undefined,
        tipo_id: selectedTipo ? parseInt(selectedTipo) : undefined,
        plano_id: selectedPlano ? parseInt(selectedPlano) : undefined,
      });
      setDespesas(res);
    } catch (err: any) {
      console.error(err);
    }
  }

  async function loadHistorico() {
    try {
      const res = await despesasService.getHistorico();
      setHistorico(res);
      setShowHistoricoModal(true);
    } catch (err: any) {
      alert('Erro ao carregar histórico');
    }
  }

  function handleOpenModal(despesa?: Despesa) {
    if (despesa) {
      setEditingDespesa(despesa);
      setDescricao(despesa.descricao);
      setValorDocumento(despesa.valor_documento.toString());
      setDataVencimento(despesa.data_vencimento ? despesa.data_vencimento.substring(0, 10) : '');
      setPlanoId(despesa.plano_id.toString());
      setStatusId(despesa.status_despesa_id.toString());
      setTipoId(despesa.tipo_despesa_id.toString());
    } else {
      setEditingDespesa(null);
      setDescricao('');
      setValorDocumento('');
      setDataVencimento('');
      setPlanoId(planos.length > 0 ? planos[0].id.toString() : '');
      setStatusId(statusList.length > 0 ? statusList[0].id.toString() : '1');
      setTipoId(tipos.length > 0 ? tipos[0].id.toString() : '');
    }
    setShowModal(true);
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    try {
      const payload = {
        descricao,
        valor_documento: parseFloat(valorDocumento),
        data_vencimento: dataVencimento,
        plano_id: parseInt(planoId),
        status_despesa_id: parseInt(statusId),
        tipo_despesa_id: parseInt(tipoId),
      };

      if (editingDespesa) {
        await despesasService.updateDespesa(editingDespesa.id, payload);
      } else {
        await despesasService.createDespesa(payload);
      }

      setShowModal(false);
      fetchFilteredDespesas();
    } catch (err: any) {
      alert(err.message || 'Erro ao salvar despesa');
    }
  }

  async function handleDelete(id: number) {
    if (!confirm('Deseja realmente excluir esta despesa?')) return;
    try {
      await despesasService.deleteDespesa(id);
      fetchFilteredDespesas();
    } catch (err: any) {
      alert(err.message || 'Erro ao remover despesa');
    }
  }

  function renderStatusBadge(status?: StatusDespesa) {
    const nome = status?.nome?.toLowerCase() || '';
    if (nome.includes('pago')) {
      return (
        <span className="inline-flex items-center gap-1 text-xs font-bold uppercase bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 px-2.5 py-0.5 rounded-full">
          <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400" /> Pago
        </span>
      );
    }
    if (nome.includes('atrasado')) {
      return (
        <span className="inline-flex items-center gap-1 text-xs font-bold uppercase bg-rose-950/80 border border-rose-500/50 text-rose-300 px-2.5 py-0.5 rounded-full">
          <AlertCircle className="w-3.5 h-3.5 text-rose-400" /> Atrasado
        </span>
      );
    }
    return (
      <span className="inline-flex items-center gap-1 text-xs font-bold uppercase bg-amber-950/80 border border-amber-500/50 text-amber-300 px-2.5 py-0.5 rounded-full">
        <Clock className="w-3.5 h-3.5 text-amber-400" /> Pendente
      </span>
    );
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center p-12 text-slate-400">
        <RefreshCw className="w-6 h-6 animate-spin mr-2" />
        Carregando gestão corporativa de despesas...
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Header Corporativo */}
      <div className="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-700/50 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <Receipt className="w-7 h-7 text-indigo-400" />
            Módulo Corporativo de Despesas
          </h1>
          <p className="text-sm text-slate-400 mt-1">
            Controle de contas a pagar, datas de vencimento, categorização e trilha de auditoria.
          </p>
        </div>
        <div className="flex items-center gap-3">
          <button
            onClick={loadHistorico}
            className="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition-all"
          >
            <History className="w-4 h-4 text-indigo-400" /> Histórico de Alterações
          </button>
          <button
            onClick={() => handleOpenModal()}
            className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-indigo-600/30 flex items-center gap-2 transition-all"
          >
            <Plus className="w-4 h-4" /> Nova Despesa
          </button>
        </div>
      </div>

      {/* Barra de Filtros Corporativa */}
      <div className="bg-slate-800/80 border border-slate-700 rounded-xl p-4 flex flex-col md:flex-row items-center gap-4">
        <div className="flex items-center gap-2 text-xs font-bold uppercase text-slate-400">
          <Filter className="w-4 h-4 text-indigo-400" /> Filtrar Por:
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full md:w-auto flex-1">
          <select
            value={selectedStatus}
            onChange={(e) => setSelectedStatus(e.target.value)}
            className="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500"
          >
            <option value="">Todos os Status</option>
            {statusList.map((st) => (
              <option key={st.id} value={st.id}>
                Status: {st.nome}
              </option>
            ))}
          </select>

          <select
            value={selectedTipo}
            onChange={(e) => setSelectedTipo(e.target.value)}
            className="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500"
          >
            <option value="">Todas as Categorias</option>
            {tipos.map((tp) => (
              <option key={tp.id} value={tp.id}>
                Categoria: {tp.nome}
              </option>
            ))}
          </select>

          <select
            value={selectedPlano}
            onChange={(e) => setSelectedPlano(e.target.value)}
            className="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500"
          >
            <option value="">Todos os Planos Mensais</option>
            {planos.map((pl) => (
              <option key={pl.id} value={pl.id}>
                Plano: {pl.mes_ano}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Tabela de Despesas */}
      <div className="bg-slate-800/80 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
        {despesas.length === 0 ? (
          <div className="p-8 text-center text-slate-400 text-sm">
            Nenhuma despesa localizada com os filtros selecionados.
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="bg-slate-900/80 text-xs font-semibold uppercase text-slate-400 border-b border-slate-700">
                <tr>
                  <th className="px-6 py-3.5">Descrição</th>
                  <th className="px-6 py-3.5">Status</th>
                  <th className="px-6 py-3.5">Categoria</th>
                  <th className="px-6 py-3.5">Plano Mensal</th>
                  <th className="px-6 py-3.5">Vencimento</th>
                  <th className="px-6 py-3.5">Valor (R$)</th>
                  <th className="px-6 py-3.5 text-right">Ações</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-700/60">
                {despesas.map((despesa) => (
                  <tr key={despesa.id} className="hover:bg-slate-700/30 transition-colors">
                    <td className="px-6 py-4 font-bold text-slate-100">{despesa.descricao}</td>
                    <td className="px-6 py-4">{renderStatusBadge(despesa.status_despesa)}</td>
                    <td className="px-6 py-4">
                      <span className="text-xs bg-slate-900 border border-slate-700 text-slate-300 px-2.5 py-1 rounded">
                        {despesa.tipo_despesa?.nome || 'N/A'}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-xs text-slate-300">
                      {despesa.plano?.mes_ano || 'N/A'}
                    </td>
                    <td className="px-6 py-4 font-mono text-slate-300 text-xs">
                      {new Date(despesa.data_vencimento).toLocaleDateString('pt-BR')}
                    </td>
                    <td className="px-6 py-4 font-bold text-slate-100">
                      R$ {Number(despesa.valor_documento).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <button
                          onClick={() => handleOpenModal(despesa)}
                          className="text-slate-400 hover:text-indigo-400 transition-colors"
                          title="Editar"
                        >
                          <Edit2 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(despesa.id)}
                          className="text-slate-400 hover:text-rose-400 transition-colors"
                          title="Deletar"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Modal Criar/Editar Despesa */}
      {showModal && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
          <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-lg font-bold text-slate-100 mb-4">
              {editingDespesa ? 'Editar Despesa' : 'Nova Despesa Corporativa'}
            </h3>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Descrição</label>
                <input
                  type="text"
                  required
                  value={descricao}
                  onChange={(e) => setDescricao(e.target.value)}
                  placeholder="Fatura Servidores Nuvem"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Valor do Documento (R$)</label>
                  <input
                    type="number"
                    step="0.01"
                    required
                    value={valorDocumento}
                    onChange={(e) => setValorDocumento(e.target.value)}
                    placeholder="450.00"
                    className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Data de Vencimento</label>
                  <input
                    type="date"
                    required
                    value={dataVencimento}
                    onChange={(e) => setDataVencimento(e.target.value)}
                    className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                  />
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Plano Mensal</label>
                  <select
                    required
                    value={planoId}
                    onChange={(e) => setPlanoId(e.target.value)}
                    className="w-full bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-2.5 py-2 focus:outline-none focus:border-indigo-500"
                  >
                    <option value="">Selecione...</option>
                    {planos.map((p) => (
                      <option key={p.id} value={p.id}>
                        {p.mes_ano}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Status</label>
                  <select
                    required
                    value={statusId}
                    onChange={(e) => setStatusId(e.target.value)}
                    className="w-full bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-2.5 py-2 focus:outline-none focus:border-indigo-500"
                  >
                    <option value="">Selecione...</option>
                    {statusList.map((st) => (
                      <option key={st.id} value={st.id}>
                        {st.nome}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Categoria</label>
                  <select
                    required
                    value={tipoId}
                    onChange={(e) => setTipoId(e.target.value)}
                    className="w-full bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-lg px-2.5 py-2 focus:outline-none focus:border-indigo-500"
                  >
                    <option value="">Selecione...</option>
                    {tipos.map((tp) => (
                      <option key={tp.id} value={tp.id}>
                        {tp.nome}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-3 border-t border-slate-700/60">
                <button
                  type="button"
                  onClick={() => setShowModal(false)}
                  className="bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs px-4 py-2 rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg"
                >
                  Salvar Despesa
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal Histórico */}
      {showHistoricoModal && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
          <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-2xl shadow-2xl">
            <div className="flex justify-between items-center mb-4 pb-2 border-b border-slate-700">
              <h3 className="text-lg font-bold text-slate-100 flex items-center gap-2">
                <History className="w-5 h-5 text-indigo-400" />
                Histórico de Alterações de Despesas
              </h3>
              <button
                onClick={() => setShowHistoricoModal(false)}
                className="text-slate-400 hover:text-slate-200 text-xs font-bold"
              >
                ✕ Fechar
              </button>
            </div>

            <div className="max-h-96 overflow-y-auto space-y-3">
              {historico.length === 0 ? (
                <p className="text-center text-slate-400 text-xs p-4">Nenhum registro de histórico.</p>
              ) : (
                historico.map((h) => (
                  <div key={h.id} className="bg-slate-900 border border-slate-700/70 rounded-lg p-3 text-xs flex justify-between items-center">
                    <div>
                      <p className="font-bold text-slate-200">{h.despesa?.descricao || 'Despesa ID #' + h.despesa_id}</p>
                      <p className="text-slate-400 mt-0.5">Novo Status: <strong className="text-indigo-300">{h.status_despesa?.nome}</strong></p>
                    </div>
                    <span className="font-mono text-slate-500">{new Date(h.data).toLocaleString('pt-BR')}</span>
                  </div>
                ))
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
