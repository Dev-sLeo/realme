<?php

/**
 * Home - Logos (Prova social) + Swiper (somente slider)
 * Arquivo: partials/template/pages/PAGINA/_logos.html.php
 *
 * ACF:
 * - logos (group)
 *   - text (text)
 *   - items (repeater)
 *     - image (image)
 *     - link (link)
 */

$logos = get_field('logos');
$logos = is_array($logos) ? $logos : [];

if (empty($logos)) {
  return;
}

$text  = isset($logos['text']) ? trim((string) $logos['text']) : '';
$items = (!empty($logos['items']) && is_array($logos['items'])) ? $logos['items'] : [];

if (empty($text) && empty($items)) {
  return;
}

$fallback_logo_url = trailingslashit(get_stylesheet_directory_uri()) . 'public/image/logo-default.webp';
?>

<section class="o-logos" aria-label="<?php echo esc_attr__('Prova social', 'textdomain'); ?>">
  <div class="s-container">

    <?php if ($text) : ?>
      <h2 class="o-logos__text title__small"><?= $text ?></h2>
    <?php endif; ?>

    <?php if (!empty($items)) : ?>
      <div class="o-logos__slider">
        <div class="splide o-logos__swiper" aria-label="logos">
          <div class="splide__track">
            <ul class="splide__list">
              <?php foreach ($items as $row) :
                if (!is_array($row)) continue;

                $img  = (!empty($row['image']) && is_array($row['image'])) ? $row['image'] : null;
                $link = (!empty($row['link']) && is_array($row['link'])) ? $row['link'] : null;

                $img_id  = (!empty($img['ID'])) ? (int) $img['ID'] : 0;
                $img_alt = (!empty($img['alt'])) ? (string) $img['alt'] : '';
                $alt     = $img_alt ? $img_alt : esc_attr__('Logo', 'textdomain');

                $has_link = (!empty($link['url']));
                $href     = $has_link ? esc_url($link['url']) : '';
                $target   = $has_link && !empty($link['target']) ? esc_attr($link['target']) : '_self';
                $rel      = ($has_link && $target === '_blank') ? 'noopener noreferrer' : '';
              ?>
                <div class="splide__slide o-logos__slide">
                  <?php
                  // Conteúdo (img ACF OU fallback)
                  ob_start();
                  if ($img_id) {
                    echo wp_get_attachment_image(
                      $img_id,
                      'medium',
                      false,
                      [
                        'class'    => 'c-logo__img o-logos__img',
                        'alt'      => esc_attr($alt),
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                      ]
                    );
                  } else {
                  ?>
                    <img
                      class="c-logo__img o-logos__img"
                      src="<?php echo esc_url($fallback_logo_url); ?>"
                      alt="<?php echo esc_attr($alt); ?>"
                      loading="lazy"
                      decoding="async" />
                  <?php
                  }
                  $logo_markup = ob_get_clean();
                  ?>

                  <?php if ($has_link) : ?>
                    <a class="c-logo o-logos__item" href="<?php echo $href; ?>" target="<?php echo $target; ?>" <?php echo $rel ? ' rel="' . esc_attr($rel) . '"' : ''; ?>>
                      <?php echo $logo_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                      ?>
                    </a>
                  <?php else : ?>
                    <div class="c-logo o-logos__item">
                      <?php echo $logo_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                      ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>