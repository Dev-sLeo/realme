<?php
global $tpl_engine;
$data = isset($data) && is_array($data) ? $data : get_field('veja_outros_resultados');

if (empty($data) || !is_array($data)) {
  return;
}

$titulo = $data['titulo'] ?? '';

$post_type = get_query_var('post_type');
if (is_array($post_type)) {
  $post_type = reset($post_type);
}
$post_type = $post_type ?: 'case';

$tax_cat = 'cat_cases';
$tax_regiao = 'regiao';

$cat_terms = get_terms([
  'taxonomy' => $tax_cat,
]);

$regiao_terms = get_terms([
  'taxonomy' => $tax_regiao,
  'hide_empty' => true,
]);

$cat_current = isset($_GET['cat_case']) ? sanitize_text_field(wp_unslash($_GET['cat_case'])) : '';
$reg_current = isset($_GET['regiao']) ? sanitize_text_field(wp_unslash($_GET['regiao'])) : '';

$tax_query = [];
if ($cat_current) {
  $tax_query[] = [
    'taxonomy' => $tax_cat,
    'field' => 'slug',
    'terms' => [$cat_current],
  ];
}
if ($reg_current) {
  $tax_query[] = [
    'taxonomy' => $tax_regiao,
    'field' => 'slug',
    'terms' => [$reg_current],
  ];
}

$args = [
  'post_type' => $post_type,
  'post_status' => 'publish',
  'posts_per_page' => 6,
  'paged' => 1,
];

if (!empty($tax_query)) {
  $args['tax_query'] = $tax_query;
}

$q = new WP_Query($args);

$regiao_options = [];
if (!empty($regiao_terms) && !is_wp_error($regiao_terms)) {
  $regiao_options[] = ['value' => '', 'label' => __('Todas as regiões', 'textdomain')];
  foreach ($regiao_terms as $t) {
    $regiao_options[] = ['value' => $t->slug, 'label' => $t->name];
  }
}
?>

