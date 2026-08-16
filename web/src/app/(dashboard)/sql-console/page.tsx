import { SqlConsoleModule } from '@/modules/tools/components/SqlConsoleModule';

export const metadata = {
  title: 'Console SQL • Meu Financeiro',
  description: 'Executar queries SQL diretas no banco de dados da aplicação',
};

export default function SqlConsolePage() {
  return <SqlConsoleModule />;
}
