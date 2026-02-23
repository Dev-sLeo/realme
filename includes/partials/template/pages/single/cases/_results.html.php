<?php
$data = isset($data) && is_array($data) ? $data : get_field('more_cases');

if (empty($data)) {
  return;
}

$title = $data['title'] ?? '';

$post_id = get_the_ID();
$post_type = get_post_type($post_id);

$q = new WP_Query([
  'post_type' => $post_type ?: 'post',
  'posts_per_page' => 3,
  'post_status' => 'publish',
  'post__not_in' => [$post_id],
  'orderby' => 'date',
  'order' => 'DESC',
]);

if (!$q->have_posts()) {
  wp_reset_postdata();
  return;
}
?>

<section class="c-more-cases" aria-label="<?php echo esc_attr($title ?: __('Veja mais resultados', 'textdomain')); ?>">
  <div class="o-container">

    <?php if ($title): ?>
      <header class="c-more-cases__header">
        <h2 class="title__normal c-more-cases__title">
          <?php echo esc_html($title); ?>
        </h2>
      </header>
    <?php endif; ?>

    <div class="c-more-cases__list">
      <?php while ($q->have_posts()):
        $q->the_post();
        $id = get_the_ID();

        $hero = get_field('hero_case', $id);
        $quote = get_field('case_quote', $id);

        $logo = is_array($hero) ? ($hero['logo'] ?? null) : null;
        $company = is_array($hero) ? ($hero['company'] ?? '') : '';
        $location = is_array($hero) ? ($hero['location'] ?? '') : '';
        $highlight = is_array($hero) ? ($hero['highlight'] ?? []) : [];

        $h_icon = is_array($highlight) ? ($highlight['icone'] ?? null) : null;
        $h_text = is_array($highlight) ? ($highlight['texto'] ?? '') : '';
        $h_desc = is_array($highlight) ? ($highlight['descricao'] ?? '') : '';

        $t_text = is_array($quote) ? ($quote['text'] ?? '') : '';
        $t_author = is_array($quote) ? ($quote['author'] ?? '') : '';
        $t_role = is_array($quote) ? ($quote['role'] ?? '') : '';

        $logo_id = $logo ? (is_array($logo) ? ($logo['ID'] ?? null) : $logo) : null;
        $h_icon_id = $h_icon ? (is_array($h_icon) ? ($h_icon['ID'] ?? null) : $h_icon) : null;

        $permalink = get_permalink($id);
        ?>

        <article class="c-more-cases__card">
          <div class="c-more-cases__card-top">

            <div class="c-more-cases__company">
              <?php if ($logo_id): ?>
                <div class="c-more-cases__logo">
                  <?php echo wp_get_attachment_image($logo_id, 'thumbnail', false, ['class' => 'c-more-cases__logo-img']); ?>
                </div>
              <?php endif; ?>

              <div class="c-more-cases__company-info">
                <?php if ($company): ?>
                  <p class="c-more-cases__company-name">
                    <?php echo esc_html($company); ?>
                  </p>
                <?php endif; ?>
                <?php if ($location): ?>
                  <p class="c-more-cases__company-location text__normal">
                    <?php echo esc_html($location); ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>

            <?php if ($h_text || $h_desc || $h_icon_id): ?>
              <div class="c-more-cases__highlight">
                <?php if ($h_icon_id): ?>
                  <div class="c-more-cases__highlight-icon" aria-hidden="true">
                    <?php echo wp_get_attachment_image($h_icon_id, 'thumbnail'); ?>
                  </div>
                <?php endif; ?>

                <div class="c-more-cases__highlight-content">
                  <?php if ($h_text): ?>
                    <p class="title__normal c-more-cases__highlight-number">
                      <?php echo esc_html($h_text); ?>
                    </p>
                  <?php endif; ?>
                  <?php if ($h_desc): ?>
                    <p class="text__normal c-more-cases__highlight-label">
                      <?php echo esc_html($h_desc); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

          </div>

          <?php if ($t_text): ?>
            <blockquote class="c-more-cases__quote text__normal">
              <?php echo wpautop(wp_kses_post($t_text)); ?>
            </blockquote>
          <?php endif; ?>

          <?php if ($t_author || $t_role): ?>
            <div class="c-more-cases__author">
              <?php if ($t_author): ?>
                <p class="c-more-cases__author-name">
                  <?php echo esc_html($t_author); ?>
                </p>
              <?php endif; ?>
              <?php if ($t_role): ?>
                <p class="c-more-cases__author-role text__normal">
                  <?php echo esc_html($t_role); ?>
                </p>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <div class="c-more-cases__cta">
            <a class="button button__blue c-more-cases__button" href="<?php echo esc_url($permalink); ?>">
              <?php echo esc_html__('Veja mais', 'textdomain'); ?>
            </a>
          </div>
        </article>

      <?php endwhile; ?>
    </div>

  </div>
</section>

<?php wp_reset_postdata(); ?>