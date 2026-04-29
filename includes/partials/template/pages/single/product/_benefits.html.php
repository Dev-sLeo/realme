<?php global $tpl_engine; ?>
<?php
$data = isset($data) && is_array($data) ? $data : get_field('benefits');
if (empty($data) || !is_array($data)) {
  return;
}
$cards = is_array($data['cards'] ?? null) ? $data['cards'] : array();
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
    <?php if (!empty($cards)) : ?>
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
</section>