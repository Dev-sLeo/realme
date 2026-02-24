<?php
$data = isset($data) && is_array($data) ? $data : get_field('case_content');

if (empty($data)) {
  return;
}

$title       = $data['title'] ?? '';
$description = $data['description'] ?? '';
$file_field  = $data['file'] ?? ''; // ACF: Arquivo (pode retornar array, ID ou URL)
$challenge   = $data['challenge'] ?? '';
$solution    = $data['solution'] ?? '';
$results     = $data['results'] ?? [];

/**
 * Normaliza retorno do ACF File:
 * - array (url, id, mime_type, title, filename...)
 * - ID (int)
 * - URL (string)
 */
$file = [
  'url'  => '',
  'id'   => 0,
  'mime' => '',
  'name' => '',
];

if (is_array($file_field)) {
  $file['url']  = $file_field['url'] ?? '';
  $file['id']   = isset($file_field['id']) ? (int) $file_field['id'] : 0;
  $file['mime'] = $file_field['mime_type'] ?? '';
  $file['name'] = $file_field['title'] ?? ($file_field['filename'] ?? '');
} elseif (is_numeric($file_field)) {
  $file['id']  = (int) $file_field;
  $file['url'] = wp_get_attachment_url($file['id']) ?: '';
  $file['mime'] = $file['id'] ? (get_post_mime_type($file['id']) ?: '') : '';
  $file['name'] = $file['id'] ? (get_the_title($file['id']) ?: '') : '';
} elseif (is_string($file_field) && $file_field) {
  $file['url'] = $file_field;
}

/**
 * Se ainda não temos mime e temos ID, tenta buscar pelo ID.
 * Se não tiver ID mas tiver URL, tenta inferir via attachment_url_to_postid.
 */
if (!$file['mime']) {
  $maybe_id = $file['id'];

  if (!$maybe_id && $file['url']) {
    $maybe_id = attachment_url_to_postid($file['url']);
    if ($maybe_id) {
      $file['id'] = (int) $maybe_id;
      if (!$file['name']) {
        $file['name'] = get_the_title($file['id']) ?: '';
      }
    }
  }

  if ($file['id']) {
    $file['mime'] = get_post_mime_type($file['id']) ?: '';
  }
}

$has_header  = ($title || $description);
$has_file    = !empty($file['url']);
$has_body    = ($challenge || $solution);
$has_results = (!empty($results) && is_array($results));

$is_image = $has_file && is_string($file['mime']) && str_starts_with($file['mime'], 'image/');
$is_video = $has_file && is_string($file['mime']) && str_starts_with($file['mime'], 'video/');

