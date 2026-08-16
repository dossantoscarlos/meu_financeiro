'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { apiFetch, clearAuthToken, getAuthToken } from '@/shared/lib/api';
import { User } from '@/shared/types';
import { TabProvider, useTabContext } from '@/shared/context/TabContext';
import { ExtTabPanel } from '@/shared/components/ExtTabPanel';
import { TabContentContainer } from '@/shared/components/TabContentContainer';
import {
    LayoutDashboard,
    Users,
    CreditCard,
    TrendingUp,
    Receipt,
    Package,
    LogOut,
    Shield,
    Menu,
    X,
    Terminal,
    RefreshCw,
    FileBarChart,
} from 'lucide-react';

const NAV_GROUPS = [
    {
        group: 'Dashboard',
        items: [
            { name: 'Visão Geral', href: '/', icon: LayoutDashboard, iconName: 'LayoutDashboard', closable: false },
        ],
    },
    {
        group: 'Financeiro',
        items: [
            { name: 'Receita / Rendas', href: '/receita', aliases: ['/rendas'], icon: TrendingUp, iconName: 'TrendingUp', closable: true },
            { name: 'Despesas', href: '/despesas', icon: Receipt, iconName: 'Receipt', closable: true },
            { name: 'Pagamentos & Planos', href: '/pagamentos', aliases: ['/planos', '/caixinhas'], icon: CreditCard, iconName: 'CreditCard', closable: true },
            { name: 'Relatórios', href: '/relatorios', icon: FileBarChart, iconName: 'FileBarChart', closable: true },
        ],
    },
    {
        group: 'Operação',
        items: [
            { name: 'Produtos', href: '/produto', aliases: ['/produtos'], icon: Package, iconName: 'Package', closable: true },
        ],
    },
    {
        group: 'Ferramentas',
        items: [
            { name: 'Console SQL', href: '/sql-console', icon: Terminal, iconName: 'Terminal', closable: true },
        ],
    },
    {
        group: 'Configuração',
        items: [
            { name: 'Usuários & Perfil', href: '/users', icon: Users, iconName: 'Users', closable: true },
        ],
    },
];

