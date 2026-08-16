export interface User {
  id: number;
  name: string;
  email: string;
  created_at?: string;
}

export interface StatusDespesa {
  id: number;
  nome: string;
}

export interface TipoDespesa {
  id: number;
  nome: string;
}

export interface Plano {
  id: number;
  descricao_simples: string | null;
  mes_ano: string;
  user_id: number;
  despesas_count?: number;
  despesas?: Despesa[];
  gastos?: Gasto;
}

export interface Caixinha {
  id: number;
  descricao: string;
  valor_produto: number;
  parcelas: number;
  valor_parcela: string | number;
  deleted_at?: string | null;
}

export interface Gasto {
  id: number;
  plano_id: number;
  valor: number;
  plano?: Plano;
}

export interface Renda {
  id: number;
  saldo: number;
  custo: number;
  user_id: number;
}

export interface RendaSummary {
  total_receita: number;
  total_custo: number;
  saldo_liquido: number;
}

export interface Despesa {
  id: number;
  descricao: string;
  valor_documento: number;
  data_vencimento: string;
  plano_id: number;
  status_despesa_id: number;
  tipo_despesa_id: number;
  status_despesa?: StatusDespesa;
  tipo_despesa?: TipoDespesa;
  plano?: Plano;
}

export interface HistoricoDespesa {
  id: number;
  despesa_id: number;
  status_despesa_id: number;
  data: string;
  despesa?: Despesa;
  status_despesa?: StatusDespesa;
}

export interface Produto {
  id: number;
  descricao_curta: string;
  preco: number;
  quantidade: number;
  tipo_medida: string;
  data_compra: string;
  user_id: number;
  total: number;
}
