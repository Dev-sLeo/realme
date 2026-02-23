<?php
$data = isset($data) && is_array($data) ? $data : get_field('final_cta');
if (empty($data) || !is_array($data)) {
  return;
}
?>
<section class="o-product-final-cta">
  <div class="s-container">
    <div class="o-product-final-cta__content">
      <div>
        <?php if (!empty($data['title'])) : ?><h2 class="title__normal"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
        <?php if (!empty($data['description'])) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($data['description'])); ?></div><?php endif; ?>

        <div class="o-product-final-cta__actions">
          <?php if (!empty($data['cta_primary']['url']) && !empty($data['cta_primary']['title'])) : ?>
            <a class="button button__blue" href="<?php echo esc_url($data['cta_primary']['url']); ?>" target="<?php echo esc_attr($data['cta_primary']['target'] ?? '_self'); ?>"><?php echo esc_html($data['cta_primary']['title']); ?></a>
          <?php endif; ?>
          <?php if (!empty($data['cta_secondary']['url']) && !empty($data['cta_secondary']['title'])) : ?>
            <a class="button-border__blue" href="<?php echo esc_url($data['cta_secondary']['url']); ?>" target="<?php echo esc_attr($data['cta_secondary']['target'] ?? '_self'); ?>"><?php echo esc_html($data['cta_secondary']['title']); ?></a>
          <?php endif; ?>
        </div>

        <?php if (!empty($data['bullets']) && is_array($data['bullets'])) : ?>
          <ul class="o-product-final-cta__bullets">
            <?php foreach ($data['bullets'] as $bullet) : if (empty($bullet['text'])) continue; ?>
              <li><?php echo esc_html($bullet['text']); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <?php if (!empty($data['art']['ID'])) : ?>
        <div class="o-product-final-cta__image"><?php echo wp_get_attachment_image((int) $data['art']['ID'], 'large', false, array('loading' => 'lazy')); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>
