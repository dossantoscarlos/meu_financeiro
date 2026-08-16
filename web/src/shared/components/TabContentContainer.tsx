'use client';

import React from 'react';
import { useTabContext } from '@/shared/context/TabContext';
import OverviewDashboard from '@/app/(dashboard)/page';
import ReceitaModule from '@/modules/receita/components/ReceitaModule';
import DespesasModule from '@/modules/despesas/components/DespesasModule';
import PagamentosModule from '@/modules/pagamentos/components/PagamentosModule';
import ProdutoModule from '@/modules/produto/components/ProdutoModule';
import { SqlConsoleModule } from '@/modules/tools/components/SqlConsoleModule';
import UsersModule from '@/modules/users/components/UsersModule';
import RelatoriosModule from '@/modules/relatorios/components/RelatoriosModule';

const ROUTE_COMPONENT_MAP: Record<string, React.ComponentType> = {
  '/': OverviewDashboard,
  '/receita': ReceitaModule,
  '/rendas': ReceitaModule,
  '/despesas': DespesasModule,
  '/pagamentos': PagamentosModule,
  '/planos': PagamentosModule,
  '/caixinhas': PagamentosModule,
  '/produto': ProdutoModule,
  '/produtos': ProdutoModule,
  '/sql-console': SqlConsoleModule,
  '/users': UsersModule,
  '/relatorios': RelatoriosModule,
};

export function TabContentContainer({ children }: { children: React.ReactNode }) {
  const { tabs, activeTabId, refreshKeys } = useTabContext();

  return (
    <div className="relative w-full h-full">
      {tabs.map((tab) => {
        const Component = ROUTE_COMPONENT_MAP[tab.id];
        const isActive = activeTabId === tab.id;
        const keyVal = `${tab.id}-${refreshKeys[tab.id] || 0}`;

        if (!Component) {
          return (
            <div key={keyVal} className={isActive ? 'block' : 'hidden'}>
              {children}
            </div>
          );
        }

        return (
          <div key={keyVal} className={isActive ? 'block animate-fadeIn' : 'hidden'}>
            <Component />
          </div>
        );
      })}
    </div>
  );
}
