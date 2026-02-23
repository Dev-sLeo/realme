<?php
$data = isset($data) && is_array($data) ? $data : get_field('case_quote');

if (empty($data)) {
  return;
}

$logo = $data['logo'] ?? null;
$highlight = $data['highlight'] ?? '';
$text = $data['text'] ?? '';
$author = $data['author'] ?? '';
$role = $data['role'] ?? '';

$logo_id = null;
if ($logo) {
  $logo_id = is_array($logo) ? ($logo['ID'] ?? null) : $logo;
}

if (!$logo_id && !$highlight && !$text && !$author && !$role) {
  return;
}
?>

<section class="c-case-testimonial">
  <div class="o-container">
    <div class="c-case-testimonial__grid">

      <div class="c-case-testimonial__left">
        <div class="c-case-testimonial__card">
          <?php if ($logo_id): ?>
            <div class="c-case-testimonial__logo">
              <?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'c-case-testimonial__logo-img']); ?>
            </div>
          <?php endif; ?>

          <?php if ($highlight): ?>
            <p class="title__normal c-case-testimonial__highlight">
              <?php echo esc_html($highlight); ?>
            </p>
          <?php endif; ?>
        </div>
      </div>

      <div class="c-case-testimonial__right">
        <div class="c-case-testimonial__content">

          <?php if ($text): ?>
            <blockquote class="c-case-testimonial__quote text__normal">
              <?php echo wpautop(wp_kses_post($text)); ?>
            </blockquote>
          <?php endif; ?>

          <?php if ($author || $role): ?>
            <div class="c-case-testimonial__author">
              <?php if ($author): ?>
                <p class="c-case-testimonial__author-name">
                  <?php echo esc_html($author); ?>
                </p>
              <?php endif; ?>

              <?php if ($role): ?>
                <p class="c-case-testimonial__author-role text__normal">
                  <?php echo esc_html($role); ?>
                </p>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>
</section>