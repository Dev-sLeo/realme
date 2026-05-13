<?php
$data = isset($data) && is_array($data) ? $data : get_field('hero_case');

if (empty($data)) {
  return;
}

$logo = $data['logo'] ?? null;
$company = $data['company'] ?? '';
$location = $data['location'] ?? '';
$highlight = $data['highlight'] ?? [];
$quote = $data['quote'] ?? '';
$author = $data['author'] ?? '';
$role = $data['role'] ?? '';
$author_image = $data['author_image'] ?? null;

// highlight.imagens: suporta ACF gallery (array de images) ou image simples (array com 'url')
$raw_imagens = $highlight['imagens'] ?? null;
if (!empty($raw_imagens)) {
  // gallery type retorna array indexado de arrays de imagem
  if (isset($raw_imagens[0]) && is_array($raw_imagens[0])) {
    $highlight_images = $raw_imagens;
  } elseif (isset($raw_imagens['url'])) {
    // image type retorna um único array de imagem
    $highlight_images = [$raw_imagens];
  } else {
    $highlight_images = [];
  }
} else {
  $highlight_images = [];
}
?>

<section class="c-hero-case">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-single-cases.webp" alt="">
  </div>
  <div class="s-container">
    <div class="c-hero-case__grid">

      <div class="c-hero-case__left">
        <div class="c-hero-case__card">

          <?php if ($logo):
            $logo_id = is_array($logo) ? ($logo['ID'] ?? null) : $logo;
            if ($logo_id): ?>
              <div class="c-hero-case__logo" data-animate="fade-up" data-animate-delay="0.1">
                <?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'c-hero-case__logo-img']); ?>
              </div>
          <?php endif;
          endif; ?>

          <?php if ($company || $location): ?>
            <div class="c-hero-case__company" data-animate="fade-up" data-animate-delay="0.2">
              <?php if ($company): ?>
                <p class=" c-hero-case__company-name">
                  <?php echo esc_html($company); ?>
                </p>
              <?php endif; ?>

              <?php if ($location): ?>
                <p class="c-hero-case__company-location">
                  <?php echo esc_html($location); ?>
                </p>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        </div>

        <?php if (!empty($highlight_images)): ?>
          <div class="c-hero-case__highlight" data-animate="fade-up" data-animate-delay="0.3">
            <div class="c-hero-case__gallery">
              <?php foreach ($highlight_images as $img):
                if (!is_array($img)) continue;
                $img_url = $img['url'] ?? '';
                $img_alt = $img['alt'] ?? ($img['title'] ?? '');
                if (!$img_url) continue;
              ?>
                <figure class="c-hero-case__gallery-item">
                  <img
                    src="<?php echo esc_url($img_url); ?>"
                    alt="<?php echo esc_attr($img_alt); ?>"
                    loading="lazy"
                    decoding="async">
                </figure>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($quote || $author || $role || $author_image): ?>
        <div class="c-hero-case__right" data-animate="fade-up" data-animate-delay="0.4">
          <div class="c-hero-case__testimonial">

            <?php if ($quote): ?>
              <blockquote class="c-hero-case__quote">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="21" viewBox="0 0 28 21" fill="none">
                  <path d="M0 21V15.4737C0 13.7953 0.296784 12.0146 0.890351 10.1316C1.50439 8.22807 2.3845 6.3962 3.5307 4.63597C4.69737 2.85526 6.09941 1.30994 7.73684 0L11.6667 3.19298C10.3772 5.03509 9.25146 6.95906 8.28947 8.96491C7.34795 10.9503 6.87719 13.0789 6.87719 15.3509V21H0ZM15.7193 21V15.4737C15.7193 13.7953 16.0161 12.0146 16.6096 10.1316C17.2237 8.22807 18.1038 6.3962 19.25 4.63597C20.4167 2.85526 21.8187 1.30994 23.4561 0L27.386 3.19298C26.0965 5.03509 24.9708 6.95906 24.0088 8.96491C23.0672 10.9503 22.5965 13.0789 22.5965 15.3509V21H15.7193Z" fill="#A3D2E0" />
                </svg>
                <?php echo wpautop(wp_kses_post($quote)); ?>
              </blockquote>
            <?php endif; ?>

            <?php if ($author || $role || $author_image): ?>
              <div class="c-hero-case__author">

                <?php if ($author_image):
                  $author_image_id = is_array($author_image) ? ($author_image['ID'] ?? null) : $author_image;
                  if ($author_image_id): ?>
                    <div class="c-hero-case__author-image">
                      <?php echo wp_get_attachment_image($author_image_id, 'thumbnail'); ?>
                    </div>
                <?php endif;
                endif; ?>

                <div class="c-hero-case__author-info">
                  <?php if ($author): ?>
                    <p class="c-hero-case__author-name">
                      <?php echo esc_html($author); ?>
                    </p>
                  <?php endif; ?>

                  <?php if ($role): ?>
                    <p class="c-hero-case__author-role">
                      <?php echo esc_html($role); ?>
                    </p>
                  <?php endif; ?>
                </div>

              </div>
            <?php endif; ?>

          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>