# agent-structure.txt — Estrutura do Template WP (Leonardo Pang • tpl_engine • atualização contínua)

Você é um agente responsável por:
1) Criar a estrutura inicial do template WordPress no padrão Leonardo Pang
2) Manter um arquivo de “mapa de projeto” sempre atualizado conforme o repositório cresce
3) Nunca quebrar o padrão existente (tpl_engine + includes/partials + extension + ACF JSON + Webpack)

REGRAS (obrigatórias)
- Não comentar código (só se o Leonardo pedir)
- Não renomear pastas/arquivos existentes
- Não mover arquivos sem solicitação explícita
- Sempre MOBILE-FIRST (quando envolver CSS/SCSS/JS)
- Sempre validar a base SCSS em webpack/css/sass/base antes de criar estilos novos
- Sempre respeitar a estrutura real de partials e naming (o-/c-/u-)

──────────────────────────────────────────────────────────────
1) ARQUIVOS DE MAPA (sempre criar/manter)

Criar (ou manter) estes arquivos no projeto:

/docs/project-map.md
/docs/structure.txt

Objetivo:
- project-map.md: mapa semântico (onde está cada coisa, entrypoints, padrões)
- structure.txt: árvore de pastas/arquivos (gerada automaticamente)

──────────────────────────────────────────────────────────────
2) COMO GERAR/ATUALIZAR /docs/structure.txt (Windows PowerShell)

Rodar na raiz do tema (onde fica functions.php e webpack.config.js):

tree /F /A > docs/structure.txt

──────────────────────────────────────────────────────────────
3) VERSÃO “CLEAN” (sem node_modules) — recomendada para documentação

Criar também:

/docs/structure.clean.txt

Geração (PowerShell):

$exclude = @('node_modules','.git','vendor','dist','build','.cache')
function Show-Tree($path, $indent='') {
  Get-ChildItem -LiteralPath $path -Force |
    Where-Object { $exclude -notcontains $_.Name } |
    Sort-Object { $_.PSIsContainer } -Descending, Name |
    ForEach-Object {
      if ($_.PSIsContainer) {
        "$indent+-- $($_.Name)/"
        Show-Tree $_.FullName ($indent + '   ')
      } else {
        "$indent|   $($_.Name)"
      }
    }
}
Show-Tree (Get-Location).Path > docs/structure.clean.txt

──────────────────────────────────────────────────────────────
4) ATUALIZAÇÃO CONTÍNUA (sem depender do Leonardo lembrar)

Opção A — Git hook (recomendado)
Criar arquivo:
.git/hooks/pre-commit

Conteúdo (Git Bash):
#!/bin/sh
tree /F /A > docs/structure.txt
exit 0

Opção B — script npm
No package.json, criar:
"scripts": {
  "structure": "tree /F /A > docs/structure.txt"
}

Rodar:
yarn structure

──────────────────────────────────────────────────────────────
5) COMO O AGENTE DEVE VALIDAR A ESTRUTURA ANTES DE CRIAR COISAS NOVAS

Antes de criar qualquer arquivo novo, verificar:

A) Templates WP na raiz
- 404.php, page.php, single.php, front-page.php etc

B) ACF JSON
- /acf-json (grupos e CPT/tax)

C) Core de extensão
- /extension (ajax, helpers, query-filters, theme-customizer)

D) tpl_engine / partials
- /includes/partials/components
- /includes/partials/template (global/header/footer/pages)

E) Webpack
- webpack.config.js (entrada JS/CSS conforme projeto atual)

F) SCSS base
- webpack/css/sass/base (titles, typography, helpers)
- Proibido recriar títulos/tamanhos se já existem na base

──────────────────────────────────────────────────────────────
6) QUANDO A ESTRUTURA CRESCER, COMO O AGENTE DEVE ATUAR

Regra:
- Sempre adicionar novas pastas/arquivos no local certo (não espalhar)
- Sempre atualizar docs/project-map.md e docs/structure*.txt ao final

Exemplos:
- Novo módulo de página: includes/partials/template/pages/<pagina>/_<secao>.html.php
- Novo componente: includes/partials/components/<componente>/_<componente>.html.php
- Novo handler AJAX: extension/ajax/<arquivo>.php
- Novo helper: extension/helpers/<arquivo>.php

FIM
