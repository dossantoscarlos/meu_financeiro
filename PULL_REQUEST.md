feat: Configuração inicial de governança e padrões de contribuição

## Descrição

Explique objetivamente **o que foi feito** e **por quê**.  
Contexto importa. PR sem contexto atrasa review.

Este Pull Request é abrangente e foca em estabelecer a base para a governança e padronização de contribuições no projeto. As principais mudanças incluem:

- **Criação de Templates:** Foram adicionados templates para Issues (`.github/ISSUE_TEMPLATE.MD`) e Pull Requests (`.github/PULL_REQUEST_TEMPLATE.MD`) para padronizar a criação e o fluxo de trabalho.
- **Código de Conduta:** O arquivo `CODE_OF_CONDUCT.md` foi criado para definir as regras de conduta e garantir um ambiente de colaboração positivo.
- **Guia de Contribuição:** O `CONTRIBUTING.md` foi adicionado para guiar novos colaboradores sobre como contribuir, incluindo fluxo de desenvolvimento, padrão de código, commits e testes.
- **Atualização do Modelo Despesa:** No arquivo `app/Models/Despesa.php`, foram adicionados docblocks para melhor documentação das propriedades da classe e removidas algumas definições de `protected $casts` e o relacionamento `historico()`.
- **Adição de `query_page.json`:** Um novo arquivo `query_page.json` foi incluído na raiz do projeto, contendo uma lista extensa de "POLOs".

---

## Tipo de mudança

Marque o que se aplica:

- [ ] Bug fix (correção sem quebrar compatibilidade)
- [x] Nova feature (governança do projeto, templates, documentação inicial)
- [x] Refatoração (no modelo Despesa)
- [x] Documentação (CÓDIGO DE CONDUTA, CONTRIBUINDO, templates)
- [ ] Testes
- [ ] Outro (descreva): Adição de dados (query_page.json)

---

## Problema relacionado

- Issue relacionada: #___ (Se houver uma issue que englobe essas mudanças, mencione-a aqui)
- Não existe issue prévia: [ ] Sim [x] Não  
  > PR grande sem issue pode ser fechado.

---

## Como testar

Descreva **passo a passo** como validar a mudança:

1.  **Verificação de Arquivos:** Confirme a presença dos novos arquivos:
    - `.github/ISSUE_TEMPLATE.MD`
    - `.github/PULL_REQUEST_TEMPLATE.MD`
    - `CODE_OF_CONDUCT.md`
    - `CONTRIBUTING.md`
    - `PULL_REQUEST.md` (este arquivo)
    - `query_page.json`
2.  **Revisão de Conteúdo:** Leia o conteúdo de cada um desses arquivos para garantir que as informações estão corretas e fazem sentido para o projeto.
3.  **Análise do Modelo Despesa:** Verifique se as alterações no `app/Models/Despesa.php` (docblocks e remoção de `casts`/`historico`) não causaram regressões ou erros na aplicação. (Pode ser necessário executar testes unitários ou de integração relacionados a este modelo).

Se não houver testes, justifique.

---

## Checklist

Confirme antes de pedir review:

- [x] Código segue o padrão do projeto
- [ ] Testes adicionados ou atualizados (Apenas para as mudanças no modelo Despesa, se aplicável)
- [x] Lint passa sem erros
- [x] Documentação atualizada (se aplicável)
- [x] Não inclui código morto ou debug
- [x] Não quebra compatibilidade sem aviso

---

## Impacto

- Quebra compatibilidade? [ ] Sim [x] Não  
- Impacto em performance? [ ] Sim [x] Não  
- Impacto em segurança? [ ] Sim [x] Não  

Explique se marcou "Sim". As mudanças no modelo `Despesa.php` devem ser revisadas para garantir que não haja impactos inesperados.

---

## Observações adicionais

Este PR visa aprimorar a organização e a forma como as contribuições são gerenciadas no projeto.
