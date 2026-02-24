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
              <div class="c-hero-case__logo">
                <?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'c-hero-case__logo-img']); ?>
              </div>
          <?php endif;
          endif; ?>

          <?php if ($company || $location): ?>
            <div class="c-hero-case__company">
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

        <?php if ($highlight_text || $highlight_description || $highlight_icon): ?>
          <div class="c-hero-case__highlight">
            <?php if ($highlight_icon):
              $icon_id = is_array($highlight_icon) ? ($highlight_icon['ID'] ?? null) : $highlight_icon;
              if ($icon_id): ?>
                <div class="c-hero-case__highlight-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52" fill="none">
                    <path d="M43.7182 22.3925V41.586C43.7182 42.5796 43.7182 43.0765 43.5559 43.4685C43.3394 43.991 42.9242 44.4062 42.4017 44.6226C42.0097 44.7849 41.5128 44.7849 40.5192 44.7849C39.5257 44.7849 39.0288 44.7849 38.6368 44.6226C38.1143 44.4062 37.6991 43.991 37.4826 43.4685C37.3203 43.0765 37.3203 42.5796 37.3203 41.586V22.3925C37.3203 21.3989 37.3203 20.902 37.4826 20.51C37.6991 19.9875 38.1143 19.5723 38.6368 19.3559C39.0288 19.1935 39.5257 19.1935 40.5192 19.1935C41.5128 19.1935 42.0097 19.1935 42.4017 19.3559C42.9242 19.5723 43.3394 19.9875 43.5559 20.51C43.7182 20.902 43.7182 21.3989 43.7182 22.3925Z" stroke="#A3D2E0" stroke-width="3.19892" stroke-linejoin="round" />
                    <path d="M35.1895 6.39783H41.5873V12.7957" stroke="#A3D2E0" stroke-width="3.19892" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M40.5206 7.46417C40.5206 7.46417 31.9901 18.1273 9.59766 25.5914" stroke="#A3D2E0" stroke-width="3.19892" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M28.7904 29.8566V41.586C28.7904 42.5796 28.7904 43.0765 28.6281 43.4685C28.4117 43.991 27.9965 44.4062 27.474 44.6227C27.082 44.785 26.5851 44.785 25.5915 44.785C24.5979 44.785 24.101 44.785 23.709 44.6227C23.1866 44.4062 22.7713 43.991 22.5549 43.4685C22.3926 43.0765 22.3926 42.5796 22.3926 41.586V29.8566C22.3926 28.8631 22.3926 28.3662 22.5549 27.9742C22.7713 27.4517 23.1866 27.0365 23.709 26.82C24.101 26.6577 24.5979 26.6577 25.5915 26.6577C26.5851 26.6577 27.082 26.6577 27.474 26.82C27.9965 27.0365 28.4117 27.4517 28.6281 27.9742C28.7904 28.3662 28.7904 28.8631 28.7904 29.8566Z" stroke="#A3D2E0" stroke-width="3.19892" stroke-linejoin="round" />
                    <path d="M13.8627 35.1882V41.586C13.8627 42.5796 13.8627 43.0765 13.7004 43.4685C13.4839 43.991 13.0687 44.4062 12.5462 44.6227C12.1543 44.785 11.6574 44.785 10.6638 44.785C9.6701 44.785 9.17326 44.785 8.78135 44.6227C8.2588 44.4062 7.84362 43.991 7.62718 43.4685C7.46484 43.0765 7.46484 42.5796 7.46484 41.586V35.1882C7.46484 34.1946 7.46484 33.6977 7.62718 33.3057C7.84362 32.7832 8.2588 32.368 8.78135 32.1516C9.17326 31.9893 9.6701 31.9893 10.6638 31.9893C11.6574 31.9893 12.1543 31.9893 12.5462 32.1516C13.0687 32.368 13.4839 32.7832 13.7004 33.3057C13.8627 33.6977 13.8627 34.1946 13.8627 35.1882Z" stroke="#A3D2E0" stroke-width="3.19892" stroke-linejoin="round" />
                  </svg>
                </div>
            <?php endif;
            endif; ?>

            <div class="c-hero-case__highlight-content">
              <?php if ($highlight_text): ?>
                <p class="c-hero-case__highlight-title">
                  <?php echo esc_html($highlight_text); ?>
                </p>
              <?php endif; ?>

              <?php if ($highlight_description): ?>
                <p class="c-hero-case__highlight-description">
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