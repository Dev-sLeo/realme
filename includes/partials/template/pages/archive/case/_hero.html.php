<?php global $tpl_engine; ?>
<?php
$data = isset($data) && is_array($data) ? $data : get_field('hero_cases');

if (empty($data) || !is_array($data)) {
  return;
}

$subtitulo = $data['subtitulo'] ?? '';
$titulo = $data['titulo'] ?? '';
$descricao = $data['descricao'] ?? '';
$cases = $data['cases'] ?? [];

if (!$subtitulo && !$titulo && !$descricao && (empty($cases) || !is_array($cases))) {
  return;
}
?>

<section class="c-archive-cases-hero">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-archive-cases.webp" alt="">
  </div>
  <div class="s-container">
    <?php if ($subtitulo || $titulo || $descricao): ?>
      <header class="c-archive-cases-hero__header">
        <?php if ($subtitulo): ?><p class="subtitulo c-archive-cases-hero__subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($subtitulo); ?></p><?php endif; ?>
        <?php if ($titulo): ?><h1 class="title__super c-archive-cases-hero__title" data-animate="fade-up" data-animate-delay="0.2"><?php echo esc_html($titulo); ?></h1><?php endif; ?>
        <?php if ($descricao): ?><p class="text__normal c-archive-cases-hero__description" data-animate="fade-up" data-animate-delay="0.3"><?php echo esc_html($descricao); ?></p><?php endif; ?>
      </header>
    <?php endif; ?>
  </div>

  <?php if (!empty($cases) && is_array($cases)): ?>
    <div class="c-archive-cases-hero__slider" data-animate="fade-up" data-animate-delay="0.4">
      <div class="splide c-archive-cases-hero__splide js-archive-cases-hero-splide">
        <div class="splide__track">
          <ul class="splide__list">
            <?php foreach ($cases as $item):
              if (!is_array($item)) {
                continue;
              }

              $nome_case = $item['nome_case'] ?? '';
              $local = $item['local'] ?? '';
              $numero = $item['numeroporcentagem'] ?? '';
              $desc_num = $item['descricao_porcetagem'] ?? '';
              $foto = $item['foto'] ?? null;
              $nome = $item['nome'] ?? '';
              $cargo = $item['cargo'] ?? '';

              $foto_id = $foto ? (is_array($foto) ? ($foto['ID'] ?? null) : $foto) : null;

              if (!$nome_case && !$local && !$numero && !$desc_num && !$foto_id && !$nome && !$cargo) {
                continue;
              }
            ?>
              <div class="splide__slide">
                <article class="c-archive-cases-hero__card">
                  <div class="c-archive-cases-hero__card-top">
                    <div class="c-archive-cases-hero__card-company">
                      <?php if ($nome_case): ?><p class="c-archive-cases-hero__company-name"><?php echo esc_html($nome_case); ?></p><?php endif; ?>
                      <?php if ($local): ?><p class=" c-archive-cases-hero__company-location"><?php echo esc_html($local); ?></p><?php endif; ?>
                    </div>

                    <?php if ($numero || $desc_num): ?>
                      <div class="c-archive-cases-hero__metric">
                        <?php if ($numero): ?>
                          <p class="c-archive-cases-hero__metric-number">
                            <?php $tpl_engine->svg('icons/chart') ?>
                            <?= esc_html($numero); ?>
                          </p>
                        <?php endif; ?>
                        <?php if ($desc_num): ?><p class=" c-archive-cases-hero__metric-label"><?php echo esc_html($desc_num); ?></p><?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <?php if ($nome || $cargo || $foto_id): ?>
                    <div class="c-archive-cases-hero__author">
                      <?php if ($foto_id): ?><div class="c-archive-cases-hero__author-photo"><?php echo wp_get_attachment_image($foto_id, 'thumbnail', false, ['class' => 'c-archive-cases-hero__author-img']); ?></div><?php endif; ?>
                      <div class="c-archive-cases-hero__author-info">
                        <?php if ($nome): ?><p class="c-archive-cases-hero__author-name"><?php echo esc_html($nome); ?></p><?php endif; ?>
                        <?php if ($cargo): ?><p class=" c-archive-cases-hero__author-role"><?php echo esc_html($cargo); ?></p><?php endif; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </article>
              </div>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <svg xmlns="http://www.w3.org/2000/svg" width="132" height="132" viewBox="0 0 132 132" fill="none" class="arrow-down-section">
    <g filter="url(#filter0_d_371_21099)">
      <circle cx="65.6289" cy="49.814" r="34" fill="white" />
    </g>
    <path d="M73.535 48.2325L66.0233 55.7442L58.5117 48.2325" stroke="#85C3D6" stroke-width="1.5814" stroke-linecap="round" stroke-linejoin="round" />
    <defs>
      <filter id="filter0_d_371_21099" x="0.000999451" y="1.14441e-05" width="131.256" height="131.256" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
        <feFlood flood-opacity="0" result="BackgroundImageFix" />
        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
        <feOffset dy="15.814" />
        <feGaussianBlur stdDeviation="15.814" />
        <feComposite in2="hardAlpha" operator="out" />
        <feColorMatrix type="matrix" values="0 0 0 0 0.301961 0 0 0 0 0.380392 0 0 0 0 0.67451 0 0 0 0.2 0" />
        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_371_21099" />
        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_371_21099" result="shape" />
      </filter>
    </defs>
  </svg>

</section>