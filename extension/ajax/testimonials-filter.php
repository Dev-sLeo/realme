<?php
function theme_testimonials_build_query_args($filter)
{
  $args = [
    'post_type' => 'depoimento',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
  ];

  $filter = is_string($filter) ? sanitize_title($filter) : '';

  if ($filter !== '') {
    $args['tax_query'] = [
      [
        'taxonomy' => 'tipo',
        'field' => 'slug',
        'terms' => [$filter],
      ],
    ];
  }

  return $args;
}
function theme_testimonials_render_slide($post_id)
{
  $quote = (string)get_field('texto', $post_id);

  $empresa = get_field('empresa', $post_id);
  $empresa = is_array($empresa) ? $empresa : [];

  $foto = (!empty($empresa['foto']) && is_array($empresa['foto'])) ? $empresa['foto'] : null;
  $nome = isset($empresa['nome']) ? (string)$empresa['nome'] : '';
  $estado = isset($empresa['estado']) ? (string)$empresa['estado'] : '';
  $logo = (!empty($empresa['logo']) && is_array($empresa['logo'])) ? $empresa['logo'] : null;

  $foto_id = (!empty($foto['ID'])) ? (int)$foto['ID'] : 0;
  $foto_alt = (!empty($foto['alt'])) ? (string)$foto['alt'] : '';

  $logo_id = (!empty($logo['ID'])) ? (int)$logo['ID'] : 0;
  $logo_alt = (!empty($logo['alt'])) ? (string)$logo['alt'] : '';

  if ($quote === '' && $nome === '' && $estado === '' && !$foto_id && !$logo_id) {
    return '';
  }

  ob_start(); ?>
  <div class="swiper-slide o-testimonials__slide">
    <article class="c-testimonial o-testimonials__card">
      <?php if ($quote !== ''): ?>
        <blockquote class="c-testimonial__quote"><?php echo $quote; ?></blockquote>
      <?php
  endif; ?>
      <div class="c-testimonial__information">
        <div class="c-testimonial__meta">
          <?php if ($foto_id): ?>
            <div class="c-testimonial__avatar">
              <?php
    echo wp_get_attachment_image(
      $foto_id,
      'thumbnail',
      false,
    [
      'class' => 'c-testimonial__avatar-img',
      'alt' => $foto_alt,
      'loading' => 'lazy',
      'decoding' => 'async',
    ]
    );
?>
            </div>
          <?php
  endif; ?>

          <div class="c-testimonial__who">
            <?php if ($nome !== ''): ?>
              <div class="c-testimonial__name"><?php echo esc_html($nome); ?></div>
            <?php
  endif; ?>
            <?php if ($estado !== ''): ?>
              <div class="c-testimonial__place"><?php echo esc_html($estado); ?></div>
            <?php
  endif; ?>
          </div>
        </div>

        <?php if ($logo_id): ?>
          <div class="c-testimonial__logo">
            <?php
    echo wp_get_attachment_image(
      $logo_id,
      'medium',
      false,
    [
      'class' => 'c-testimonial__logo-img',
      'alt' => $logo_alt,
      'loading' => 'lazy',
      'decoding' => 'async',
    ]
    );
?>
          </div>
        <?php
  endif; ?>
      </div>
    </article>
  </div>
<?php
  return (string)ob_get_clean();
}
function theme_ajax_testimonials_filter()
{
  check_ajax_referer('testimonials_filter', 'nonce');

  $filter = isset($_POST['filter']) ? (string)wp_unslash($_POST['filter']) : '';
  $args = theme_testimonials_build_query_args($filter);

  $q = new WP_Query($args);

  $html = '';
  if ($q->have_posts()) {
    while ($q->have_posts()) {
      $q->the_post();
      $html .= theme_testimonials_render_slide(get_the_ID());
    }
    wp_reset_postdata();
  }

  wp_send_json_success([
    'html' => $html,
    'meta' => [
      'found_posts' => (int)$q->found_posts,
      'max_pages' => (int)$q->max_num_pages,
      'current_page' => 1,
      'has_more' => false,
      'filter' => sanitize_title($filter),
    ],
  ]);
}

add_action('wp_ajax_testimonials_filter', 'theme_ajax_testimonials_filter');
add_action('wp_ajax_nopriv_testimonials_filter', 'theme_ajax_testimonials_filter');