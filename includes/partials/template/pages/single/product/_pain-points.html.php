<?php
$data = isset($data) && is_array($data) ? $data : get_field('pain_points');
if (empty($data) || !is_array($data)) {
  return;
}
$title = $data['title'] ?? '';
$subtitle = $data['eyebrown'] ?? '';
$description = $data['description'] ?? '';
$cards = !empty($data['cards']) && is_array($data['cards']) ? $data['cards'] : array();
if (empty($title) && empty($description) && empty($cards)) {
  return;
}
?>
<section class="o-product-pain-points">
  <div class="s-container">
    <?php if (!empty($title) || !empty($description)) : ?>
      <header class="o-product-pain-points__header">
        <?php if (!empty($subtitle)) : ?>
          <span class="subtitulo"><?php echo esc_html($subtitle); ?></span>
        <?php endif; ?>
        <?php if (!empty($title)) : ?><h2 class="title__normal"><?php echo esc_html($title); ?></h2><?php endif; ?>
        <?php if (!empty($description)) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($description)); ?></div><?php endif; ?>
      </header>
    <?php endif; ?>
    <?php if (!empty($cards)) : ?>
      <div class="o-product-pain-points__grid">
        <?php foreach ($cards as $card) :
          $icon = !empty($card['icon']['ID']) ? (int) $card['icon']['ID'] : 0;
          $card_title = $card['title'] ?? '';
          $card_text = $card['text'] ?? '';
          if (empty($icon) && empty($card_title) && empty($card_text)) {
            continue;
          }
        ?>
          <article class="o-product-card">
            <?php if (!empty($icon)) : ?><div class="o-product-card__icon"><?php echo wp_get_attachment_image($icon, 'thumbnail', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
            <?php if (!empty($card_title)) : ?><h3 class="o-product-card__title"><?php echo esc_html($card_title); ?></h3><?php endif; ?>
            <?php if (!empty($card_text)) : ?><div class="o-product-card__text"><?php echo wpautop(wp_kses_post($card_text)); ?></div><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>