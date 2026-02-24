<?php
$data = isset($data) && is_array($data) ? $data : get_field('final_cta');
if (empty($data) || !is_array($data)) {
  return;
}
?>
<section class="o-product-final-cta">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-single-cases.webp" alt="">
  </div>
  <div class="s-container">
    <div class="o-product-final-cta__content">
      <div>
        <?php if (!empty($data['title'])) : ?><h2 class="title__normal"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
        <?php if (!empty($data['description'])) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($data['description'])); ?></div><?php endif; ?>
        <div class="o-product-final-cta__actions">
          <?php if (!empty($data['cta_primary']['url']) && !empty($data['cta_primary']['title'])) : ?><a class="button button__blue" href="<?php echo esc_url($data['cta_primary']['url']); ?>" target="<?php echo esc_attr($data['cta_primary']['target'] ?? '_self'); ?>"><?php echo esc_html($data['cta_primary']['title']); ?></a><?php endif; ?>
          <?php if (!empty($data['cta_secondary']['url']) && !empty($data['cta_secondary']['title'])) : ?><a class="button button-border__blue" href="<?php echo esc_url($data['cta_secondary']['url']); ?>" target="<?php echo esc_attr($data['cta_secondary']['target'] ?? '_self'); ?>"><?php echo esc_html($data['cta_secondary']['title']); ?></a><?php endif; ?>
        </div>
        <?php if (!empty($data['bullets']) && is_array($data['bullets'])) : ?>
          <ul class="o-product-final-cta__bullets">
            <?php foreach ($data['bullets'] as $bullet) : if (empty($bullet['text'])) continue; ?><li><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                  <path d="M18.3346 10.0003C18.3346 5.39795 14.6036 1.66699 10.0013 1.66699C5.39893 1.66699 1.66797 5.39795 1.66797 10.0003C1.66797 14.6027 5.39893 18.3337 10.0013 18.3337C14.6036 18.3337 18.3346 14.6027 18.3346 10.0003Z" stroke="#5290A3" stroke-width="1.5" />
                  <path d="M6.66797 10.4167L8.7513 12.5L13.3346 7.5" stroke="#5290A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg><?php echo esc_html($bullet['text']); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <?php if (!empty($data['art']['ID'])) : ?><div class="o-product-final-cta__image"><?php echo wp_get_attachment_image((int) $data['art']['ID'], 'large', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
    </div>
  </div>
</section>