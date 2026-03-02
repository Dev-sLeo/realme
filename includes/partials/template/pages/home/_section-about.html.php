<?php

/**
 * Módulo: Por que nós
 * Grupo ACF (dentro do group_home_modulos): section_about
 */

$data = isset($data) && is_array($data) ? $data : get_field('section_about');
if (empty($data) || !is_array($data)) {
  return;
}

$sub_titulo = isset($data['sub_titulo']) ? trim((string) $data['sub_titulo']) : '';
$title      = isset($data['title']) ? trim((string) $data['title']) : '';
$text       = isset($data['text']) ? (string) $data['text'] : '';

$image = isset($data['image']) ? $data['image'] : null;
$image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;

$features = (isset($data['features']) && is_array($data['features'])) ? $data['features'] : [];
$has_features = !empty($features);

if (!$sub_titulo && !$title && !trim($text) && !$image_id && !$has_features) {
  return;
}
?>

<section class="o-about" aria-label="<?php echo esc_attr($title ?: 'Sobre'); ?>">
  <div class="s-container">
    <div class="o-about__container">

      <div class="o-about__grid">

        <div class="o-about__content">
          <?php if ($sub_titulo) : ?>
            <p class="o-about__eyebrow subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($sub_titulo); ?></p>
          <?php endif; ?>

          <?php if ($title) : ?>
            <h2 class="o-about__title title__normal"><?php echo esc_html($title); ?></h2>
          <?php endif; ?>

          <?php if (trim($text)) : ?>
            <div class="o-about__text text__noraml" data-animate="fade-up" data-animate-delay="0.15">
              <?= $text ?>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($image_id) : ?>
          <div class="o-about__media" data-animate="fade-up" data-animate-delay="0.2">
            <?php
            echo wp_get_attachment_image(
              $image_id,
              'full',
              false,
              array(
                'class'   => 'o-about__image',
                'loading' => 'lazy',
              )
            );
            ?>
          </div>
        <?php endif; ?>

      </div>

      <?php if ($has_features) : ?>
        <div class="o-about__features o-about__swiper swiper" data-swiper="about-features" role="region" aria-label="Destaques" data-animate="fade-up" data-animate-delay="0.25">
          <div class="swiper-wrapper" role="list">
            <?php foreach ($features as $feature) :
              if (!is_array($feature)) {
                continue;
              }

              $f_icon = isset($feature['icon']) ? $feature['icon'] : null;
              $f_icon_id = is_array($f_icon) && !empty($f_icon['ID']) ? (int) $f_icon['ID'] : 0;

              $f_title = isset($feature['title']) ? trim((string) $feature['title']) : '';
              $f_text  = isset($feature['text']) ? (string) $feature['text'] : '';

              if (!$f_icon_id && !$f_title && !trim($f_text)) {
                continue;
              }
            ?>
              <div class="swiper-slide" role="listitem">
                <div class="c-feature">
                  <?php if ($f_icon_id) : ?>
                    <div class="c-feature__icon" aria-hidden="true">
                      <?php
                      echo wp_get_attachment_image(
                        $f_icon_id,
                        'thumbnail',
                        false,
                        array('class' => 'c-feature__icon-img', 'loading' => 'lazy')
                      );
                      ?>
                    </div>
                  <?php endif; ?>

                  <div class="c-feature__body">
                    <?php if ($f_title) : ?>
                      <h3 class="c-feature__title"><?php echo esc_html($f_title); ?></h3>
                    <?php endif; ?>

                    <?php if (trim($f_text)) : ?>
                      <div class="c-feature__text">
                        <?php echo wpautop(wp_kses_post($f_text)); ?>
                      </div>
                    <?php endif; ?>

                    <a href="" class="button button-border__blue">Veja mais</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>