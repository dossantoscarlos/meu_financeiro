import { apiFetch } from '@/shared/lib/api';
import { Renda, RendaSummary } from '@/shared/types';

export const receitaService = {
  getRendas: () => apiFetch<Renda[]>('/rendas'),
  getSummary: () => apiFetch<RendaSummary>('/rendas/summary'),
  createRenda: (data: { saldo: number; custo: number }) =>
    apiFetch<Renda>('/rendas', { method: 'POST', body: JSON.stringify(data) }),
  updateRenda: (id: number, data: { saldo?: number; custo?: number }) =>
    apiFetch<Renda>(`/rendas/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteRenda: (id: number) =>
    apiFetch<{ message: string }>(`/rendas/${id}`, { method: 'DELETE' }),
};
