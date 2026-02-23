<?php
$data = isset($data) && is_array($data) ? $data : get_field('cases_em_destaque');

if (empty($data) || !is_array($data)) {
  return;
}

$titulo = $data['titulo'] ?? '';
$slider = $data['slider'] ?? [];

if (!$titulo && (empty($slider) || !is_array($slider))) {
  return;
}
?>

<section class="c-cases-destaque">
  <div class="o-container">

    <?php if ($titulo): ?>
      <header class="c-cases-destaque__header">
        <h2 class="title__normal c-cases-destaque__title">
          <?php echo esc_html($titulo); ?>
        </h2>
      </header>
    <?php endif; ?>

    <?php if (!empty($slider) && is_array($slider)): ?>
      <div class="c-cases-destaque__slider splide js-cases-destaque-splide"
        data-splide='{"type":"slide","perPage":1,"gap":"24px","pagination":true,"arrows":true}'>
        <div class="splide__track">
          <div class="splide__list">
            <?php foreach ($slider as $item):
              if (!is_array($item)) {
                continue;
              }

              $logo = $item['logo'] ?? null;
              $nome_empresa = $item['nome_empresa'] ?? '';
              $cidade_estado = $item['cidade_estado'] ?? '';
              $depoimento = $item['depoimento'] ?? '';
              $foto_autor = $item['foto_autor'] ?? null;
              $nome_autor = $item['nome_autor'] ?? '';
              $cargo_autor = $item['cargo_autor'] ?? '';
              $botao = $item['botao'] ?? null;

              $logo_id = null;
              if ($logo) {
                $logo_id = is_array($logo) ? ($logo['ID'] ?? null) : $logo;
              }

              $autor_id = null;
              if ($foto_autor) {
                $autor_id = is_array($foto_autor) ? ($foto_autor['ID'] ?? null) : $foto_autor;
              }

              $btn_url = '';
              $btn_title = '';
              $btn_target = '';
              if (is_array($botao)) {
                $btn_url = $botao['url'] ?? '';
                $btn_title = $botao['title'] ?? '';
                $btn_target = $botao['target'] ?? '';
              }

              if (!$logo_id && !$nome_empresa && !$cidade_estado && !$depoimento && !$autor_id && !$nome_autor && !$cargo_autor && !$btn_url) {
                continue;
              }
              ?>
              <div class="splide__slide">
                <article class="c-cases-destaque__card">
                  <div class="c-cases-destaque__grid">

                    <div class="c-cases-destaque__left">
                      <div class="c-cases-destaque__company">
                        <?php if ($logo_id): ?>
                          <div class="c-cases-destaque__logo">
                            <?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'c-cases-destaque__logo-img']); ?>
                          </div>
                        <?php endif; ?>

                        <?php if ($nome_empresa || $cidade_estado): ?>
                          <div class="c-cases-destaque__company-info">
                            <?php if ($nome_empresa): ?>
                              <p class="c-cases-destaque__company-name">
                                <?php echo esc_html($nome_empresa); ?>
                              </p>
                            <?php endif; ?>
                            <?php if ($cidade_estado): ?>
                              <p class="text__normal c-cases-destaque__company-location">
                                <?php echo esc_html($cidade_estado); ?>
                              </p>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="c-cases-destaque__right">
                      <?php if ($depoimento): ?>
                        <blockquote class="c-cases-destaque__quote text__normal">
                          <?php echo wpautop(wp_kses_post($depoimento)); ?>
                        </blockquote>
                      <?php endif; ?>

                      <?php if ($autor_id || $nome_autor || $cargo_autor): ?>
                        <div class="c-cases-destaque__author">
                          <?php if ($autor_id): ?>
                            <div class="c-cases-destaque__author-photo">
                              <?php echo wp_get_attachment_image($autor_id, 'thumbnail', false, ['class' => 'c-cases-destaque__author-img']); ?>
                            </div>
                          <?php endif; ?>

                          <div class="c-cases-destaque__author-info">
                            <?php if ($nome_autor): ?>
                              <p class="c-cases-destaque__author-name">
                                <?php echo esc_html($nome_autor); ?>
                              </p>
                            <?php endif; ?>
                            <?php if ($cargo_autor): ?>
                              <p class="text__normal c-cases-destaque__author-role">
                                <?php echo esc_html($cargo_autor); ?>
                              </p>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endif; ?>

                      <?php if ($btn_url): ?>
                        <div class="c-cases-destaque__cta">
                          <a class="button button__blue c-cases-destaque__button" href="<?php echo esc_url($btn_url); ?>"
                            <?php echo $btn_target ? ' target="' . esc_attr($btn_target) . '"' : ''; ?>>
                            <?php echo esc_html($btn_title ?: __('Saiba mais', 'textdomain')); ?>
                          </a>
                        </div>
                      <?php endif; ?>
                    </div>

                  </div>
                </article>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>