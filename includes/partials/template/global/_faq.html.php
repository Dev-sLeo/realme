<?php global $tpl_engine; ?>
<?php
/**
 * Módulo: FAQ
 * Grupo ACF: faq
 * Campos:
 * - sub_title (text)
 * - title (text)
 * - descritopn (textarea)
 * - lista (repeater)
 * - button (link)
 */

$faq = $data ?? get_field('faq');
if (empty($faq)) return;

$sub_title   = $faq['sub_title'] ?? '';
$title       = $faq['title'] ?? '';
$description = $faq['descritopn'] ?? ''; // conforme print
$button      = $faq['button'] ?? null;
$lista       = $faq['lista'] ?? [];

if (empty($title) && empty($sub_title) && empty($description) && empty($lista) && empty($button)) return;

/**
 * Monta itens para o componente global de accordion:
 * Esperado:
 * $items = [
 *  ['icone' => image, 'titulo' => string, 'texto' => html]
 * ]
 *
 * Obs: como os subcampos do repeater não vieram no print, deixei flexível:
 * - titulo: tenta 'titulo' e 'title'
 * - texto: tenta 'texto', 'text', 'description'
 * - icone: tenta 'icone' e 'icon' (pode não existir, vai como null)
 */
$items = [];
if (!empty($lista) && is_array($lista)) {
  foreach ($lista as $row) {
    $q_icon  = $row['icone'] ?? ($row['icon'] ?? null);
    $q_title = $row['pergunta'] ?? ($row['title'] ?? '');
    $q_text  = $row['resposta'] ?? ($row['text'] ?? ($row['description'] ?? ''));

    if (empty($q_title) && empty($q_text)) continue;

    $items[] = [
      'icone'  => null,
      'titulo' => $q_title,
      'texto'  => $q_text,
    ];
  }
}
?>

<section class="o-faq" aria-labelledby="faq-title">
  <div class="s-container">
    <div class="o-faq__container">

      <header class="o-faq__header">
        <?php if (!empty($sub_title)) : ?>
          <p class="o-faq__eyebrow subtitulo"><?= esc_html($sub_title); ?></p>
        <?php endif; ?>

        <?php if (!empty($title)) : ?>
          <h2 class="o-faq__title title__normal" id="faq-title"><?= esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($description)) : ?>
          <div class="o-faq__description text__normal">
            <?= $description ?>
          </div>
        <?php endif; ?>
      </header>

      <?php if (!empty($items)) : ?>
        <div class="o-faq__content">
          <?php
          $tpl_engine->partial(
            'components/accordion/accordion',
            [
              'items' => $items,
            ]
          );
          ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($button) && !empty($button['url'])) : ?>
        <div class="o-faq__footer">
          <a class="o-faq__button button button-border__blue"
            href="<?= esc_url($button['url']); ?>"
            <?= !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>
            aria-label="<?= esc_attr($button['title'] ?? ''); ?>">
            <span class="c-button__label"><?= esc_html($button['title'] ?? ''); ?></span>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>