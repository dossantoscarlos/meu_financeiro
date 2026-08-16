'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { usePathname, useRouter } from 'next/navigation';

export interface TabItem {
  id: string;
  title: string;
  iconName: string;
  closable: boolean;
}

interface TabContextType {
  tabs: TabItem[];
  activeTabId: string;
  openTab: (tab: TabItem) => void;
  closeTab: (id: string, e?: React.MouseEvent) => void;
  setActiveTabId: (id: string) => void;
  closeOtherTabs: (id: string) => void;
  closeAllTabs: () => void;
  refreshTab: (id?: string) => void;
  refreshAllTabs: () => void;
  refreshKeys: Record<string, number>;
  lastSyncedAt: Record<string, string | null>;
  isSyncing: boolean;
}

const DEFAULT_TABS: TabItem[] = [
  {
    id: '/',
    title: 'Visão Geral',
    iconName: 'LayoutDashboard',
    closable: false,
  },
];

const TabContext = createContext<TabContextType | undefined>(undefined);

export function TabProvider({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const router = useRouter();
  const [tabs, setTabs] = useState<TabItem[]>(DEFAULT_TABS);
  const [activeTabId, setActiveTabIdState] = useState<string>('/');
  const [refreshKeys, setRefreshKeys] = useState<Record<string, number>>({});
  const [lastSyncedAt, setLastSyncedAt] = useState<Record<string, string | null>>({});
  const [isSyncing, setIsSyncing] = useState<boolean>(false);

  // Sincroniza a aba ativa quando a rota muda diretamente pela URL ou navegação
  useEffect(() => {
    if (!pathname) return;

    setActiveTabIdState(pathname);
  }, [pathname]);

  function refreshTab(id?: string) {
    const targetId = id || activeTabId;
    setIsSyncing(true);

    const now = new Date().toLocaleTimeString('pt-BR', {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    });

    setRefreshKeys((prev) => ({
      ...prev,
      [targetId]: (prev[targetId] || 0) + 1,
    }));

    setLastSyncedAt((prev) => ({
      ...prev,
      [targetId]: now,
    }));

    setTimeout(() => {
      setIsSyncing(false);
    }, 600);
  }

  function refreshAllTabs() {
    setIsSyncing(true);
    const now = new Date().toLocaleTimeString('pt-BR', {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    });

    setRefreshKeys((prev) => {
      const next = { ...prev };
      tabs.forEach((tab) => {
        next[tab.id] = (next[tab.id] || 0) + 1;
      });
      return next;
    });

    setLastSyncedAt((prev) => {
      const next = { ...prev };
      tabs.forEach((tab) => {
        next[tab.id] = now;
      });
      return next;
    });

    setTimeout(() => {
      setIsSyncing(false);
    }, 600);
  }

  function openTab(newTab: TabItem) {
    setTabs((prevTabs) => {
      const exists = prevTabs.some((t) => t.id === newTab.id);
      if (!exists) {
        return [...prevTabs, newTab];
      }
      return prevTabs;
    });

    setActiveTabIdState(newTab.id);
  }

  function setActiveTabId(id: string) {
    setActiveTabIdState(id);
    // Atualiza a URL sem recarregar o estado
    router.push(id);
  }

  function closeTab(id: string, e?: React.MouseEvent) {
    if (e) {
      e.stopPropagation();
    }

    const targetTab = tabs.find((t) => t.id === id);
    if (targetTab && !targetTab.closable) {
      return; // Abas fixas não podem ser fechadas
    }

    const nextTabs = tabs.filter((t) => t.id !== id);
    setTabs(nextTabs);

    // Se a aba fechada era a ativa, ativa a última aba da lista
    if (activeTabId === id && nextTabs.length > 0) {
      const lastTab = nextTabs[nextTabs.length - 1];
      setActiveTabIdState(lastTab.id);
      router.push(lastTab.id);
    }
  }

  function closeOtherTabs(id: string) {
    setTabs((prevTabs) => prevTabs.filter((t) => !t.closable || t.id === id));
    setActiveTabIdState(id);
    router.push(id);
  }

  function closeAllTabs() {
    setTabs(DEFAULT_TABS);
    setActiveTabIdState('/');
    router.push('/');
  }

  return (
    <TabContext.Provider
      value={{
        tabs,
        activeTabId,
        openTab,
        closeTab,
        setActiveTabId,
        closeOtherTabs,
        closeAllTabs,
        refreshTab,
        refreshAllTabs,
        refreshKeys,
        lastSyncedAt,
        isSyncing,
      }}
    >
      {children}
    </TabContext.Provider>
  );
}

export function useTabContext() {
  const context = useContext(TabContext);
  if (!context) {
    throw new Error('useTabContext deve ser usado dentro de um TabProvider');
  }
  return context;
}
