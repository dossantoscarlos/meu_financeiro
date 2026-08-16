import { getAuthToken } from '@/shared/lib/api';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8001/api';

export interface ReportItem {
  id: string;
  title: string;
  category: string;
  description: string;
  format: 'PDF' | 'EXCEL' | 'WEB';
  badge: string;
  badgeColor: string;
  available: boolean;
  endpoint: string;
}

export const AVAILABLE_REPORTS: ReportItem[] = [
  {
    id: 'custo-mensal-despesas',
    title: 'Relatório de Custo Mensal de Despesas',
    category: 'Despesas & Custos',
    description: 'Análise detalhada de custos, gastos por categoria, top 5 maiores despesas e custo médio por item.',
    format: 'PDF',
    badge: 'Gerencial',
    badgeColor: 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30',
    available: true,
    endpoint: '/relatorios/despesas-custo-mensal',
  },
  {
    id: 'balanco-receita-despesa',
    title: 'Balanço Mensal: Receitas vs. Despesas',
    category: 'Financeiro Geral',
    description: 'Demonstrativo consolidado entre o saldo de rendas recebidas e total de saídas no período com % de comprometimento.',
    format: 'PDF',
    badge: 'Consolidado',
    badgeColor: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
    available: true,
    endpoint: '/relatorios/balanco-mensal',
  },
  {
    id: 'analise-categorias',
    title: 'Análise Comparativa por Categorias',
    category: 'Categorias',
    description: 'Distribuição percentual e acumulada por categorias de gastos com gráficos comparativos de custos.',
    format: 'PDF',
    badge: 'Analítico',
    badgeColor: 'bg-amber-500/20 text-amber-400 border-amber-500/30',
    available: true,
    endpoint: '/relatorios/categorias',
  },
  {
    id: 'historico-audit-pagamentos',
    title: 'Histórico & Auditoria de Status de Despesas',
    category: 'Auditoria',
    description: 'Listagem completa de liquidações, pagamentos pendentes, atrasos e histórico de alterações em audit log.',
    format: 'PDF',
    badge: 'Auditoria',
    badgeColor: 'bg-purple-500/20 text-purple-400 border-purple-500/30',
    available: true,
    endpoint: '/relatorios/auditoria-despesas',
  },
  {
    id: 'relatorio-produtos',
    title: 'Relatório Gerencial de Produtos & Estoque',
    category: 'Produtos & Estoque',
    description: 'Avaliação física e financeira do catálogo de produtos em estoque, preço médio e valor total acumulado.',
    format: 'PDF',
    badge: 'Estoque',
    badgeColor: 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30',
    available: true,
    endpoint: '/relatorios/produtos',
  },
];

export const relatoriosService = {
  /**
   * Obtém o Blob do relatório PDF específico para visualização ou download
   */
  getRelatorioPdfBlob: async (endpoint: string, mes: number, ano: number): Promise<Blob> => {
    const token = getAuthToken();
    const headers: Record<string, string> = {};

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    const response = await fetch(`${API_URL}${endpoint}?mes=${mes}&ano=${ano}`, {
      method: 'GET',
      headers,
    });

    if (!response.ok) {
      throw new Error(`Erro ao gerar relatório (HTTP ${response.status})`);
    }

    return response.blob();
  },

  /**
   * Baixa diretamente o arquivo PDF com nome e endpoint específicos
   */
  downloadRelatorioPdf: async (report: ReportItem, mes: number, ano: number) => {
    const blob = await relatoriosService.getRelatorioPdfBlob(report.endpoint, mes, ano);
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${report.id}_${mes.toString().padStart(2, '0')}_${ano}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  },
};
