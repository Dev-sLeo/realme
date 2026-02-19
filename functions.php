<?php
add_theme_support('post-thumbnails');
add_theme_support('post-templates');
add_theme_support('title-tag');
add_theme_support('custom-logo');
add_filter('wpcf7_autop_or_not', '__return_false');

define('THEMELIB', TEMPLATEPATH . '/extension');
define('COMPOSER', TEMPLATEPATH . '/vendor');
$composer_autoload = COMPOSER . '/autoload.php';
if (file_exists($composer_autoload)) {
  require_once $composer_autoload;
} else {
  error_log('[convida] Composer autoload not found: ' . $composer_autoload . '. Run `composer install` in theme root.');
  add_action('admin_notices', function () use ($composer_autoload) {
    echo '<div class="notice notice-error"><p><strong>Theme dependency error:</strong> composer autoload not found: <code>' . esc_html($composer_autoload) . '</code>. Run <code>composer install</code> in the theme root.</p></div>';
  });
}

global $qdi_config;

$root = dirname(__FILE__);
$qdi_config_path = implode(DIRECTORY_SEPARATOR, array($root, 'config'));
$qdi_config = \Qdi\WP\Utils::load_config($qdi_config_path);
\Qdi\WP\Theme::initialize($qdi_config);

$theme_version = wp_get_theme();
$theme_version = $theme_version->get('Version');

define('PATHS_INC', TEMPLATEPATH . '/includes');
define('PATHS_SVG', PATHS_INC . '/svg');
define('PATHS_PARTIALS', PATHS_INC . '/partials');
define('THEME_VERSION', $theme_version);
define('ENV', $qdi_config['env']['env']);
define('FB_APP_ID', $qdi_config['env']['fb_app_id']);
define('FB_ACCESS_TOLKEN', $qdi_config['env']['fb_access_tolken']);
define('LIB', TEMPLATEPATH . '/lib');

// Functions
require_once(THEMELIB . '/helpers.php');
require_once(THEMELIB . '/theme-customizer.php');
require_once(THEMELIB . '/query-filters.php');
require_once(THEMELIB . '/ajax.php');
require_once(LIB . '/parsedown/Parsedown.php');

global $tpl_engine;
global $cache_engine;
global $gpi_settings;


$gpi_settings = get_theme_mod('cpssu_general_theme_settings');

function gpi_setting($key)
{
  global $gpi_settings;

  if (empty($gpi_settings) || !is_array($gpi_settings)) {
    return false;
  }

  if (!array_key_exists($key, $gpi_settings)) {
    return false;
  }

  return $gpi_settings[$key];
}

$tpl_engine = new \Qdi\WP\Template(array(
  'base_path' => PATHS_INC
));

$cache_engine = new \Qdi\WP\Cache(array(
  'prefix' => '_gpi_cache',
  'debug' => false
));

function register_site_scripts()
{
  if (is_admin()) {
    return false;
  }

  $main_queue = null;
  $public_js_dir = get_template_directory_uri();
  $public_js_dir .= '/public/js/';

  $main_js = (ENV == 'production') ? 'app.min.js' : 'app.min.js';
  $main_js = $public_js_dir . $main_js;
  wp_enqueue_script('main_js', $main_js, $main_queue, THEME_VERSION, true);

  $js_vars = array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'env' => ENV,
    'homeUrl' => home_url(),
  );
  wp_localize_script('main_js', 'phpVars', $js_vars);
  wp_localize_script(
    'main_js',
    'usAjax',
    [
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce'   => wp_create_nonce('ajax_nonce')
    ]
  );
}
add_action('wp_enqueue_scripts', 'register_site_scripts');

add_action('init', 'wp_snippet_author_base');
function wp_snippet_author_base()
{
  global $wp_rewrite;
  $author_slug = 'autor'; // the new slug name
  $wp_rewrite->author_base = $author_slug;
}

function cc_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


function gpi_theme_setup()
{
  register_nav_menus(array(
    'header' => 'Header Menu principal',
    'footer' => 'Footer Menu',
    'footer2' => __('Footer Menu 2', 'theme'),
    'footer3' => __('Footer Menu 3', 'theme'),
    'footer4' => __('Footer Menu 4', 'theme'),
  ));
}

add_action('after_setup_theme', 'gpi_theme_setup');

if ($GLOBALS['pagenow'] === 'wp-login.php') {
  ob_start();
}

$is_builder = false;
if (array_key_exists('fl_builder', $_GET)) {
  $is_builder = true;
}

add_action('login_form', function ($args) {
  $login = ob_get_contents();
  ob_clean();
  $login = str_replace('id="user_pass"', 'id="user_pass" autocomplete="off"', $login);
  $login = str_replace('id="user_login"', 'id="user_login" autocomplete="off"', $login);
  echo $login;
}, 9999);

add_filter('xmlrpc_methods', function ($methods) {
  unset($methods['pingback.ping']);
  return $methods;
});

