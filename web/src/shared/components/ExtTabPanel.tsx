'use client';

import React, { useState, useRef, useEffect } from 'react';
import { useTabContext, TabItem } from '@/shared/context/TabContext';
import {
  LayoutDashboard,
  TrendingUp,
  Receipt,
  CreditCard,
  Package,
  Terminal,
  Users,
  X,
  MoreVertical,
  Layers,
  XCircle,
  RefreshCw,
} from 'lucide-react';

const ICON_MAP: Record<string, React.ComponentType<{ className?: string }>> = {
  LayoutDashboard,
  TrendingUp,
  Receipt,
  CreditCard,
  Package,
  Terminal,
  Users,
  Layers,
};

export function ExtTabPanel() {
  const {
    tabs,
    activeTabId,
    setActiveTabId,
    closeTab,
    closeOtherTabs,
    closeAllTabs,
    refreshTab,
    refreshAllTabs,
    isSyncing,
  } = useTabContext();
  const [menuOpen, setMenuOpen] = useState(false);
  const menuRef = useRef<HTMLDivElement>(null);

  // Fecha o menu de contexto de abas ao clicar fora
  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (menuRef.current && !menuRef.current.contains(event.target as Node)) {
        setMenuOpen(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  return (
    <div className="bg-slate-900 border-b border-slate-800 flex items-center justify-between px-4 select-none">
      {/* Lista de Abas ExtJS Scrollable */}
      <div className="flex items-center gap-1 overflow-x-auto scrollbar-none py-1.5 flex-1 min-w-0">
        {tabs.map((tab) => {
          const isActive = activeTabId === tab.id;
          const Icon = ICON_MAP[tab.iconName] || Layers;

          return (
            <div
              key={tab.id}
              onClick={() => setActiveTabId(tab.id)}
              className={`group relative flex items-center gap-2.5 px-3.5 py-2 rounded-t-xl text-xs font-semibold cursor-pointer transition-all duration-150 shrink-0 border-t-2 border-x ${
                isActive
                  ? 'bg-slate-950 text-indigo-400 border-t-indigo-500 border-x-slate-800 shadow-md shadow-indigo-500/5 z-10 -mb-[7px] pb-3'
                  : 'bg-slate-900/60 text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 border-t-transparent border-x-slate-800/40'
              }`}
            >
              <Icon className={`w-4 h-4 ${isActive ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300'}`} />
              <span className="whitespace-nowrap tracking-tight">{tab.title}</span>

              {/* Botão Fechar Aba (ExtJS Close Tool) */}
              {tab.closable && (
                <button
                  type="button"
                  onClick={(e) => closeTab(tab.id, e)}
                  title="Fechar Aba"
                  className="w-4 h-4 rounded-full flex items-center justify-center text-slate-500 hover:text-rose-400 hover:bg-rose-500/20 transition-colors ml-1"
                >
                  <X className="w-3 h-3" />
                </button>
              )}
            </div>
          );
        })}
      </div>

      {/* ExtJS Tab Options Menu (Sincronizar / Fechar Outras / Fechar Todas) */}
      <div className="relative pl-3 border-l border-slate-800 shrink-0" ref={menuRef}>
        <button
          type="button"
          onClick={() => setMenuOpen(!menuOpen)}
          title="Opções do TabPanel"
          className="p-1.5 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors"
        >
          <MoreVertical className="w-4 h-4" />
        </button>

        {menuOpen && (
          <div className="absolute right-0 top-full mt-2 w-52 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl z-50 py-1.5 text-xs text-slate-200 divide-y divide-slate-800">
            <div className="py-1">
              <button
                type="button"
                onClick={() => {
                  refreshTab();
                  setMenuOpen(false);
                }}
                className="w-full text-left px-4 py-2 hover:bg-slate-800 flex items-center gap-2 font-medium text-indigo-400"
              >
                <RefreshCw className={`w-3.5 h-3.5 ${isSyncing ? 'animate-spin' : ''}`} />
                Sincronizar Aba Atual
              </button>
              <button
                type="button"
                onClick={() => {
                  refreshAllTabs();
                  setMenuOpen(false);
                }}
                className="w-full text-left px-4 py-2 hover:bg-slate-800 flex items-center gap-2 font-medium text-indigo-300"
              >
                <RefreshCw className={`w-3.5 h-3.5 ${isSyncing ? 'animate-spin' : ''}`} />
                Sincronizar Todas as Abas
              </button>
            </div>
            <div className="py-1">
              <button
                type="button"
                onClick={() => {
                  closeOtherTabs(activeTabId);
                  setMenuOpen(false);
                }}
                className="w-full text-left px-4 py-2 hover:bg-slate-800 flex items-center gap-2 font-medium"
              >
                <X className="w-3.5 h-3.5 text-amber-400" />
                Fechar Outras Abas
              </button>
              <button
                type="button"
                onClick={() => {
                  closeAllTabs();
                  setMenuOpen(false);
                }}
                className="w-full text-left px-4 py-2 hover:bg-slate-800 flex items-center gap-2 font-medium text-rose-400"
              >
                <XCircle className="w-3.5 h-3.5 text-rose-400" />
                Fechar Todas as Abas
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