<section class="c-archive-cases-results js-archive-cases-results"
  data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
  data-post-type="<?php echo esc_attr($post_type); ?>" data-tax-cat="<?php echo esc_attr($tax_cat); ?>"
  data-tax-regiao="<?php echo esc_attr($tax_regiao); ?>"
  data-nonce="<?php echo esc_attr(wp_create_nonce('archive_cases_filter')); ?>">
  <div class="s-container">

    <?php if ($titulo): ?>
      <header class="c-archive-cases-results__header">
        <h2 class="title__normal c-archive-cases-results__title" data-animate="fade-up" data-animate-delay="0.1">
          <?php echo esc_html($titulo); ?>
        </h2>
      </header>
    <?php endif; ?>

    <form class="c-archive-cases-results__filters js-archive-cases-filters" method="get"
      action="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>" data-animate="fade-up" data-animate-delay="0.2">
      <div class="c-archive-cases-results__filters-left" role="tablist"
        aria-label="<?php echo esc_attr__('Filtrar por categoria', 'textdomain'); ?>">
        <button type="button"
          class="c-archive-cases-results__chip js-cat-filter<?php echo $cat_current ? '' : ' is-active'; ?>" data-cat=""
          aria-pressed="<?php echo $cat_current ? 'false' : 'true'; ?>">
          <?php echo esc_html__('Todos', 'textdomain'); ?>
        </button>

        <?php if (!empty($cat_terms) && !is_wp_error($cat_terms)): ?>
          <?php foreach ($cat_terms as $term):
            $active = ($cat_current === $term->slug);
          ?>
            <button type="button"
              class="c-archive-cases-results__chip js-cat-filter<?php echo $active ? ' is-active' : ''; ?>"
              data-cat="<?php echo esc_attr($term->slug); ?>" aria-pressed="<?php echo $active ? 'true' : 'false'; ?>">
              <?php echo esc_html($term->name); ?>
            </button>
          <?php endforeach; ?>
        <?php endif; ?>

        <input type="hidden" name="cat_case" value="<?php echo esc_attr($cat_current); ?>" class="js-cat-input" />
      </div>

      <div class="c-archive-cases-results__filters-right" data-animate="fade-up" data-animate-delay="0.3">
        <?php
        echo $tpl_engine->partial('components/filters/select', [
          'label' => __('Região', 'textdomain'),
          'nome' => 'regiao',
          'options' => $regiao_options,
          'value' => $reg_current,
        ]);
        ?>
        <noscript>
          <button type="submit" class="button button__blue">
            <?php echo esc_html__('Filtrar', 'textdomain'); ?>
          </button>
        </noscript>
      </div>
    </form>

    <div class="c-archive-cases-results__grid js-archive-cases-grid" aria-live="polite" aria-busy="false" data-animate="fade-up" data-animate-delay="0.4">
      <?php if ($q->have_posts()): ?>
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

          $t_text = is_array($quote) ? ($quote['quote'] ?? '') : '';
          $author_image = is_array($hero) ? ($hero['author_image'] ?? null) : null;
          $author_image_id = $author_image ? (is_array($author_image) ? ($author_image['ID'] ?? null) : (int) $author_image) : null;
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
                  <div class="c-archive-cases-results__logo" data-animate="fade-up" data-animate-delay="0.5">
                    <?php echo wp_get_attachment_image($logo_id, 'thumbnail', false, ['class' => 'c-archive-cases-results__logo-img']); ?>
                  </div>
                <?php endif; ?>

                <div class="c-archive-cases-results__company-info">
                  <?php if ($company): ?>
                    <p class="c-archive-cases-results__company-name" data-animate="fade-up" data-animate-delay="0.6">
                      <?php echo esc_html($company); ?>
                    </p>
                  <?php endif; ?>
                  <?php if ($location): ?>
                    <p class="c-archive-cases-results__company-location" data-animate="fade-up" data-animate-delay="0.7">
                      <?php echo esc_html($location); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>

              <?php if ($h_text || $h_desc || $h_icon_id): ?>
                <div class="c-archive-cases-results__highlight" data-animate="fade-up" data-animate-delay="0.8">

                  <div class="c-archive-cases-results__highlight-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34" fill="none">
                      <path d="M29.043 15.304V28.054C29.043 28.714 29.043 29.0441 28.9352 29.3044C28.7914 29.6515 28.5155 29.9274 28.1685 30.0711C27.9081 30.179 27.578 30.179 26.918 30.179C26.2579 30.179 25.9279 30.179 25.6675 30.0711C25.3204 29.9274 25.0446 29.6515 24.9008 29.3044C24.793 29.0441 24.793 28.714 24.793 28.054V15.304C24.793 14.6439 24.793 14.3138 24.9008 14.0535C25.0446 13.7064 25.3204 13.4306 25.6675 13.2868C25.9279 13.179 26.2579 13.179 26.918 13.179C27.578 13.179 27.9081 13.179 28.1685 13.2868C28.5155 13.4306 28.7914 13.7064 28.9352 14.0535C29.043 14.3138 29.043 14.6439 29.043 15.304Z" stroke="#A3D2E0" stroke-width="2.125" stroke-linejoin="round" />
                      <path d="M23.375 4.67896H27.625V8.92895" stroke="#A3D2E0" stroke-width="2.125" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M26.9167 5.38721C26.9167 5.38721 21.25 12.4705 6.375 17.4289" stroke="#A3D2E0" stroke-width="2.125" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M19.125 20.2622V28.0539C19.125 28.7139 19.125 29.044 19.0172 29.3044C18.8734 29.6514 18.5976 29.9273 18.2505 30.0711C17.9901 30.1789 17.66 30.1789 17 30.1789C16.34 30.1789 16.0099 30.1789 15.7495 30.0711C15.4024 29.9273 15.1266 29.6514 14.9828 29.3044C14.875 29.044 14.875 28.7139 14.875 28.0539V20.2622C14.875 19.6022 14.875 19.2721 14.9828 19.0117C15.1266 18.6646 15.4024 18.3888 15.7495 18.245C16.0099 18.1372 16.34 18.1372 17 18.1372C17.66 18.1372 17.9901 18.1372 18.2505 18.245C18.5976 18.3888 18.8734 18.6646 19.0172 19.0117C19.125 19.2721 19.125 19.6022 19.125 20.2622Z" stroke="#A3D2E0" stroke-width="2.125" stroke-linejoin="round" />
                      <path d="M9.20703 23.804V28.054C9.20703 28.714 9.20703 29.0441 9.09919 29.3044C8.95542 29.6515 8.67962 29.9274 8.33249 30.0711C8.07215 30.179 7.74211 30.179 7.08203 30.179C6.42195 30.179 6.09191 30.179 5.83157 30.0711C5.48444 29.9274 5.20865 29.6515 5.06487 29.3044C4.95703 29.0441 4.95703 28.714 4.95703 28.054V23.804C4.95703 23.1439 4.95703 22.8138 5.06487 22.5535C5.20865 22.2064 5.48444 21.9306 5.83157 21.7868C6.09191 21.679 6.42195 21.679 7.08203 21.679C7.74211 21.679 8.07215 21.679 8.33249 21.7868C8.67962 21.9306 8.95542 22.2064 9.09919 22.5535C9.20703 22.8138 9.20703 23.1439 9.20703 23.804Z" stroke="#A3D2E0" stroke-width="2.125" stroke-linejoin="round" />
                    </svg>
                  </div>


                  <div class="c-archive-cases-results__highlight-content">
                    <?php if ($h_text): ?>
                      <p class="c-archive-cases-results__highlight-number">
                        <?php echo esc_html($h_text); ?>
                      </p>
                    <?php endif; ?>
                    <?php if ($h_desc): ?>
                      <p class="c-archive-cases-results__highlight-label">
                        <?php echo esc_html($h_desc); ?>
                      </p>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>

            </div>

            <?php if ($t_text): ?>
              <blockquote class="c-archive-cases-results__quote">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="21" viewBox="0 0 28 21" fill="none">
                  <path d="M0 21V15.4737C0 13.7953 0.296784 12.0146 0.890351 10.1316C1.50439 8.22807 2.3845 6.3962 3.5307 4.63597C4.69737 2.85526 6.09941 1.30994 7.73684 0L11.6667 3.19298C10.3772 5.03509 9.25146 6.95906 8.28947 8.96491C7.34795 10.9503 6.87719 13.0789 6.87719 15.3509V21H0ZM15.7193 21V15.4737C15.7193 13.7953 16.0161 12.0146 16.6096 10.1316C17.2237 8.22807 18.1038 6.3962 19.25 4.63597C20.4167 2.85526 21.8187 1.30994 23.4561 0L27.386 3.19298C26.0965 5.03509 24.9708 6.95906 24.0088 8.96491C23.0672 10.9503 22.5965 13.0789 22.5965 15.3509V21H15.7193Z" fill="#A3D2E0" />
                </svg>
                <?php echo wpautop(wp_kses_post($t_text)); ?>
              </blockquote>
            <?php endif; ?>


            <?php if ($t_author || $t_role || $author_image_id): ?>
              <div class="c-archive-cases-results__author">

                <?php if ($author_image_id): ?>
                  <div class="c-archive-cases-results__author-photo" aria-hidden="true">
                    <?php
                    echo wp_get_attachment_image(
                      $author_image_id,
                      'thumbnail',
                      false,
                      [
                        'class' => 'c-archive-cases-results__author-photo-img',
                        'loading' => 'lazy',
                        'decoding' => 'async',
                      ]
                    );
                    ?>
                  </div>
                <?php endif; ?>

                <div class="c-archive-cases-results__author-info">
                  <?php if ($t_author): ?>
                    <p class="c-archive-cases-results__author-name">
                      <?php echo esc_html($t_author); ?>
                    </p>
                  <?php endif; ?>

                  <?php if ($t_role): ?>
                    <p class="c-archive-cases-results__author-role text__normal">
                      <?php echo esc_html($t_role); ?>
                    </p>
                  <?php endif; ?>
                </div>

              </div>
            <?php endif; ?>

            <div class="c-archive-cases-results__cta">
              <a class="button button__blue c-archive-cases-results__button" href="<?php echo esc_url($permalink); ?>">
                <?php echo esc_html__('Veja mais', 'textdomain'); ?>
              </a>
            </div>
          </article>

        <?php endwhile; ?>
      <?php else: ?>
        <p class="text__normal c-archive-cases-results__empty">
          <?php echo esc_html__('Nenhum case encontrado para os filtros selecionados.', 'textdomain'); ?>
        </p>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php wp_reset_postdata(); ?>