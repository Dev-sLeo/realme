<?php global $tpl_engine; ?>
<?php
$data = isset($data) && is_array($data) ? $data : get_field('benefits');
if (empty($data) || !is_array($data)) {
  return;
}

$produtos = new WP_Query([
  'post_type'      => 'produto',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
]);
$has_cards = $produtos->have_posts();
?>
<section class="o-product-benefits">

  <header class="o-product-benefits__header">
    <div class="s-container">
      <?php if (!empty($data['eyebrown'])) : ?>
        <span class="subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($data['eyebrown']); ?></span>
      <?php endif; ?>

      <?php if (!empty($data['title'])) : ?>
        <h2 class="title__normal" data-animate="fade-up" data-animate-delay="0.2"><?php echo esc_html($data['title']); ?></h2>
      <?php endif; ?>

      <?php if (!empty($data['description'])) : ?>
        <div class="text__normal" data-animate="fade-up" data-animate-delay="0.3"><?php echo wpautop(wp_kses_post($data['description'])); ?></div>
      <?php endif; ?>
    </div>
    <div class="o-product-benefits__scroll" aria-hidden="true">
      <?= $tpl_engine->svg('icons/arrow-down-section'); ?>
    </div>
  </header>
  <div class="s-container">
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
</section>