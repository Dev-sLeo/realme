# agent.md — WordPress Template (padrão Leonardo Pang • tpl_engine • MOBILE-FIRST)

Você é um agente que cria/ajusta templates WordPress seguindo EXATAMENTE a estrutura do Leonardo Pang.

Regras principais:

1. Código pronto para produção
2. Sem comentários no código (só se eu pedir)
3. Seguir tpl_engine
4. Seguir ACF real
5. Seguir naming do projeto
6. OBRIGATÓRIO MOBILE-FIRST em TODO CSS/SCSS/JS

Se qualquer regra for quebrada → resposta está errada.

---

REGRA GLOBAL — MOBILE-FIRST OBRIGATÓRIO

Todo CSS/SCSS deve ser mobile-first.
Nunca começar desktop-first.
Nunca usar max-width como base.
Nunca inverter lógica.

Ordem correta:

1. Mobile base
2. Tablet
3. Desktop
4. Large Desktop

---

NAMING OBRIGATÓRIO

Prefixos:
o- → organismos
c- → componentes
u- → utilidades
is-/has- → estados

Tipografia:
title**normal
text**normal

Botões:
button button\_\_blue
button button

---

ESTRUTURA DO PROJETO

theme/
app/
partials/
assets/src/js
assets/src/scss
assets/dist
webpack/

---

TPL_ENGINE

Render padrão:
$tpl_engine->render('template/pages/HOME/\_hero', ['data' => $hero]);

Dentro do partial:
$data = isset($data) && is_array($data) ? $data : (get_query_var('data') ?: []);
if (empty($data)) return;

---

ACF PRO

Nunca inventar campos.
Sempre usar exatamente os nomes enviados.
Normalizar arrays sempre.

---

WEBPACK

Build obrigatório:
assets/dist/app.js
assets/dist/app.css

Entry JS:
assets/src/js/app.js

---

CHECKLIST FINAL

- MOBILE-FIRST correto
- tpl_engine usado
- Campos ACF corretos
- Classes padrão Leo Pang
- app.js como bundle
- Sem comentários no código
- HTML válido
- Sem max-width como base

---

FIM DO AGENT-WP.TXT
