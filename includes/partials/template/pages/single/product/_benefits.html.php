<?php
$data = isset($data) && is_array($data) ? $data : get_field('benefits');
if (empty($data) || !is_array($data)) {
  return;
}
$cards = is_array($data['cards'] ?? null) ? $data['cards'] : array();
?>
<section class="o-product-benefits">
  <div class="s-container">
    <header class="o-product-benefits__header">
      <?php if (!empty($data['title'])) : ?><h2 class="title__normal"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
      <?php if (!empty($data['description'])) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($data['description'])); ?></div><?php endif; ?>
    </header>

    <?php if (!empty($cards)) : ?>
      <div class="o-product-benefits__slider">
        <div class="swiper o-product-benefits__swiper" data-swiper="product-benefits">
          <div class="swiper-wrapper">
            <?php foreach ($cards as $card) :
              $cta = is_array($card['cta'] ?? null) ? $card['cta'] : array();
            ?>
              <article class="swiper-slide o-product-benefits__slide">
                <?php if (!empty($card['image']['ID'])) : ?><div class="o-product-benefits__image"><?php echo wp_get_attachment_image((int) $card['image']['ID'], 'large', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
                <?php if (!empty($card['title'])) : ?><h3><?php echo esc_html($card['title']); ?></h3><?php endif; ?>
                <?php if (!empty($card['text'])) : ?><div><?php echo wpautop(wp_kses_post($card['text'])); ?></div><?php endif; ?>
                <?php if (!empty($card['list']) && is_array($card['list'])) : ?>
                  <ul>
                    <?php foreach ($card['list'] as $item) : if (empty($item['text'])) continue; ?>
                      <li><?php echo esc_html($item['text']); ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
                <?php if (!empty($cta['url']) && !empty($cta['title'])) : ?>
                  <a class="o-product-benefits__cta" href="<?php echo esc_url($cta['url']); ?>" target="<?php echo esc_attr($cta['target'] ?? '_self'); ?>"><?php echo esc_html($cta['title']); ?></a>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
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
