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

$produtos  = new WP_Query([
  'post_type'      => 'produto',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
]);
$has_cards = $produtos->have_posts();

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
              <?php while ($produtos->have_posts()) : $produtos->the_post();
                $single_card = get_field('single_card') ?: [];
                $raw_list    = is_array($single_card['list'] ?? null) ? $single_card['list'] : [];
                $list        = array_map(fn($row) => ['text' => $row['item'] ?? ''], $raw_list);
                $tpl_engine->partial('components/cards/benefits-slide', [
                  'vars' => [
                    'image' => $single_card['imagem'] ?? null,
                    'title' => get_the_title(),
                    'text'  => $single_card['text'] ?? '',
                    'list'  => $list,
                    'cta'   => ['url' => get_permalink(), 'title' => get_the_title(), 'target' => ''],
                  ],
                ]);
              endwhile;
              wp_reset_postdata(); ?>
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