function processarArquivo($urlArquivo)
{
  $extensao = pathinfo($urlArquivo, PATHINFO_EXTENSION);
  if (strtolower($extensao) === 'svg') {
    $conteudo = file_get_contents($urlArquivo);
    return $conteudo;
  } else {
    return $urlArquivo;
  }
}

function gerar_slug($string)
{
  $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  $slug = strtolower($slug);
  $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
  $slug = trim($slug, '-');
  return $slug;
}

add_filter('acf/settings/save_json', function ($path) {
  return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
  unset($paths[0]);
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
});


function render_media_image($imagem, $size = 'full', $attrs = [])
{
  // Normaliza inputs
  if (is_numeric($imagem)) {
    $id      = intval($imagem);
    $url     = wp_get_attachment_image_url($id, $size);
    $mime    = get_post_mime_type($id);
    $alt     = get_post_meta($id, '_wp_attachment_image_alt', true);
  } elseif (is_array($imagem)) {
    $id      = $imagem['id'] ?? null;
    $url     = $imagem['url'] ?? ($id ? wp_get_attachment_image_url($id, $size) : null);
    $mime    = $id ? get_post_mime_type($id) : null;
    $alt     = $imagem['alt'] ?? ($id ? get_post_meta($id, '_wp_attachment_image_alt', true) : '');
  } elseif (is_string($imagem)) {
    $id      = attachment_url_to_postid($imagem);
    $url     = $imagem;
    $mime    = $id ? get_post_mime_type($id) : null;
    $alt     = $id ? get_post_meta($id, '_wp_attachment_image_alt', true) : '';
  } else {
    return '';
  }

  if (!$url) return '';

  $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
  $is_svg = ($mime === 'image/svg+xml' || $ext === 'svg');

  // Monta atributos extras (para SVG inline também)
  $attr_html = '';
  foreach ($attrs as $key => $value) {
    $attr_html .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
  }

  /**
   * SVG → inline
   */
  if ($is_svg) {
    $svg = @file_get_contents($url);

    if (!$svg) {
      return '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '"' . $attr_html . ' loading="lazy">';
    }

    // Adiciona alt como title se não existir
    if ($alt && !preg_match('/<title>/', $svg)) {
      $svg = preg_replace(
        '/<svg([^>]*)>/',
        '<svg$1><title>' . esc_html($alt) . '</title>',
        $svg,
        1
      );
    }

    // Insere atributos do usuário no <svg>
    return preg_replace(
      '/<svg/',
      '<svg' . $attr_html,
      $svg,
      1
    );
  }

  /**
   * Raster → usa wp_get_attachment_image com lazy loading
   */
  $img_attrs = array_merge([
    'alt'     => $alt,
    'loading' => 'lazy',
  ], $attrs);

  return wp_get_attachment_image($id, $size, false, $img_attrs);
}

function render_hero_background_image($image, array $attrs = []): string
{
  $image_source = $image;

  // Permite receber a estrutura completa do ACF ou apenas o ID
  if (is_array($image) && array_key_exists('ID', $image)) {
    $image_source = $image['ID'];
  }

  if (empty($image_source)) {
    return '';
  }

  return render_media_image($image_source, 'full', get_hero_media_attributes($attrs));
}

/**
 * Estrelas estilo Google com preenchimento parcial usando o SVG fornecido.
 */
if (!function_exists('convida_render_stars')) {
  function convida_render_stars($rating = 0, $args = [])
  {
    $rating = floatval($rating);
    if ($rating < 0) $rating = 0;
    if ($rating > 5) $rating = 5;

    $size  = isset($args['size']) ? intval($args['size']) : 26;
    $gap   = isset($args['gap']) ? intval($args['gap']) : 4;
    $bg    = $args['bg']   ?? '#E6E6E6';  // estrela vazia
    $fill  = $args['fill'] ?? '#E6B100';  // dourado (igual seu SVG)
    $class = $args['class'] ?? 'c-rating';

    $path = 'M12.6329 0L15.6151 9.17834H25.2658L17.4582 14.8509L20.4404 24.0292L12.6329 18.3567L4.82531 24.0292L7.80753 14.8509L-2.95639e-05 9.17834H9.65065L12.6329 0Z';

    $uid = 'star_' . substr(md5(uniqid('', true)), 0, 10);

    ob_start(); ?>
    <div class="<?= esc_attr($class); ?>"
      aria-label="<?= esc_attr(sprintf('Avaliação %.1f de 5', $rating)); ?>"
      style="display:flex;align-items:center;gap:<?= esc_attr($gap); ?>px;">
      <?php for ($i = 1; $i <= 5; $i++) :
        $p = ($rating - ($i - 1));
        if ($p < 0) $p = 0;
        if ($p > 1) $p = 1;
        $percent = $p * 100;

        $clipId = $uid . '_clip_' . $i;
        $w = ($percent / 100) * 26; // viewBox width
      ?>
        <svg width="<?= esc_attr($size); ?>" height="<?= esc_attr($size); ?>"
          viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg"
          role="img" aria-hidden="true" style="display:block;flex:0 0 auto;">
          <defs>
            <clipPath id="<?= esc_attr($clipId); ?>">
              <path d="<?= esc_attr($path); ?>" />
            </clipPath>
          </defs>

          <!-- Base (vazia) -->
          <path d="<?= esc_attr($path); ?>" fill="<?= esc_attr($bg); ?>" />

          <!-- Preenchimento parcial (estilo Google) -->
          <g clip-path="url(#<?= esc_attr($clipId); ?>)">
            <rect x="0" y="0" width="<?= esc_attr($w); ?>" height="25" fill="<?= esc_attr($fill); ?>" />
          </g>
        </svg>
      <?php endfor; ?>
    </div>
  <?php
    return ob_get_clean();
  }
}

