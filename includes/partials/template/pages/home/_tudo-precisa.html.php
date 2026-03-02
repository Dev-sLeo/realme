<?php

/**
 * Home - Seção: Tudo que o atendimento precisa
 * Arquivo: partials/template/pages/PAGINA/_section-tudo-precisa.html.php
 *
 * Campos (conforme prints ACF):
 * - section_tudo_precisa (group)
 *   - sub_titulo (text)        [pode conter HTML]
 *   - title (text)             [pode conter HTML]
 *   - text (textarea)          [pode conter HTML]
 *   - items (repeater)
 *     - title (text)           [pode conter HTML]
 *     - text (textarea)        [pode conter HTML]
 *     - button (link)
 *     - imagem (image)
 */

$data = get_field('section_tudo_precisa');
$data = is_array($data) ? $data : [];

if (empty($data)) {
  return;
}

$sub_title = isset($data['sub_titulo']) ? (string) $data['sub_titulo'] : '';
$title     = isset($data['title']) ? (string) $data['title'] : '';
$text      = isset($data['text']) ? (string) $data['text'] : '';
$items     = (!empty($data['items']) && is_array($data['items'])) ? $data['items'] : [];

$has_top   = ($sub_title !== '' || $title !== '' || $text !== '');
$has_items = !empty($items);

if (!$has_top && !$has_items) {
  return;
}
?>

<section class="o-tudo-precisa" aria-label="Tudo que o atendimento precisa">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-hero-header.webp" alt="">
  </div>
  <div class="s-container">

    <?php if ($has_top) : ?>
      <header class="o-tudo-precisa__header">
        <?php if ($sub_title !== '') : ?>
          <div class="o-tudo-precisa__eyebrow subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo $sub_title; ?></div>
        <?php endif; ?>

        <?php if ($title !== '') : ?>
          <h2 class="o-tudo-precisa__title title__normal" data-animate="fade-up" data-animate-delay="0.15"><?php echo $title; ?></h2>
        <?php endif; ?>

        <?php if ($text !== '') : ?>
          <div class="o-tudo-precisa__text text__normal"><?php echo $text; ?></div>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <?php if ($has_items) : ?>
      <div class="o-tudo-precisa__grid" role="list">
        <?php foreach ($items as $i => $item) :
          if (!is_array($item)) continue;

          $it_title = isset($item['title']) ? (string) $item['title'] : '';
          $it_text  = isset($item['text']) ? (string) $item['text'] : '';
          $button   = (!empty($item['button']) && is_array($item['button'])) ? $item['button'] : null;
          $image    = (!empty($item['imagem']) && is_array($item['imagem'])) ? $item['imagem'] : null;

          $btn_has  = (!empty($button['url']));
          $btn_url  = $btn_has ? (string) $button['url'] : '';
          $btn_lbl  = ($btn_has && !empty($button['title'])) ? (string) $button['title'] : '';
          $btn_tgt  = ($btn_has && !empty($button['target'])) ? (string) $button['target'] : '_self';
          $btn_rel  = ($btn_has && $btn_tgt === '_blank') ? 'noopener noreferrer' : '';

          $img_id   = (!empty($image['ID'])) ? (int) $image['ID'] : 0;
          $img_alt  = (!empty($image['alt'])) ? (string) $image['alt'] : '';

          $has_card = ($it_title !== '' || $it_text !== '' || $btn_has || $img_id);
          if (!$has_card) continue;
        ?>
          <article class="c-tudo-card o-tudo-precisa__card" role="listitem" data-animate="fade-up" data-animate-delay="<?php echo 0.2 + ($i * 0.05); ?>">
            <div class="c-tudo-card__body">
              <?php if ($it_title !== '') : ?>
                <h3 class="c-tudo-card__title"><?php echo $it_title; ?></h3>
              <?php endif; ?>

              <?php if ($it_text !== '') : ?>
                <div class="c-tudo-card__text"><?php echo $it_text; ?></div>
              <?php endif; ?>

              <?php if ($btn_has) : ?>
                <div class="c-tudo-card__actions">
                  <a class="button button-border__blue"
                    href="<?php echo $btn_url; ?>"
                    target="<?php echo $btn_tgt; ?>" <?php echo $btn_rel ? ' rel="' . $btn_rel . '"' : ''; ?>>
                    <?php echo $btn_lbl; ?>
                  </a>
                </div>
              <?php endif; ?>
            </div>

            <?php if ($img_id) : ?>
              <div class="c-tudo-card__media">
                <?php
                echo wp_get_attachment_image(
                  $img_id,
                  'large',
                  false,
                  [
                    'class'    => 'c-tudo-card__image',
                    'alt'      => $img_alt,
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                  ]
                );
                ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>