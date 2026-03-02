<?php
$data = isset($data) && is_array($data) ? $data : get_field('technology');
if (empty($data) || !is_array($data)) {
  return;
}
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$cards = is_array($data['cards'] ?? null) ? $data['cards'] : array();
$stats = is_array($data['stats'] ?? null) ? $data['stats'] : array();
?>
<section class="o-product-technology">

  <div class="s-container">
    <header class="o-product-technology__header">
      <?php if (!empty($title)) : ?><h2 class="title__normal"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if (!empty($description)) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($description)); ?></div><?php endif; ?>
    </header>
    <?php if (!empty($cards)) : ?>
      <div class="o-product-technology__cards">
        <?php foreach ($cards as $card) : ?>
          <article class="o-product-card">
            <?php if (!empty($card['icon']['ID'])) : ?><div class="o-product-card__icon"><?php echo wp_get_attachment_image((int) $card['icon']['ID'], 'thumbnail', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
            <?php if (!empty($card['title'])) : ?><h3 class="o-product-card__title"><?php echo esc_html($card['title']); ?></h3><?php endif; ?>
            <?php if (!empty($card['text'])) : ?><div class="o-product-card__text"><?php echo wpautop(wp_kses_post($card['text'])); ?></div><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($stats)) : ?>
      <div class="o-product-technology__stats">
        <div class="hero-bg">
          <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-tecnologia.webp" alt="">
        </div>
        <?php foreach ($stats as $stat) : ?>
          <div class="o-product-stat">
            <?php if (!empty($stat['value'])) : ?><strong class="o-product-stat__value"><?php echo esc_html($stat['value']); ?></strong><?php endif; ?>
            <?php if (!empty($stat['label'])) : ?><span class="o-product-stat__label"><?php echo esc_html($stat['label']); ?></span><?php endif; ?>
            <?php if (!empty($stat['value'])) : ?>
              <div class="o-product-stat__bar js-stat-bar" data-value="<?php echo esc_attr($stat['value']); ?>">
                <span class="o-product-stat__bar-value" style="width: <?php echo esc_attr($stat['value']); ?>;"></span>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>