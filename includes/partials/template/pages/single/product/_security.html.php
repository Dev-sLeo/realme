<?php
$data = isset($data) && is_array($data) ? $data : get_field('security');
if (empty($data) || !is_array($data)) {
  return;
}
?>
<section class="o-product-security">
  <div class="s-container">
    <header class="o-product-security__header">
      <?php if (!empty($data['title'])) : ?><h2 class="title__normal"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
      <?php if (!empty($data['description'])) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($data['description'])); ?></div><?php endif; ?>
    </header>

    <div class="o-product-security__content">
      <?php if (!empty($data['bullets']) && is_array($data['bullets'])) : ?>
        <ul class="o-product-security__bullets">
          <?php foreach ($data['bullets'] as $bullet) :
            if (empty($bullet['text'])) {
              continue;
            }
          ?>
            <li><?php echo esc_html($bullet['text']); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if (!empty($data['mockup']['ID'])) : ?>
        <div class="o-product-security__mockup"><?php echo wp_get_attachment_image((int) $data['mockup']['ID'], 'large', false, array('loading' => 'lazy')); ?></div>
      <?php endif; ?>
    </div>

    <?php if (!empty($data['cards']) && is_array($data['cards'])) : ?>
      <div class="o-product-security__cards">
        <?php foreach ($data['cards'] as $card) : ?>
          <article class="o-product-card">
            <?php if (!empty($card['icon']['ID'])) : ?><div class="o-product-card__icon"><?php echo wp_get_attachment_image((int) $card['icon']['ID'], 'thumbnail', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
            <?php if (!empty($card['title'])) : ?><h3 class="o-product-card__title"><?php echo esc_html($card['title']); ?></h3><?php endif; ?>
            <?php if (!empty($card['text'])) : ?><div class="o-product-card__text"><?php echo wpautop(wp_kses_post($card['text'])); ?></div><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
