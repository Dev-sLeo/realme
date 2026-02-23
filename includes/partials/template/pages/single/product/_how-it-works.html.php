<?php
$data = isset($data) && is_array($data) ? $data : get_field('how_it_works');
if (empty($data) || !is_array($data)) {
  return;
}
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$steps = is_array($data['steps'] ?? null) ? $data['steps'] : array();
$cta_primary = is_array($data['cta_primary'] ?? null) ? $data['cta_primary'] : array();
$cta_secondary = is_array($data['cta_secondary'] ?? null) ? $data['cta_secondary'] : array();
?>
<section class="o-product-how-it-works">
  <div class="s-container">
    <header class="o-product-how-it-works__header">
      <?php if (!empty($title)) : ?><h2 class="title__normal"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if (!empty($description)) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($description)); ?></div><?php endif; ?>
    </header>

    <?php if (!empty($steps)) : ?>
      <div class="o-product-how-it-works__grid">
        <?php foreach ($steps as $step) :
          $icon = !empty($step['icon']['ID']) ? (int) $step['icon']['ID'] : 0;
          $step_title = $step['title'] ?? '';
          $step_text = $step['text'] ?? '';
        ?>
          <article class="o-product-card o-product-how-it-works__card">
            <?php if ($icon) : ?><div class="o-product-card__icon"><?php echo wp_get_attachment_image($icon, 'thumbnail', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
            <?php if (!empty($step_title)) : ?><h3 class="o-product-card__title"><?php echo esc_html($step_title); ?></h3><?php endif; ?>
            <?php if (!empty($step_text)) : ?><div class="o-product-card__text"><?php echo wpautop(wp_kses_post($step_text)); ?></div><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($cta_primary['url']) || !empty($cta_secondary['url'])) : ?>
      <div class="o-product-how-it-works__actions">
        <?php if (!empty($cta_primary['url']) && !empty($cta_primary['title'])) : ?>
          <a class="button button__blue" href="<?php echo esc_url($cta_primary['url']); ?>" target="<?php echo esc_attr($cta_primary['target'] ?? '_self'); ?>"><?php echo esc_html($cta_primary['title']); ?></a>
        <?php endif; ?>
        <?php if (!empty($cta_secondary['url']) && !empty($cta_secondary['title'])) : ?>
          <a class="button-border__blue" href="<?php echo esc_url($cta_secondary['url']); ?>" target="<?php echo esc_attr($cta_secondary['target'] ?? '_self'); ?>"><?php echo esc_html($cta_secondary['title']); ?></a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
