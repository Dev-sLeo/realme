<?php global $tpl_engine; ?>
<?php

/**
 * Módulo: Por que nós
 * Grupo ACF (dentro do group_home_modulos): section_about
 */

$data = isset($data) && is_array($data) ? $data : get_field('section_about');
if (empty($data) || !is_array($data)) {
  return;
}

$sub_titulo = isset($data['sub_titulo']) ? trim((string) $data['sub_titulo']) : '';
$title      = isset($data['title']) ? trim((string) $data['title']) : '';
$text       = isset($data['text']) ? (string) $data['text'] : '';

$image = isset($data['image']) ? $data['image'] : null;
$image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;

$cards = (isset($data['cards']) && is_array($data['cards'])) ? $data['cards'] : [];
$has_cards = !empty($cards);

if (!$sub_titulo && !$title && !trim($text) && !$image_id && !$has_cards) {
  return;
}
?>

<section class="o-about" aria-label="<?php echo esc_attr($title ?: 'Sobre'); ?>">
  <div class="s-container">
    <div class="o-about__container">

      <div class="o-about__grid">

        <div class="o-about__content">
          <?php if ($sub_titulo) : ?>
            <p class="o-about__eyebrow subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($sub_titulo); ?></p>
          <?php endif; ?>

          <?php if ($title) : ?>
            <h2 class="o-about__title title__normal"><?php echo esc_html($title); ?></h2>
          <?php endif; ?>

          <?php if (trim($text)) : ?>
            <div class="o-about__text text__noraml" data-animate="fade-up" data-animate-delay="0.15">
              <?= $text ?>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($image_id) : ?>
          <div class="o-about__media" data-animate="fade-up" data-animate-delay="0.2">
            <?php
            echo wp_get_attachment_image(
              $image_id,
              'full',
              false,
              array(
                'class'   => 'o-about__image',
                'loading' => 'lazy',
              )
            );
            ?>
          </div>
        <?php endif; ?>

      </div>

      <?php if ($has_cards) : ?>
        <div class="o-product-benefits__slider" data-animate="fade-up" data-animate-delay="0.4">
          <div class="swiper o-product-benefits__swiper" data-swiper="product-benefits">
            <div class="swiper-wrapper">
              <?php foreach ($cards as $card) :
                $tpl_engine->partial('components/cards/benefits-slide', [
                  'vars' => [
                    'image' => $card['image'] ?? null,
                    'title' => $card['title'] ?? '',
                    'text'  => $card['text'] ?? '',
                    'list'  => $card['list'] ?? [],
                    'cta'   => $card['cta'] ?? [],
                  ],
                ]);
              endforeach; ?>
            </div>
            <div class="o-product-benefits__arrows">
              <button class="o-product-benefits__arrow o-product-benefits__arrow--prev" type="button" aria-label="Anterior"></button>
              <button class="o-product-benefits__arrow o-product-benefits__arrow--next" type="button" aria-label="Próximo"></button>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>