function theme_testimonials_build_query_args($filter)
{
  $args = [
    'post_type'      => 'depoimento',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ];

  $filter = is_string($filter) ? sanitize_title($filter) : '';

  if ($filter !== '') {
    $args['tax_query'] = [
      [
        'taxonomy' => 'tipo',
        'field'    => 'slug',
        'terms'    => [$filter],
      ],
    ];
  }

  return $args;
}

function theme_testimonials_render_slide($post_id)
{
  $quote   = (string) get_field('texto', $post_id);

  $empresa = get_field('empresa', $post_id);
  $empresa = is_array($empresa) ? $empresa : [];

  $foto   = (!empty($empresa['foto']) && is_array($empresa['foto'])) ? $empresa['foto'] : null;
  $nome   = isset($empresa['nome']) ? (string) $empresa['nome'] : '';
  $estado = isset($empresa['estado']) ? (string) $empresa['estado'] : '';
  $logo   = (!empty($empresa['logo']) && is_array($empresa['logo'])) ? $empresa['logo'] : null;

  $foto_id  = (!empty($foto['ID'])) ? (int) $foto['ID'] : 0;
  $foto_alt = (!empty($foto['alt'])) ? (string) $foto['alt'] : '';

  $logo_id  = (!empty($logo['ID'])) ? (int) $logo['ID'] : 0;
  $logo_alt = (!empty($logo['alt'])) ? (string) $logo['alt'] : '';

  if ($quote === '' && $nome === '' && $estado === '' && !$foto_id && !$logo_id) {
    return '';
  }

  ob_start(); ?>
  <div class="swiper-slide o-testimonials__slide">
    <article class="c-testimonial o-testimonials__card">
      <?php if ($quote !== '') : ?>
        <blockquote class="c-testimonial__quote"><?php echo $quote; ?></blockquote>
      <?php endif; ?>
      <div class="c-testimonial__information">
        <div class="c-testimonial__meta">
          <?php if ($foto_id) : ?>
            <div class="c-testimonial__avatar">
              <?php
              echo wp_get_attachment_image(
                $foto_id,
                'thumbnail',
                false,
                [
                  'class'    => 'c-testimonial__avatar-img',
                  'alt'      => $foto_alt,
                  'loading'  => 'lazy',
                  'decoding' => 'async',
                ]
              );
              ?>
            </div>
          <?php endif; ?>

          <div class="c-testimonial__who">
            <?php if ($nome !== '') : ?>
              <div class="c-testimonial__name"><?php echo esc_html($nome); ?></div>
            <?php endif; ?>
            <?php if ($estado !== '') : ?>
              <div class="c-testimonial__place"><?php echo esc_html($estado); ?></div>
            <?php endif; ?>
          </div>
        </div>

        <?php if ($logo_id) : ?>
          <div class="c-testimonial__logo">
            <?php
            echo wp_get_attachment_image(
              $logo_id,
              'medium',
              false,
              [
                'class'    => 'c-testimonial__logo-img',
                'alt'      => $logo_alt,
                'loading'  => 'lazy',
                'decoding' => 'async',
              ]
            );
            ?>
          </div>
        <?php endif; ?>
      </div>
    </article>
  </div>
<?php
  return (string) ob_get_clean();
}

function theme_ajax_testimonials_filter()
{
  check_ajax_referer('testimonials_filter', 'nonce');

  $filter = isset($_POST['filter']) ? (string) wp_unslash($_POST['filter']) : '';
  $args   = theme_testimonials_build_query_args($filter);

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
      'found_posts'   => (int) $q->found_posts,
      'max_pages'     => (int) $q->max_num_pages,
      'current_page'  => 1,
      'has_more'      => false,
      'filter'        => sanitize_title($filter),
    ],
  ]);
}

add_action('wp_ajax_testimonials_filter', 'theme_ajax_testimonials_filter');
add_action('wp_ajax_nopriv_testimonials_filter', 'theme_ajax_testimonials_filter');
