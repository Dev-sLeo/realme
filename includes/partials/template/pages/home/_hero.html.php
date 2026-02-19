<?php

$hero = get_field('hero', get_the_ID());
$hero = is_array($hero) ? $hero : [];

if (empty($hero)) {
  return;
}

// Normaliza campos
$title         = isset($hero['title']) ? trim((string) $hero['title']) : '';
$subtitle      = isset($hero['subtitle']) ? trim((string) $hero['subtitle']) : '';
$description   = isset($hero['description']) ? trim((string) $hero['description']) : '';
$btn_primary   = (!empty($hero['button_primary']) && is_array($hero['button_primary'])) ? $hero['button_primary'] : null;
$btn_secondary = (!empty($hero['button_secondary']) && is_array($hero['button_secondary'])) ? $hero['button_secondary'] : null;
$mockup        = (!empty($hero['mockup_image']) && is_array($hero['mockup_image'])) ? $hero['mockup_image'] : null;
$metrics       = (!empty($hero['metrics']) && is_array($hero['metrics'])) ? $hero['metrics'] : [];

if (!$title && !$subtitle && !$btn_primary && !$btn_secondary && empty($metrics) && empty($mockup)) {
  return;
}

// Helper de botão ACF link
$render_btn = function ($link, $extra_class = '') {
  if (empty($link['url'])) return '';

  $url    = esc_url($link['url']);
  $label  = !empty($link['title']) ? esc_html($link['title']) : esc_html__('Saiba mais', 'textdomain');
  $target = !empty($link['target']) ? esc_attr($link['target']) : '_self';
  $rel    = ($target === '_blank') ? 'noopener noreferrer' : '';

  return sprintf(
    '<a class="%s" href="%s" target="%s"%s>%s</a>',
    esc_attr(trim($extra_class)),
    $url,
    $target,
    $rel ? ' rel="' . esc_attr($rel) . '"' : '',
    $label
  );
};

$mockup_id  = (!empty($mockup['ID'])) ? (int) $mockup['ID'] : 0;
$mockup_alt = (!empty($mockup['alt'])) ? (string) $mockup['alt'] : ($title ?: '');
?>

<section class="o-hero" aria-label="<?php echo esc_attr__('Seção principal', 'textdomain'); ?>">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-hero-header.webp" alt="">
  </div>
  <div class="s-container">
    <div class="o-hero__grid">

      <div class="o-hero__content">
        <?php if ($subtitle) : ?>
          <div class="o-hero__subtitle" data-animate="fade-up" data-animate-duration="0.9"
            data-animate-delay="0.1">
            <?= $subtitle ?>
          </div>
        <?php endif; ?>

        <?php if ($title) : ?>
          <h1 class="o-hero__title title__super" data-animate="fade-up" data-animate-duration="0.9"
            data-animate-delay="0.15"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>

        <?php if ($description) : ?>
          <div class="o-hero__description text__normal" data-animate="fade-up" data-animate-duration="0.9"
            data-animate-delay="0.2">
            <?= $description  ?>
          </div>
        <?php endif; ?>


        <?php if ($btn_primary || $btn_secondary) : ?>
          <div class="o-hero__actions" role="group" aria-label="<?php echo esc_attr__('Ações principais', 'textdomain'); ?>" data-animate="fade-up" data-animate-duration="0.9"
            data-animate-delay="0.25">
            <?php
            if ($btn_primary)   echo $render_btn($btn_primary, 'c-button button button__blue o-hero__button');
            if ($btn_secondary) echo $render_btn($btn_secondary, 'c-button button button-border__blue o-hero__button');
            ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
  <?php if ($mockup_id) : ?>
    <div class="o-hero__media" data-animate="fade-up" data-animate-duration="0.9"
      data-animate-delay="0.15">
      <figure class="c-media o-hero__figure">
        <?= wp_get_attachment_image(
          $mockup_id,
          'full',
          false,
          [
            'class'    => 'c-media__img o-hero__image',
            'alt'      => esc_attr($mockup_alt),
            'loading'  => 'eager',
            'decoding' => 'async',
          ]
        );
        ?>
      </figure>
    </div>
  <?php endif; ?>
</section>