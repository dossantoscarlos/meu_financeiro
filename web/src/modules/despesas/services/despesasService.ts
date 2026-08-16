import { apiFetch } from '@/shared/lib/api';
import { Despesa, TipoDespesa, StatusDespesa, HistoricoDespesa } from '@/shared/types';

export const despesasService = {
  getDespesas: (params?: { status_id?: number; tipo_id?: number; plano_id?: number }) => {
    const query = new URLSearchParams();
    if (params?.status_id) query.append('status_id', params.status_id.toString());
    if (params?.tipo_id) query.append('tipo_id', params.tipo_id.toString());
    if (params?.plano_id) query.append('plano_id', params.plano_id.toString());

    const queryString = query.toString() ? `?${query.toString()}` : '';
    return apiFetch<Despesa[]>(`/despesas${queryString}`);
  },

  createDespesa: (data: {
    descricao: string;
    valor_documento: number;
    data_vencimento: string;
    plano_id: number;
    status_despesa_id: number;
    tipo_despesa_id: number;
  }) => apiFetch<Despesa>('/despesas', { method: 'POST', body: JSON.stringify(data) }),

  updateDespesa: (id: number, data: Partial<Despesa>) =>
    apiFetch<Despesa>(`/despesas/${id}`, { method: 'PUT', body: JSON.stringify(data) }),

  deleteDespesa: (id: number) =>
    apiFetch<{ message: string }>(`/despesas/${id}`, { method: 'DELETE' }),

  getTipos: () => apiFetch<TipoDespesa[]>('/tipo-despesas'),
  createTipo: (nome: string) =>
    apiFetch<TipoDespesa>('/tipo-despesas', { method: 'POST', body: JSON.stringify({ nome }) }),

  getStatus: () => apiFetch<StatusDespesa[]>('/status-despesas'),
  getHistorico: () => apiFetch<HistoricoDespesa[]>('/historico-despesas'),
};
