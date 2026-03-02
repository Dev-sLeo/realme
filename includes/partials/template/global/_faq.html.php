<?php global $tpl_engine; ?>
<?php
$faq = $data ?? get_field('faq');
if (empty($faq)) return;

$sub_title   = $faq['sub_title'] ?? '';
$title       = $faq['title'] ?? '';
$description = $faq['descritopn'] ?? '';
$button      = $faq['button'] ?? null;
$lista       = $faq['lista'] ?? [];

if (empty($title) && empty($sub_title) && empty($description) && empty($lista) && empty($button)) return;

$items = [];
if (!empty($lista) && is_array($lista)) {
  foreach ($lista as $row) {
    $q_title = $row['pergunta'] ?? ($row['title'] ?? '');
    $q_text  = $row['resposta'] ?? ($row['text'] ?? ($row['description'] ?? ''));
    if (empty($q_title) && empty($q_text)) continue;

    $items[] = [
      'titulo' => $q_title,
      'texto'  => $q_text,
    ];
  }
}

$total_items = count($items);
$initial_limit = 5;
?>

<section class="o-faq js-faq" aria-labelledby="faq-title">
  <div class="s-container">
    <div class="o-faq__container">

      <header class="o-faq__header">
        <?php if (!empty($sub_title)) : ?>
          <p class="o-faq__eyebrow subtitulo" data-animate="fade-up"
            data-animate-delay="0.1"><?= esc_html($sub_title); ?></p>
        <?php endif; ?>

        <?php if (!empty($title)) : ?>
          <h2 class="o-faq__title title__normal" id="faq-title" data-animate="fade-up" data-animate-delay="0.2"><?= esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($description)) : ?>
          <div class="o-faq__description text__normal" data-animate="fade-up" data-animate-delay="0.3">
            <?= $description ?>
          </div>
        <?php endif; ?>
      </header>

      <?php if (!empty($items)) : ?>
        <div class="o-faq__content js-faq-list" data-animate="fade-up"
          data-animate-delay="0.25">
          <?php foreach ($items as $index => $item) : ?>
            <div class="o-faq__item js-faq-item" <?= $index >= $initial_limit ? 'hidden' : ''; ?>>
              <?php $tpl_engine->partial('components/accordion/accordion', ['items' => $items,]); ?>
            </div>
          <?php endforeach; ?>
        </div>

        <?php if ($total_items > $initial_limit) : ?>
          <div class="o-faq__footer" data-animate="fade-up"
            data-animate-delay="0.3">
            <button
              type="button"
              class="o-faq__button button button-border__blue js-faq-load-more"
              data-step="5">
              <span class="c-button__label">Carregar mais</span>
            </button>
          </div>
        <?php endif; ?>
      <?php endif; ?>

    </div>
  </div>
</section>