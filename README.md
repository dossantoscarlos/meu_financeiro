# Controle Financeiro Pessoal

## Introdução

Este projeto é um controle financeiro pessoal desenvolvido para uso diário, com o objetivo de fornecer um controle detalhado das finanças, identificando onde estão os principais gastos e como otimizá-los a curto e longo prazo. O projeto está em constante desenvolvimento, ajustando-se continuamente à realidade do usuário e ganhando novas funcionalidades e correções regularmente. O código-fonte está disponível publicamente para que qualquer pessoa possa utilizá-lo e adaptá-lo à sua própria realidade financeira.

## Tecnologias Utilizadas

- **PHP 8.1+**: Linguagem de programação principal.
- **Laravel 11**: Framework PHP utilizado para o desenvolvimento do projeto.
- **Filamentphp**: Utilizado para a interface de usuário.
- **Octane**: Biblioteca para .
- [**FrankenPHP**](https://github.com/dunglas/frankenphp): É um servidor de aplicativos moderno para PHP construído sobre o servidor web Caddy.
- **Horizon**: Utilizado para monitoramento de métricas.
- **Telescope**: Ferramenta de debug e monitoramento.

## Pontos Técnicos

O sistema possui algumas características técnicas importantes:

- **Event Listeners**: Implementados utilizando a capacidade de ouvir eventos no Laravel. Isso permite executar ações personalizadas em resposta a eventos específicos, como salvar uma despesa, sem manter o usuário na tela ou a necessidade de criar Jobs.
- **Observable**: Utilizado para monitorar eventos de um CRUD, permitindo a execução de ações personalizadas em resposta a esses eventos.
- **Custom Rules**: Foram criadas regras personalizadas para estender a validação no Filamentphp.
- **Internationalization**: Mensagens do Laravel foram internacionalizadas para facilitar a tradução.

## Funcionalidades

O sistema consiste nas seguintes funções principais:

- **Despesa**: Utilizada para registrar todas as contas, sejam elas já pagas ou pendentes.
- **Status de Despesa**: Define o status da despesa, como "a pagar" ou "pago".
- **Tipo de Despesa**: Informa o tipo da despesa, como mercado, transporte, etc.
- **Produto**: Cataloga os produtos adquiridos, visando futuras análises de gastos por categoria.
- **Plano**: Registra os planos mensais, com a intenção de fornecer análises de custo mensal no futuro.

## Futuro do Projeto

O escopo do projeto continuará a crescer e se tornar mais complexo ao longo do tempo, com atualizações que explicarão cada nova abordagem técnica escolhida. Sua finalidade é oferecer um controle financeiro completo e adaptável às necessidades individuais dos usuários.

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para reportar problemas, sugerir melhorias ou contribuir diretamente para o desenvolvimento do projeto.

## Diagramas

### Caso de uso

![Caso de uso](https://github.com/dossantoscarlos/meu_financeiro/blob/test/doc/UseCase%20Diagram0.png?raw=true)

### Diagrama de classe

![Diagrama de classe](https://github.com/dossantoscarlos/meu_financeiro/blob/test/doc/Class%20Diagram0.png?raw=true)
