<?php
add_action('wp_ajax_archive_cases_filter', 'archive_cases_filter_callback');
add_action('wp_ajax_nopriv_archive_cases_filter', 'archive_cases_filter_callback');

function archive_cases_filter_callback()
{
  $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
  if (!$nonce || !wp_verify_nonce($nonce, 'archive_cases_filter')) {
    wp_die('', 403);
  }

  $post_type = isset($_POST['post_type']) ? sanitize_key(wp_unslash($_POST['post_type'])) : 'case';
  $tax_cat = isset($_POST['tax_cat']) ? sanitize_key(wp_unslash($_POST['tax_cat'])) : 'cat_case';
  $tax_regiao = isset($_POST['tax_regiao']) ? sanitize_key(wp_unslash($_POST['tax_regiao'])) : 'regiao';

  $cat = isset($_POST['cat']) ? sanitize_text_field(wp_unslash($_POST['cat'])) : '';
  $regiao = isset($_POST['regiao']) ? sanitize_text_field(wp_unslash($_POST['regiao'])) : '';

  $tax_query = [];
  if ($cat) {
    $tax_query[] = [
      'taxonomy' => $tax_cat,
      'field' => 'slug',
      'terms' => [$cat],
    ];
  }
  if ($regiao) {
    $tax_query[] = [
      'taxonomy' => $tax_regiao,
      'field' => 'slug',
      'terms' => [$regiao],
    ];
  }

  $args = [
    'post_type' => $post_type ?: 'case',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'paged' => 1,
  ];

  if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
  }

  $q = new WP_Query($args);

  ob_start();

  if ($q->have_posts()) {
    while ($q->have_posts()) {
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
      <article class="c-archive-cases-results__card">
        <div class="c-archive-cases-results__card-top">

          <div class="c-archive-cases-results__company">
            <?php if ($logo_id): ?>
              <div class="c-archive-cases-results__logo">
                <?php echo wp_get_attachment_image($logo_id, 'thumbnail', false, ['class' => 'c-archive-cases-results__logo-img']); ?>
              </div>
            <?php endif; ?>

            <div class="c-archive-cases-results__company-info">
              <?php if ($company): ?>
                <p class="c-archive-cases-results__company-name"><?php echo esc_html($company); ?></p>
              <?php endif; ?>
              <?php if ($location): ?>
                <p class="text__normal c-archive-cases-results__company-location"><?php echo esc_html($location); ?></p>
              <?php endif; ?>
            </div>
          </div>

          <?php if ($h_text || $h_desc || $h_icon_id): ?>
            <div class="c-archive-cases-results__highlight">
              <?php if ($h_icon_id): ?>
                <div class="c-archive-cases-results__highlight-icon" aria-hidden="true">
                  <?php echo wp_get_attachment_image($h_icon_id, 'thumbnail'); ?>
                </div>
              <?php endif; ?>

              <div class="c-archive-cases-results__highlight-content">
                <?php if ($h_text): ?>
                  <p class="title__normal c-archive-cases-results__highlight-number"><?php echo esc_html($h_text); ?></p>
                <?php endif; ?>
                <?php if ($h_desc): ?>
                  <p class="text__normal c-archive-cases-results__highlight-label"><?php echo esc_html($h_desc); ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>

        <?php if ($t_text): ?>
          <blockquote class="c-archive-cases-results__quote text__normal">
            <?php echo wpautop(wp_kses_post($t_text)); ?>
          </blockquote>
        <?php endif; ?>

        <?php if ($t_author || $t_role): ?>
          <div class="c-archive-cases-results__author">
            <?php if ($t_author): ?>
              <p class="c-archive-cases-results__author-name"><?php echo esc_html($t_author); ?></p>
            <?php endif; ?>
            <?php if ($t_role): ?>
              <p class="text__normal c-archive-cases-results__author-role"><?php echo esc_html($t_role); ?></p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="c-archive-cases-results__cta">
          <a class="button button__blue c-archive-cases-results__button" href="<?php echo esc_url($permalink); ?>">
            <?php echo esc_html__('Veja mais', 'textdomain'); ?>
          </a>
        </div>
      </article>
      <?php
    }
  } else {
    ?>
    <p class="text__normal c-archive-cases-results__empty">
      <?php echo esc_html__('Nenhum case encontrado para os filtros selecionados.', 'textdomain'); ?>
    </p>
    <?php
  }

  wp_reset_postdata();

  $html = ob_get_clean();
  echo $html;
  wp_die();
}