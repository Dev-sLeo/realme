<?php

/**
 * Módulo: Faça mais negócios (CTA final)
 * Grupo ACF (dentro do group_home_modulos): final_cta
 */

$data = isset($data) && is_array($data) ? $data : get_field('final_cta');
if (empty($data) || !is_array($data)) {
  return;
}

$title = isset($data['title']) ? trim((string) $data['title']) : '';
$text  = isset($data['text']) ? (string) $data['text'] : '';

$button_primary   = isset($data['button_primary']) ? $data['button_primary'] : null;
$button_secondary = isset($data['button_secondary']) ? $data['button_secondary'] : null;

$image = isset($data['image']) ? $data['image'] : null;
$image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;

$has_primary = is_array($button_primary) && !empty($button_primary['url']) && !empty($button_primary['title']);
$has_secondary = is_array($button_secondary) && !empty($button_secondary['url']) && !empty($button_secondary['title']);

if (!$title && !trim($text) && !$has_primary && !$has_secondary && !$image_id) {
  return;
}

$primary_target = $has_primary ? (!empty($button_primary['target']) ? $button_primary['target'] : '_self') : '_self';
$secondary_target = $has_secondary ? (!empty($button_secondary['target']) ? $button_secondary['target'] : '_self') : '_self';
?>

<section class="o-final-cta" aria-label="<?php echo esc_attr($title ?: 'Chamada final'); ?>">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-hero-header.webp" alt="">
  </div>
  <div class="s-container">
    <div class="o-final-cta__container">

      <div class="o-final-cta__grid">

        <div class="o-final-cta__content">
          <?php if ($title) : ?>
            <h2 class="o-final-cta__title title__normal" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($title); ?></h2>
          <?php endif; ?>

          <?php if (trim($text)) : ?>
            <div class="o-final-cta__text text__normal" data-animate="fade-up" data-animate-delay="0.15">
              <?= $text ?>
            </div>
          <?php endif; ?>

          <?php if ($has_primary || $has_secondary) : ?>
            <div class="o-final-cta__actions" role="group" aria-label="Ações" data-animate="fade-up" data-animate-delay="0.2">
              <?php if ($has_primary) : ?>
                <a
                  class="button button__blue c-button--primary"
                  href="<?php echo esc_url($button_primary['url']); ?>"
                  target="<?php echo esc_attr($primary_target); ?>"
                  <?php echo ($primary_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($button_primary['title']); ?>
                </a>
              <?php endif; ?>

              <?php if ($has_secondary) : ?>
                <a
                  class="button button-border__blue c-button--secondary"
                  href="<?php echo esc_url($button_secondary['url']); ?>"
                  target="<?php echo esc_attr($secondary_target); ?>"
                  <?php echo ($secondary_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($button_secondary['title']); ?>
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($image_id) : ?>
          <div class="o-final-cta__media" data-animate="fade-up" data-animate-delay="0.25">
            <?php
            echo wp_get_attachment_image(
              $image_id,
              'large',
              false,
              array(
                'class'   => 'o-final-cta__image',
                'loading' => 'lazy',
              )
            );
            ?>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</section>