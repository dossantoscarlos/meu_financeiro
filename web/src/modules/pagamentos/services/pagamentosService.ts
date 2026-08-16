import { apiFetch } from '@/shared/lib/api';
import { Plano, Caixinha, Gasto, StatusDespesa } from '@/shared/types';

export const pagamentosService = {
  // Planos
  getPlanos: () => apiFetch<Plano[]>('/planos'),
  createPlano: (data: { mes_ano: string; descricao_simples?: string }) =>
    apiFetch<Plano>('/planos', { method: 'POST', body: JSON.stringify(data) }),
  deletePlano: (id: number) =>
    apiFetch<{ message: string }>(`/planos/${id}`, { method: 'DELETE' }),

  // Caixinhas
  getCaixinhas: () => apiFetch<Caixinha[]>('/caixinhas'),
  createCaixinha: (data: { descricao: string; valor_produto: number; parcelas: number }) =>
    apiFetch<Caixinha>('/caixinhas', { method: 'POST', body: JSON.stringify(data) }),
  deleteCaixinha: (id: number) =>
    apiFetch<{ message: string }>(`/caixinhas/${id}`, { method: 'DELETE' }),

  // Gastos
  getGastos: () => apiFetch<Gasto[]>('/gastos'),
  createGasto: (data: { plano_id: number; valor: number }) =>
    apiFetch<Gasto>('/gastos', { method: 'POST', body: JSON.stringify(data) }),

  // Status
  getStatusDespesas: () => apiFetch<StatusDespesa[]>('/status-despesas'),
};
