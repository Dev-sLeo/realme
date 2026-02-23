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
  <div class="o-container">
    <?php if ($subtitulo || $titulo || $descricao): ?>
      <header class="c-archive-cases-hero__header">
        <?php if ($subtitulo): ?><p class="subtitulo c-archive-cases-hero__subtitulo"><?php echo esc_html($subtitulo); ?></p><?php endif; ?>
        <?php if ($titulo): ?><h1 class="title__super c-archive-cases-hero__title"><?php echo esc_html($titulo); ?></h1><?php endif; ?>
        <?php if ($descricao): ?><p class="text__normal c-archive-cases-hero__description"><?php echo esc_html($descricao); ?></p><?php endif; ?>
      </header>
    <?php endif; ?>
  </div>

  <?php if (!empty($cases) && is_array($cases)): ?>
    <div class="c-archive-cases-hero__slider">
      <div class="swiper c-archive-cases-hero__swiper js-archive-cases-hero-swiper" data-swiper="archive-cases-hero">
        <div class="swiper-wrapper">
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
            <div class="swiper-slide">
              <article class="c-archive-cases-hero__card">
                <div class="c-archive-cases-hero__card-top">
                  <div class="c-archive-cases-hero__card-company">
                    <?php if ($nome_case): ?><p class="c-archive-cases-hero__company-name"><?php echo esc_html($nome_case); ?></p><?php endif; ?>
                    <?php if ($local): ?><p class="text__normal c-archive-cases-hero__company-location"><?php echo esc_html($local); ?></p><?php endif; ?>
                  </div>

                  <?php if ($numero || $desc_num): ?>
                    <div class="c-archive-cases-hero__metric">
                      <?php if ($numero): ?><p class="title__normal c-archive-cases-hero__metric-number"><?php echo esc_html($numero); ?></p><?php endif; ?>
                      <?php if ($desc_num): ?><p class="text__normal c-archive-cases-hero__metric-label"><?php echo esc_html($desc_num); ?></p><?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>

                <?php if ($nome || $cargo || $foto_id): ?>
                  <div class="c-archive-cases-hero__author">
                    <?php if ($foto_id): ?><div class="c-archive-cases-hero__author-photo"><?php echo wp_get_attachment_image($foto_id, 'thumbnail', false, ['class' => 'c-archive-cases-hero__author-img']); ?></div><?php endif; ?>
                    <div class="c-archive-cases-hero__author-info">
                      <?php if ($nome): ?><p class="c-archive-cases-hero__author-name"><?php echo esc_html($nome); ?></p><?php endif; ?>
                      <?php if ($cargo): ?><p class="text__normal c-archive-cases-hero__author-role"><?php echo esc_html($cargo); ?></p><?php endif; ?>
                    </div>
                  </div>
                <?php endif; ?>
              </article>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</section>