// fallback: se mime vazio mas URL tem extensão comum
if ($has_file && !$is_image && !$is_video && empty($file['mime'])) {
  $ext = strtolower(pathinfo(parse_url($file['url'], PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
  $is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
  $is_video = in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'm4v'], true);
}
?>

<section class="c-case-content">
  <div class="s-container">

    <?php if ($has_header): ?>
      <header class="c-case-content__header">
        <?php if ($title): ?>
          <h1 class="title__normal c-case-content__title">
            <?php echo esc_html($title); ?>
          </h1>
        <?php endif; ?>

        <?php if ($description): ?>
          <p class="text__normal c-case-content__description">
            <?php echo esc_html($description); ?>
          </p>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <?php if ($has_file): ?>
      <div class="c-case-content__media">
        <?php if ($is_video): ?>
          <div class="c-case-content__video">
            <video class="c-case-content__video-el" controls playsinline preload="metadata">
              <source src="<?php echo esc_url($file['url']); ?>" type="<?php echo esc_attr($file['mime'] ?: 'video/mp4'); ?>">
              <?php echo esc_html__('Seu navegador não suporta vídeo.', 'textdomain'); ?>
            </video>
          </div>

        <?php elseif ($is_image): ?>
          <figure class="c-case-content__image">
            <img
              class="c-case-content__image-el"
              src="<?php echo esc_url($file['url']); ?>"
              alt="<?php echo esc_attr($file['name'] ?: $title ?: __('Imagem do case', 'textdomain')); ?>"
              loading="lazy"
              decoding="async">
          </figure>

        <?php else: ?>
          <div class="c-case-content__file">
            <a class="c-case-content__file-link"
              href="<?php echo esc_url($file['url']); ?>"
              target="_blank"
              rel="noopener">
              <?php echo esc_html($file['name'] ?: __('Abrir arquivo', 'textdomain')); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="c-case-content__grid">

      <div class="c-case-content__main">
        <?php if ($has_body): ?>
          <div class="c-case-content__sections">

            <?php if ($challenge): ?>
              <section class="c-case-content__section c-case-content__section--challenge">
                <h2 class="c-case-content__section-title">
                  <?php echo esc_html__('O Desafio', 'textdomain'); ?>
                </h2>
                <div class="c-case-content__section-text">
                  <?php echo wpautop(wp_kses_post($challenge)); ?>
                </div>
              </section>
            <?php endif; ?>

            <?php if ($solution): ?>
              <section class="c-case-content__section c-case-content__section--solution">
                <h2 class="c-case-content__section-title">
                  <?php echo esc_html__('A Solução', 'textdomain'); ?>
                </h2>
                <div class="c-case-content__section-text ">
                  <?php echo wpautop(wp_kses_post($solution)); ?>
                </div>
              </section>
            <?php endif; ?>

          </div>
        <?php endif; ?>
      </div>

      <?php if ($has_results): ?>
        <aside class="c-case-content__aside"
          aria-label="<?php echo esc_attr__('Resultados em números', 'textdomain'); ?>">
          <h2 class="c-case-content__aside-title">
            <?php echo esc_html__('Resultados em Números', 'textdomain'); ?>
          </h2>

          <div class="c-case-content__results">
            <?php foreach ($results as $row):
              if (!is_array($row)) continue;

              $number = $row['number'] ?? '';
              $label  = $row['label'] ?? '';

              if (!$number && !$label) continue;
            ?>
              <div class="c-case-content__result">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34" fill="none">
                  <path d="M29.043 14.875V27.625C29.043 28.285 29.043 28.6151 28.9352 28.8755C28.7914 29.2226 28.5155 29.4984 28.1685 29.6422C27.9081 29.75 27.578 29.75 26.918 29.75C26.2579 29.75 25.9279 29.75 25.6675 29.6422C25.3204 29.4984 25.0446 29.2226 24.9008 28.8755C24.793 28.6151 24.793 28.285 24.793 27.625V14.875C24.793 14.215 24.793 13.8849 24.9008 13.6245C25.0446 13.2774 25.3204 13.0016 25.6675 12.8578C25.9279 12.75 26.2579 12.75 26.918 12.75C27.578 12.75 27.9081 12.75 28.1685 12.8578C28.5155 13.0016 28.7914 13.2774 28.9352 13.6245C29.043 13.8849 29.043 14.215 29.043 14.875Z" stroke="#A3D2E0" stroke-width="2.125" stroke-linejoin="round" />
                  <path d="M23.375 4.25H27.625V8.5" stroke="#A3D2E0" stroke-width="2.125" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M26.9167 4.95825C26.9167 4.95825 21.25 12.0416 6.375 16.9999" stroke="#A3D2E0" stroke-width="2.125" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M19.125 19.8333V27.6249C19.125 28.2849 19.125 28.615 19.0172 28.8754C18.8734 29.2225 18.5976 29.4983 18.2505 29.6421C17.9901 29.7499 17.66 29.7499 17 29.7499C16.34 29.7499 16.0099 29.7499 15.7495 29.6421C15.4024 29.4983 15.1266 29.2225 14.9828 28.8754C14.875 28.615 14.875 28.2849 14.875 27.6249V19.8333C14.875 19.1732 14.875 18.8431 14.9828 18.5828C15.1266 18.2357 15.4024 17.9599 15.7495 17.8161C16.0099 17.7083 16.34 17.7083 17 17.7083C17.66 17.7083 17.9901 17.7083 18.2505 17.8161C18.5976 17.9599 18.8734 18.2357 19.0172 18.5828C19.125 18.8431 19.125 19.1732 19.125 19.8333Z" stroke="#A3D2E0" stroke-width="2.125" stroke-linejoin="round" />
                  <path d="M9.20703 23.375V27.625C9.20703 28.285 9.20703 28.6151 9.09919 28.8755C8.95542 29.2226 8.67962 29.4984 8.33249 29.6422C8.07215 29.75 7.74211 29.75 7.08203 29.75C6.42195 29.75 6.09191 29.75 5.83157 29.6422C5.48444 29.4984 5.20865 29.2226 5.06487 28.8755C4.95703 28.6151 4.95703 28.285 4.95703 27.625V23.375C4.95703 22.715 4.95703 22.3849 5.06487 22.1245C5.20865 21.7774 5.48444 21.5016 5.83157 21.3578C6.09191 21.25 6.42195 21.25 7.08203 21.25C7.74211 21.25 8.07215 21.25 8.33249 21.3578C8.67962 21.5016 8.95542 21.7774 9.09919 22.1245C9.20703 22.3849 9.20703 22.715 9.20703 23.375Z" stroke="#A3D2E0" stroke-width="2.125" stroke-linejoin="round" />
                </svg>
                <div class="c-case-content__result-inner">
                  <?php if ($number): ?>
                    <p class="c-case-content__result-number">
                      <?php echo esc_html($number); ?>
                    </p>
                  <?php endif; ?>
                  <?php if ($label): ?>
                    <p class="c-case-content__result-label">
                      <?php echo esc_html($label); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </aside>
      <?php endif; ?>

    </div>

  </div>
</section>