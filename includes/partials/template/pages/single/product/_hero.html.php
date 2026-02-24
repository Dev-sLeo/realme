<?php
$data = isset($data) && is_array($data) ? $data : array();

$hero = !empty($data['hero']) && is_array($data['hero'])
  ? $data['hero']
  : get_field('hero');

if (empty($hero) || !is_array($hero)) {
  return;
}

// Fields
$badge = $hero['badge'] ?? '';
$title = $hero['title'] ?? '';
$description = $hero['description'] ?? '';
$cta_primary = $hero['cta_primary'] ?? array();   // link array
$cta_secondary = $hero['cta_secondary'] ?? array(); // link array
$metrics = $hero['metrics'] ?? array();       // repeater
$art = $hero['art'] ?? array();           // image array

// Helpers
$img_id = function ($img) {
  return (is_array($img) && !empty($img['ID'])) ? (int) $img['ID'] : 0;
};

$link = function ($l) {
  return is_array($l) ? $l : array();
};

$cta_primary = $link($cta_primary);
$cta_secondary = $link($cta_secondary);

$cta_primary_url = !empty($cta_primary['url']) ? $cta_primary['url'] : '';
$cta_primary_title = !empty($cta_primary['title']) ? $cta_primary['title'] : '';
$cta_primary_target = !empty($cta_primary['target']) ? $cta_primary['target'] : '_self';

$cta_secondary_url = !empty($cta_secondary['url']) ? $cta_secondary['url'] : '';
$cta_secondary_title = !empty($cta_secondary['title']) ? $cta_secondary['title'] : '';
$cta_secondary_target = !empty($cta_secondary['target']) ? $cta_secondary['target'] : '_self';

$art_id = $img_id($art);
?>

<section class="o-hero-product" aria-label="<?php echo esc_attr($title ?: 'Hero'); ?>">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-single-cases.webp" alt="">
  </div>
  <div class="s-container">
    <div class=" o-hero-product__container">

      <div class="o-hero-product__content">
        <?php if (!empty($badge)): ?>
          <p class="subtitulo o-hero-product__badge">
            <?php echo esc_html($badge); ?>
          </p>
        <?php endif; ?>

        <?php if (!empty($title)): ?>
          <h1 class="title__super o-hero-product__title">
            <?php echo esc_html($title); ?>
          </h1>
        <?php endif; ?>

        <?php if (!empty($description)): ?>
          <div class="o-hero-product__text">
            <?php echo wpautop(wp_kses_post($description)); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($cta_primary_url) || !empty($cta_secondary_url)): ?>
          <div class="o-hero-product__ctas" role="group" aria-label="Ações do hero">

            <?php if (!empty($cta_primary_url) && !empty($cta_primary_title)): ?>
              <a class="button button__blue o-hero-product__cta" href="<?php echo esc_url($cta_primary_url); ?>"
                target="<?php echo esc_attr($cta_primary_target); ?>"
                rel="<?php echo ($cta_primary_target === '_blank') ? 'noopener noreferrer' : 'nofollow'; ?>">
                <?php echo esc_html($cta_primary_title); ?>
              </a>
            <?php endif; ?>

          </div>
        <?php endif; ?>

        <?php if (!empty($metrics) && is_array($metrics)): ?>
          <ul class="o-hero-product__metrics" aria-label="Métricas">
            <?php foreach ($metrics as $m):
              if (empty($m) || !is_array($m))
                continue;

              $value = $m['value'] ?? '';
              $label = $m['label'] ?? '';
              $icon = $m['icon'] ?? array();
              $icon_id = $img_id($icon);

              if (empty($value) && empty($label) && empty($icon_id))
                continue;
            ?>
              <li class="c-metric o-hero-product__metric">
                <?php if (!empty($icon_id)): ?>
                  <div class="c-metric__icon">
                    <?php echo wp_get_attachment_image($icon_id, 'full', false, array('loading' => 'lazy')); ?>
                  </div>
                <?php endif; ?>

                <div class="c-metric__content">
                  <?php if (!empty($value)): ?>
                    <span class="c-metric__value">
                      <?php echo esc_html($value); ?>
                    </span>
                  <?php endif; ?>

                  <?php if (!empty($label)): ?>
                    <span class="c-metric__label">
                      <?php echo esc_html($label); ?>
                    </span>
                  <?php endif; ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <?php if (!empty($art_id)): ?>
        <div class="o-hero-product__media" aria-hidden="true">
          <?php echo wp_get_attachment_image($art_id, 'full', false, array('loading' => 'lazy')); ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>