function InnerDashboardLayout({ children }: { children: React.ReactNode }) {
    const pathname = usePathname();
    const router = useRouter();
    const [user, setUser] = useState<User | null>(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const { openTab, activeTabId, refreshTab, isSyncing, lastSyncedAt } = useTabContext();

    useEffect(() => {
        const token = getAuthToken();
        if (!token) {
            router.push('/login');
            return;
        }

        apiFetch<{ user: User }>('/auth/me')
            .then((res) => setUser(res.user))
            .catch(() => {
                clearAuthToken();
                router.push('/login');
            });
    }, [router]);

    function handleLogout() {
        apiFetch('/auth/logout', { method: 'POST' }).finally(() => {
            clearAuthToken();
            router.push('/login');
        });
    }

    function handleNavClick(item: { name: string; href: string; iconName: string; closable: boolean }) {
        setSidebarOpen(false);
        openTab({
            id: item.href,
            title: item.name,
            iconName: item.iconName,
            closable: item.closable,
        });
    }

    return (
        <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col md:flex-row">
            {/* Sidebar Corporativa */}
            <aside
                className={`fixed md:static inset-y-0 left-0 z-40 w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between transform transition-transform duration-300 ${sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
                    }`}
            >
                <div>
                    {/* Logo Corporativa */}
                    <div className="h-16 px-6 border-b border-slate-800 flex items-center justify-between">
                        <Link href="/" className="flex items-center gap-2.5">
                            <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-indigo-600/30">
                                <Shield className="w-5 h-5" />
                            </div>
                            <span className="font-bold text-lg text-slate-100 tracking-tight">Meu Financeiro</span>
                        </Link>
                        <button
                            onClick={() => setSidebarOpen(false)}
                            className="md:hidden text-slate-400 hover:text-slate-200"
                        >
                            <X className="w-5 h-5" />
                        </button>
                    </div>

                    {/* Navegação por Grupos Filament com Suporte a Abas ExtJS */}
                    <nav className="p-4 space-y-5">
                        {NAV_GROUPS.map((group) => (
                            <div key={group.group} className="space-y-1">
                                <div className="px-3 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                                    {group.group}
                                </div>
                                {group.items.map((item) => {
                                    const Icon = item.icon;
                                    const isActive =
                                        activeTabId === item.href || (item.aliases && item.aliases.includes(activeTabId));
                                    return (
                                        <button
                                            key={item.href}
                                            type="button"
                                            onClick={() => handleNavClick(item)}
                                            className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all text-left ${isActive
                                                ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/40 shadow-md shadow-indigo-600/10'
                                                : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 border border-transparent'
                                                }`}
                                        >
                                            <Icon className={`w-4 h-4 ${isActive ? 'text-indigo-400' : 'text-slate-500'}`} />
                                            {item.name}
                                        </button>
                                    );
                                })}
                            </div>
                        ))}
                    </nav>
                </div>

                {/* Rodapé Usuário */}
                <div className="p-4 border-t border-slate-800 bg-slate-900/50 space-y-3">
                    <div className="flex items-center gap-3 px-2">
                        <div className="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center font-bold text-white text-sm shadow">
                            {user?.name?.charAt(0).toUpperCase() || 'U'}
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-xs font-bold text-slate-200 truncate">{user?.name || 'Carregando...'}</p>
                            <p className="text-[11px] text-slate-400 truncate">{user?.email}</p>
                        </div>
                    </div>

                    <button
                        onClick={handleLogout}
                        className="w-full bg-slate-800 hover:bg-rose-950/60 hover:text-rose-400 border border-slate-700 hover:border-rose-500/40 text-slate-300 text-xs font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2"
                    >
                        <LogOut className="w-4 h-4" /> Encerrar Sessão
                    </button>
                </div>
            </aside>

            {/* Conteúdo Principal com Sencha ExtJS Tab Panel */}
            <div className="flex-1 flex flex-col min-w-0 min-h-screen bg-slate-950">
                {/* Header Corporativo Superior */}
                <header className="h-16 bg-slate-900/80 border-b border-slate-800 px-6 flex items-center justify-between sticky top-0 backdrop-blur-md z-30">
                    <div className="flex items-center gap-4">
                        <button
                            onClick={() => setSidebarOpen(true)}
                            className="md:hidden text-slate-400 hover:text-slate-200"
                        >
                            <Menu className="w-6 h-6" />
                        </button>
                        <span className="text-xs font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700 px-2.5 py-1 rounded flex items-center gap-1.5">
                            <span className="w-2 h-2 rounded-full bg-indigo-400 animate-pulse" />
                            Gestão Financeira
                        </span>
                    </div>

                    <div className="flex items-center gap-3">
                        {/* Portal / Botão de Sincronização de Página */}
                        <button
                            type="button"
                            onClick={() => refreshTab()}
                            disabled={isSyncing}
                            className="flex items-center gap-2 bg-indigo-600/10 hover:bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 hover:border-indigo-500/60 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shadow-sm group cursor-pointer disabled:opacity-50"
                            title="Sincronizar dados da página atual"
                        >
                            <RefreshCw className={`w-3.5 h-3.5 ${isSyncing ? 'animate-spin text-indigo-300' : 'group-hover:rotate-180 transition-transform duration-500'}`} />
                            <span>{isSyncing ? 'Sincronizando...' : 'Sincronizar Página'}</span>
                            {lastSyncedAt[activeTabId] && !isSyncing && (
                                <span className="text-[10px] bg-slate-900 border border-slate-700/60 text-slate-400 px-1.5 py-0.5 rounded font-mono">
                                    {lastSyncedAt[activeTabId]}
                                </span>
                            )}
                        </button>

                        <div className="hidden sm:flex items-center gap-2 text-xs text-slate-400 bg-slate-950 border border-slate-800 px-3 py-1.5 rounded-lg">
                            <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            API Status: <strong className="text-slate-200">{process.env.NEXT_PUBLIC_API_URL}</strong>
                        </div>
                    </div>
                </header>

                {/* ExtJS Tab Bar (Painel de Abas Ativas) */}
                <ExtTabPanel />

                {/* Container de Conteúdo (Componentes Montados Permanentes) */}
                <main className="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
                    <TabContentContainer>{children}</TabContentContainer>
                </main>
            </div>
        </div>
    );
}

export default function DashboardLayout({ children }: { children: React.ReactNode }) {
    return (
        <TabProvider>
            <InnerDashboardLayout>{children}</InnerDashboardLayout>
        </TabProvider>
    );
}
