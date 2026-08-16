import { apiFetch } from '@/shared/lib/api';
import { Produto } from '@/shared/types';

export const produtoService = {
  getProdutos: () => apiFetch<Produto[]>('/produtos'),
  createProduto: (data: {
    descricao_curta: string;
    preco: number;
    quantidade: number;
    tipo_medida: string;
    data_compra: string;
  }) => apiFetch<Produto>('/produtos', { method: 'POST', body: JSON.stringify(data) }),

  updateProduto: (id: number, data: Partial<Produto>) =>
    apiFetch<Produto>(`/produtos/${id}`, { method: 'PUT', body: JSON.stringify(data) }),

  deleteProduto: (id: number) =>
    apiFetch<{ message: string }>(`/produtos/${id}`, { method: 'DELETE' }),
};
