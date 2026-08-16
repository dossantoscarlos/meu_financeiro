'use client';

import { useState, useEffect } from 'react';
import { userService } from '../services/userService';
import { User } from '@/shared/types';
import { UserCheck, Shield, Key, Mail, User as UserIcon, RefreshCw, CheckCircle } from 'lucide-react';

export default function UsersModule() {
  const [user, setUser] = useState<User | null>(null);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState<string | null>(null);

  useEffect(() => {
    loadUser();
  }, []);

  async function loadUser() {
    try {
      setLoading(true);
      const res = await userService.getProfile();
      setUser(res.user);
      setName(res.user.name);
      setEmail(res.user.email);
    } catch (err: any) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    try {
      setSaving(true);
      setMessage(null);
      const res = await userService.updateProfile({ name, email, password: password || undefined });
      setUser(res.user);
      setPassword('');
      setMessage('Perfil atualizado com sucesso!');
      setTimeout(() => setMessage(null), 4000);
    } catch (err: any) {
      alert(err.message || 'Erro ao atualizar perfil');
    } finally {
      setSaving(false);
    }
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center p-12 text-slate-400">
        <RefreshCw className="w-6 h-6 animate-spin mr-2" />
        Carregando informações do usuário...
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Header Corporativo */}
      <div className="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-700/50 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <UserIcon className="w-7 h-7 text-indigo-400" />
            Gestão de Usuários & Perfil Corporativo
          </h1>
          <p className="text-sm text-slate-400 mt-1">
            Gerencie suas informações de conta, credenciais de acesso e parâmetros de segurança da API.
          </p>
        </div>
        <div className="flex items-center gap-2 bg-slate-800 border border-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-400">
          <Shield className="w-4 h-4 text-emerald-400" />
          Acesso Seguro Corporativo (Sanctum Token)
        </div>
      </div>

      {message && (
        <div className="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded-lg flex items-center gap-3">
          <CheckCircle className="w-5 h-5 text-emerald-400" />
          <span>{message}</span>
        </div>
      )}

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Card Resumo do Usuário */}
        <div className="bg-slate-800/80 border border-slate-700 rounded-xl p-6 shadow-xl flex flex-col justify-between">
          <div>
            <div className="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-lg shadow-indigo-500/20 mx-auto">
              {user?.name?.charAt(0).toUpperCase()}
            </div>
            <h2 className="text-xl font-bold text-center text-slate-100">{user?.name}</h2>
            <p className="text-sm text-center text-slate-400 mb-6">{user?.email}</p>

            <div className="space-y-3 border-t border-slate-700 pt-4 text-xs">
              <div className="flex justify-between items-center text-slate-300">
                <span className="text-slate-400 flex items-center gap-1.5">
                  <UserCheck className="w-4 h-4 text-indigo-400" /> ID do Usuário:
                </span>
                <span className="font-mono bg-slate-900 px-2 py-0.5 rounded text-slate-200">#{user?.id}</span>
              </div>
              <div className="flex justify-between items-center text-slate-300">
                <span className="text-slate-400 flex items-center gap-1.5">
                  <Shield className="w-4 h-4 text-indigo-400" /> Nível de Acesso:
                </span>
                <span className="font-semibold text-indigo-400 bg-indigo-950/60 border border-indigo-800/50 px-2 py-0.5 rounded">
                  Administrador
                </span>
              </div>
            </div>
          </div>

          <div className="mt-6 pt-4 border-t border-slate-700/60 text-center">
            <span className="text-xs text-slate-500">Meu Financeiro API v1.0 • Client-Server</span>
          </div>
        </div>

        {/* Formulário Edição */}
        <div className="lg:col-span-2 bg-slate-800/80 border border-slate-700 rounded-xl p-6 shadow-xl">
          <h2 className="text-lg font-semibold text-slate-100 mb-4 pb-2 border-b border-slate-700/60 flex items-center gap-2">
            <Key className="w-5 h-5 text-indigo-400" />
            Editar Dados da Conta
          </h2>

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label className="block text-xs font-semibold uppercase text-slate-400 mb-1.5">Nome Completo</label>
              <div className="relative">
                <UserIcon className="w-5 h-5 text-slate-400 absolute left-3 top-2.5" />
                <input
                  type="text"
                  required
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg pl-10 pr-4 py-2.5 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 transition-colors"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-semibold uppercase text-slate-400 mb-1.5">Endereço de E-mail</label>
              <div className="relative">
                <Mail className="w-5 h-5 text-slate-400 absolute left-3 top-2.5" />
                <input
                  type="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg pl-10 pr-4 py-2.5 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 transition-colors"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-semibold uppercase text-slate-400 mb-1.5">Nova Senha (deixe em branco para não alterar)</label>
              <div className="relative">
                <Key className="w-5 h-5 text-slate-400 absolute left-3 top-2.5" />
                <input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  className="w-full bg-slate-900 border border-slate-700 rounded-lg pl-10 pr-4 py-2.5 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 transition-colors"
                />
              </div>
            </div>

            <div className="pt-2 flex justify-end">
              <button
                type="submit"
                disabled={saving}
                className="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2 disabled:opacity-50"
              >
                {saving && <RefreshCw className="w-4 h-4 animate-spin" />}
                Salvar Alterações
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
