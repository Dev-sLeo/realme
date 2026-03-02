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
              $cta = is_array($card['cta'] ?? null) ? $card['cta'] : array();
            ?>
              <article class="swiper-slide o-product-benefits__slide">
                <?php if (!empty($card['image']['ID'])) : ?><div class="o-product-benefits__image"><?php echo wp_get_attachment_image((int) $card['image']['ID'], 'large', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
                <?php if (!empty($card['title'])) : ?><h3><?php echo esc_html($card['title']); ?></h3><?php endif; ?>
                <?php if (!empty($card['text'])) : ?><div><?php echo wpautop(wp_kses_post($card['text'])); ?></div><?php endif; ?>
                <?php if (!empty($card['list']) && is_array($card['list'])) : ?>
                  <ul>
                    <?php foreach ($card['list'] as $item) : if (empty($item['text'])) continue; ?><li><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M18.3346 10.0003C18.3346 5.39795 14.6036 1.66699 10.0013 1.66699C5.39893 1.66699 1.66797 5.39795 1.66797 10.0003C1.66797 14.6027 5.39893 18.3337 10.0013 18.3337C14.6036 18.3337 18.3346 14.6027 18.3346 10.0003Z" stroke="#5290A3" stroke-width="1.5" />
                          <path d="M6.66797 10.4167L8.7513 12.5L13.3346 7.5" stroke="#5290A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <?php echo esc_html($item['text']); ?></li><?php endforeach; ?>
                  </ul>
                <?php endif; ?>
                <a class="o-product-benefits__cta button button-border__blue" href="<?php echo esc_url($cta['url']); ?>" target="<?php echo esc_attr($cta['target'] ?? '_self'); ?>"><?php echo 'Ver mais'; ?></a>
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