'use client';

import { useState, useEffect } from 'react';
import { produtoService } from '../services/produtoService';
import { Produto } from '@/shared/types';
import { Package, Plus, Trash2, Edit2, ShoppingCart, Calculator, Calendar, RefreshCw, Tag } from 'lucide-react';

export default function ProdutoModule() {
  const [produtos, setProdutos] = useState<Produto[]>([]);
  const [loading, setLoading] = useState(true);

  // Modais
  const [showModal, setShowModal] = useState(false);
  const [editingProduto, setEditingProduto] = useState<Produto | null>(null);

  // Form State
  const [descricaoCurta, setDescricaoCurta] = useState('');
  const [preco, setPreco] = useState('');
  const [quantidade, setQuantidade] = useState('');
  const [tipoMedida, setTipoMedida] = useState('UN');
  const [dataCompra, setDataCompra] = useState('');

  useEffect(() => {
    loadProdutos();
  }, []);

  async function loadProdutos() {
    try {
      setLoading(true);
      const res = await produtoService.getProdutos();
      setProdutos(res);
    } catch (err: any) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  function handleOpenModal(produto?: Produto) {
    if (produto) {
      setEditingProduto(produto);
      setDescricaoCurta(produto.descricao_curta);
      setPreco(produto.preco.toString());
      setQuantidade(produto.quantidade.toString());
      setTipoMedida(produto.tipo_medida);
      setDataCompra(produto.data_compra ? produto.data_compra.substring(0, 10) : '');
    } else {
      setEditingProduto(null);
      setDescricaoCurta('');
      setPreco('');
      setQuantidade('1');
      setTipoMedida('UN');
      setDataCompra(new Date().toISOString().substring(0, 10));
    }
    setShowModal(true);
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    try {
      const payload = {
        descricao_curta: descricaoCurta,
        preco: parseFloat(preco),
        quantidade: parseFloat(quantidade),
        tipo_medida: tipoMedida,
        data_compra: dataCompra,
      };

      if (editingProduto) {
        await produtoService.updateProduto(editingProduto.id, payload);
      } else {
        await produtoService.createProduto(payload);
      }

      setShowModal(false);
      loadProdutos();
    } catch (err: any) {
      alert(err.message || 'Erro ao salvar produto');
    }
  }

  async function handleDelete(id: number) {
    if (!confirm('Deseja realmente remover este produto?')) return;
    try {
      await produtoService.deleteProduto(id);
      loadProdutos();
    } catch (err: any) {
      alert(err.message || 'Erro ao deletar produto');
    }
  }

  // Regra de negócio: Cálculo do valor total estimado no form
  const totalCalculado = (parseFloat(preco || '0') * parseFloat(quantidade || '0')).toFixed(2);

  if (loading) {
    return (
      <div className="flex items-center justify-center p-12 text-slate-400">
        <RefreshCw className="w-6 h-6 animate-spin mr-2" />
        Carregando catálogo de produtos...
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Header Corporativo */}
      <div className="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-700/50 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <Package className="w-7 h-7 text-indigo-400" />
            Módulo Corporativo de Produtos & Insumos
          </h1>
          <p className="text-sm text-slate-400 mt-1">
            Cadastro de itens, unidades de medida, preços unitários e cálculo automático do valor total do pedido.
          </p>
        </div>
        <button
          onClick={() => handleOpenModal()}
          className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-indigo-600/30 flex items-center gap-2 transition-all self-start md:self-auto"
        >
          <Plus className="w-4 h-4" /> Cadastrar Produto
        </button>
      </div>

      {/* Grid de Cards de Insumos */}
      <div className="bg-slate-800/80 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
        <div className="px-6 py-4 border-b border-slate-700 flex justify-between items-center bg-slate-900/60">
          <h3 className="text-base font-bold text-slate-100 flex items-center gap-2">
            <ShoppingCart className="w-5 h-5 text-indigo-400" />
            Lista de Produtos Registrados ({produtos.length})
          </h3>
        </div>

        {produtos.length === 0 ? (
          <div className="p-8 text-center text-slate-400 text-sm">
            Nenhum produto cadastrado até o momento.
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-300">
              <thead className="bg-slate-900/80 text-xs font-semibold uppercase text-slate-400 border-b border-slate-700">
                <tr>
                  <th className="px-6 py-3.5">Descrição</th>
                  <th className="px-6 py-3.5">Medida</th>
                  <th className="px-6 py-3.5">Preço Unitário (R$)</th>
                  <th className="px-6 py-3.5">Quantidade</th>
                  <th className="px-6 py-3.5">Total Calculado (R$)</th>
                  <th className="px-6 py-3.5">Data da Compra</th>
                  <th className="px-6 py-3.5 text-right">Ações</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-700/60">
                {produtos.map((prod) => (
                  <tr key={prod.id} className="hover:bg-slate-700/30 transition-colors">
                    <td className="px-6 py-4 font-bold text-slate-100">{prod.descricao_curta}</td>
                    <td className="px-6 py-4">
                      <span className="bg-slate-900 border border-slate-700 text-indigo-300 px-2 py-0.5 rounded text-xs font-mono font-bold">
                        {prod.tipo_medida}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-slate-200 font-semibold">
                      R$ {Number(prod.preco).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </td>
                    <td className="px-6 py-4 font-semibold text-slate-300">
                      {prod.quantidade}
                    </td>
                    <td className="px-6 py-4 font-black text-emerald-400">
                      R$ {Number(prod.total ?? (prod.preco * prod.quantidade)).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </td>
                    <td className="px-6 py-4 font-mono text-slate-400 text-xs">
                      {new Date(prod.data_compra).toLocaleDateString('pt-BR')}
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <button
                          onClick={() => handleOpenModal(prod)}
                          className="text-slate-400 hover:text-indigo-400 transition-colors"
                          title="Editar"
                        >
                          <Edit2 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(prod.id)}
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

      {/* Modal Criar/Editar Produto */}
      {showModal && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
          <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-lg font-bold text-slate-100 mb-4">
              {editingProduto ? 'Editar Produto' : 'Cadastrar Novo Produto'}
            </h3>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Descrição Curta</label>
                <input
                  type="text"
                  required
                  value={descricaoCurta}
                  onChange={(e) => setDescricaoCurta(e.target.value)}
                  placeholder="Cadeira Ergonômica Presidente"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Preço Unitário (R$)</label>
                  <input
                    type="number"
                    step="0.01"
                    required
                    value={preco}
                    onChange={(e) => setPreco(e.target.value)}
                    placeholder="850.00"
                    className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Quantidade</label>
                  <input
                    type="number"
                    step="0.01"
                    required
                    value={quantidade}
                    onChange={(e) => setQuantidade(e.target.value)}
                    placeholder="2"
                    className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Unidade de Medida</label>
                  <select
                    value={tipoMedida}
                    onChange={(e) => setTipoMedida(e.target.value)}
                    className="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500"
                  >
                    <option value="UN">UN (Unidade)</option>
                    <option value="KG">KG (Quilograma)</option>
                    <option value="CX">CX (Caixa)</option>
                    <option value="PCT">PCT (Pacote)</option>
                    <option value="M">M (Metro)</option>
                    <option value="L">L (Litro)</option>
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">Data da Compra</label>
                  <input
                    type="date"
                    required
                    value={dataCompra}
                    onChange={(e) => setDataCompra(e.target.value)}
                    className="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                  />
                </div>
              </div>

              {/* Total estimado em tempo real */}
              <div className="bg-slate-900/90 border border-slate-700 p-3 rounded-lg flex justify-between items-center text-xs">
                <span className="text-slate-400 flex items-center gap-1.5 font-semibold">
                  <Calculator className="w-4 h-4 text-emerald-400" /> Total Calculado:
                </span>
                <span className="text-emerald-400 font-bold text-sm">
                  R$ {Number(totalCalculado).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                </span>
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
                  Salvar Produto
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
