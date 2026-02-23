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

$highlight_icon = $highlight['icone'] ?? null;
$highlight_text = $highlight['texto'] ?? '';
$highlight_description = $highlight['descricao'] ?? '';
?>

<section class="c-hero-case">
  <div class="o-container">
    <div class="c-hero-case__grid">

      <div class="c-hero-case__left">
        <div class="c-hero-case__card">

          <?php if ($logo):
            $logo_id = is_array($logo) ? ($logo['ID'] ?? null) : $logo;
            if ($logo_id): ?>
              <div class="c-hero-case__logo">
                <?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'c-hero-case__logo-img']); ?>
              </div>
            <?php endif;
          endif; ?>

          <?php if ($company || $location): ?>
            <div class="c-hero-case__company">
              <?php if ($company): ?>
                <p class="title__normal c-hero-case__company-name">
                  <?php echo esc_html($company); ?>
                </p>
              <?php endif; ?>

              <?php if ($location): ?>
                <p class="text__normal c-hero-case__company-location">
                  <?php echo esc_html($location); ?>
                </p>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        </div>

        <?php if ($highlight_text || $highlight_description || $highlight_icon): ?>
          <div class="c-hero-case__highlight">
            <?php if ($highlight_icon):
              $icon_id = is_array($highlight_icon) ? ($highlight_icon['ID'] ?? null) : $highlight_icon;
              if ($icon_id): ?>
                <div class="c-hero-case__highlight-icon">
                  <?php echo wp_get_attachment_image($icon_id, 'full'); ?>
                </div>
              <?php endif;
            endif; ?>

            <div class="c-hero-case__highlight-content">
              <?php if ($highlight_text): ?>
                <p class="title__normal c-hero-case__highlight-title">
                  <?php echo esc_html($highlight_text); ?>
                </p>
              <?php endif; ?>

              <?php if ($highlight_description): ?>
                <p class="text__normal c-hero-case__highlight-description">
                  <?php echo esc_html($highlight_description); ?>
                </p>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($quote || $author || $role || $author_image): ?>
        <div class="c-hero-case__right">
          <div class="c-hero-case__testimonial">

            <?php if ($quote): ?>
              <blockquote class="c-hero-case__quote text__normal">
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
                    <p class="c-hero-case__author-role text__normal">
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