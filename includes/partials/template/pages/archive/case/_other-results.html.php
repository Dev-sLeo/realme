<?php
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

$tax_cat = 'cat_case';
$tax_regiao = 'regiao';

$cat_terms = get_terms([
  'taxonomy' => $tax_cat,
  'hide_empty' => true,
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
  <div class="o-container">

    <?php if ($titulo): ?>
      <header class="c-archive-cases-results__header">
        <h2 class="title__normal c-archive-cases-results__title">
          <?php echo esc_html($titulo); ?>
        </h2>
      </header>
    <?php endif; ?>

    <form class="c-archive-cases-results__filters js-archive-cases-filters" method="get"
      action="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>">
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

      <div class="c-archive-cases-results__filters-right">
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

    <div class="c-archive-cases-results__grid js-archive-cases-grid" aria-live="polite" aria-busy="false">
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
                    <p class="c-archive-cases-results__company-name">
                      <?php echo esc_html($company); ?>
                    </p>
                  <?php endif; ?>
                  <?php if ($location): ?>
                    <p class="text__normal c-archive-cases-results__company-location">
                      <?php echo esc_html($location); ?>
                    </p>
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
                      <p class="title__normal c-archive-cases-results__highlight-number">
                        <?php echo esc_html($h_text); ?>
                      </p>
                    <?php endif; ?>
                    <?php if ($h_desc): ?>
                      <p class="text__normal c-archive-cases-results__highlight-label">
                        <?php echo esc_html($h_desc); ?>
                      </p>
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
                  <p class="c-archive-cases-results__author-name">
                    <?php echo esc_html($t_author); ?>
                  </p>
                <?php endif; ?>
                <?php if ($t_role): ?>
                  <p class="text__normal c-archive-cases-results__author-role">
                    <?php echo esc_html($t_role); ?>
                  </p>
                <?php endif; ?>
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

<script>
  (function () {
    var root = document.querySelector('.js-archive-cases-results');
    if (!root) return;

    var form = root.querySelector('.js-archive-cases-filters');
    var grid = root.querySelector('.js-archive-cases-grid');
    var catButtons = root.querySelectorAll('.js-cat-filter');
    var catInput = root.querySelector('.js-cat-input');
    var select = form ? form.querySelector('select[name="regiao"]') : null;

    var ajaxUrl = root.getAttribute('data-ajax-url');
    var postType = root.getAttribute('data-post-type');
    var taxCat = root.getAttribute('data-tax-cat');
    var taxRegiao = root.getAttribute('data-tax-regiao');
    var nonce = root.getAttribute('data-nonce');

    function setActiveCat(slug) {
      catButtons.forEach(function (btn) {
        var isActive = (btn.getAttribute('data-cat') || '') === (slug || '');
        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });
    }

    function fetchCases() {
      if (!grid) return;

      var cat = catInput ? (catInput.value || '') : '';
      var regiao = select ? (select.value || '') : '';

      grid.setAttribute('aria-busy', 'true');

      var body = new URLSearchParams();
      body.set('action', 'archive_cases_filter');
      body.set('nonce', nonce || '');
      body.set('post_type', postType || 'case');
      body.set('tax_cat', taxCat || 'cat_case');
      body.set('tax_regiao', taxRegiao || 'regiao');
      body.set('cat', cat);
      body.set('regiao', regiao);

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: body.toString()
      })
        .then(function (r) { return r.text(); })
        .then(function (html) {
          grid.innerHTML = html;
          grid.setAttribute('aria-busy', 'false');
        })
        .catch(function () {
          grid.setAttribute('aria-busy', 'false');
        });
    }

    catButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var slug = btn.getAttribute('data-cat') || '';
        if (catInput) catInput.value = slug;
        setActiveCat(slug);
        fetchCases();
      });
    });

    if (select) {
      select.addEventListener('change', function () {
        fetchCases();
      });
    }

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchCases();
      });
    }
  })();
</script>