<?php

$data = isset($data) ? $data : (get_query_var('data') ?: []);
$data = is_array($data) ? $data : [];

$post_id = isset($data['id']) ? (int) $data['id'] : (get_the_ID() ?: 0);

$hero = get_field('hero', $post_id);
$hero = is_array($hero) ? $hero : [];

if (empty($hero)) {
  return;
}

$title         = isset($hero['title']) ? trim((string) $hero['title']) : '';
$subtitle      = isset($hero['subtitle']) ? trim((string) $hero['subtitle']) : '';
$description   = isset($hero['description']) ? (string) $hero['description'] : '';

$btn_primary   = (!empty($hero['button_primary']) && is_array($hero['button_primary'])) ? $hero['button_primary'] : null;
$btn_secondary = (!empty($hero['button_secondary']) && is_array($hero['button_secondary'])) ? $hero['button_secondary'] : null;

$mockup_raw   = $hero['mockup_image'] ?? null;
$mockupm_raw  = $hero['mockup_image_mobile'] ?? null;

$img_id = function ($img) {
  if (is_array($img) && !empty($img['ID'])) return (int) $img['ID'];
  if (is_numeric($img)) return (int) $img;
  return 0;
};

$img_alt = function ($img, $fallback = '') {
  if (is_array($img) && !empty($img['alt'])) return (string) $img['alt'];
  return (string) $fallback;
};

$mockup_id  = $img_id($mockup_raw);
$mockupm_id = $img_id($mockupm_raw);

$mockup_alt  = $img_alt($mockup_raw, $title ?: '');
$mockupm_alt = $img_alt($mockupm_raw, $title ?: '');

if (
  !$title &&
  !$subtitle &&
  !trim((string) $description) &&
  empty($btn_primary) &&
  empty($btn_secondary) &&
  !$mockup_id &&
  !$mockupm_id
) {
  return;
}

$render_btn = function ($link, $extra_class = '') {
  if (!is_array($link) || empty($link['url'])) return '';

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

$bg_src = get_stylesheet_directory_uri() . '/public/image/bg-hero-header.webp';
$label_section = $title ?: esc_html__('Seção principal', 'textdomain');
?>

<section class="o-hero" aria-label="<?php echo esc_attr($label_section); ?>">
  <div class="hero-bg" aria-hidden="true">
    <img src="<?php echo esc_url($bg_src); ?>" alt="">
  </div>

  <div class="s-container">
    <div class="o-hero__grid">
      <div class="o-hero__content">

        <?php if ($subtitle) : ?>
          <div class="o-hero__subtitle" data-animate="fade-up" data-animate-duration="0.9" data-animate-delay="0.1">
            <?php echo wp_kses_post($subtitle); ?>
          </div>
        <?php endif; ?>

        <?php if ($title) : ?>
          <h1 class="o-hero__title title__super" data-animate="fade-up" data-animate-duration="0.9" data-animate-delay="0.15">
            <?php echo esc_html($title); ?>
          </h1>
        <?php endif; ?>

        <?php if (trim((string) $description)) : ?>
          <div class="o-hero__description text__normal" data-animate="fade-up" data-animate-duration="0.9" data-animate-delay="0.2">
            <?php echo wpautop(wp_kses_post($description)); ?>
          </div>
        <?php endif; ?>

        <?php if ($btn_primary || $btn_secondary) : ?>
          <div class="o-hero__actions" role="group" aria-label="<?php echo esc_attr__('Ações principais', 'textdomain'); ?>" data-animate="fade-up" data-animate-duration="0.9" data-animate-delay="0.25">
            <?php
            if ($btn_primary)   echo $render_btn($btn_primary, 'c-button button button__blue o-hero__button');
            if ($btn_secondary) echo $render_btn($btn_secondary, 'c-button button button-border__blue o-hero__button');
            ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <?php if ($mockup_id || $mockupm_id) : ?>
    <div class="o-hero__media" data-animate="fade-up" data-animate-duration="0.9" data-animate-delay="0.15">
      <figure class="c-media o-hero__figure">
        <?php if ($mockup_id && $mockupm_id) : ?>
          <picture>
            <source media="(max-width: 767px)" srcset="<?php echo esc_url(wp_get_attachment_image_url($mockupm_id, 'full')); ?>">
            <?php
            echo wp_get_attachment_image(
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
          </picture>
        <?php else : ?>
          <?php
          $final_id  = $mockup_id ?: $mockupm_id;
          $final_alt = $mockup_id ? $mockup_alt : $mockupm_alt;

          echo wp_get_attachment_image(
            $final_id,
            'full',
            false,
            [
              'class'    => 'c-media__img o-hero__image',
              'alt'      => esc_attr($final_alt),
              'loading'  => 'eager',
              'decoding' => 'async',
            ]
          );
          ?>
        <?php endif; ?>
      </figure>
    </div>
  <?php endif; ?>
</section>