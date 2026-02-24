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
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/highlights-cases.webp" alt="">
  </div>
  <div class="s-container">
    <?php if ($titulo): ?>
      <header class="c-cases-destaque__header">
        <h2 class="title__normal c-cases-destaque__title"><?php echo esc_html($titulo); ?></h2>
      </header>
    <?php endif; ?>

    <?php if (!empty($slider) && is_array($slider)): ?>
      <div class="c-cases-destaque__slider-wrap">
        <div class="swiper c-cases-destaque__slider js-cases-destaque-swiper" data-swiper="cases-destaque">
          <div class="swiper-wrapper">
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

              $logo_id = $logo ? (is_array($logo) ? ($logo['ID'] ?? null) : $logo) : null;
              $autor_id = $foto_autor ? (is_array($foto_autor) ? ($foto_autor['ID'] ?? null) : $foto_autor) : null;

              $btn_url = is_array($botao) ? ($botao['url'] ?? '') : '';
              $btn_title = is_array($botao) ? ($botao['title'] ?? '') : '';
              $btn_target = is_array($botao) ? ($botao['target'] ?? '') : '';
            ?>
              <div class="swiper-slide">
                <article class="c-cases-destaque__card">
                  <div class="c-cases-destaque__grid">
                    <div class="c-cases-destaque__left">
                      <div class="c-cases-destaque__company">
                        <?php if ($logo_id): ?><div class="c-cases-destaque__logo"><?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'c-cases-destaque__logo-img']); ?></div><?php endif; ?>
                        <?php if ($nome_empresa || $cidade_estado): ?>
                          <div class="c-cases-destaque__company-info">
                            <?php if ($nome_empresa): ?><p class="c-cases-destaque__company-name"><?php echo esc_html($nome_empresa); ?></p><?php endif; ?>
                            <?php if ($cidade_estado): ?><p class="c-cases-destaque__company-location"><?php echo esc_html($cidade_estado); ?></p><?php endif; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="c-cases-destaque__right">

                      <?php if ($depoimento): ?><blockquote class="c-cases-destaque__quote">
                          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="21" viewBox="0 0 28 21" fill="none">
                            <path d="M0 21V15.4737C0 13.7953 0.296784 12.0146 0.890351 10.1316C1.50439 8.22807 2.3845 6.3962 3.5307 4.63597C4.69737 2.85526 6.09941 1.30994 7.73684 0L11.6667 3.19298C10.3772 5.03509 9.25146 6.95906 8.28947 8.96491C7.34795 10.9503 6.87719 13.0789 6.87719 15.3509V21H0ZM15.7193 21V15.4737C15.7193 13.7953 16.0161 12.0146 16.6096 10.1316C17.2237 8.22807 18.1038 6.3962 19.25 4.63597C20.4167 2.85526 21.8187 1.30994 23.4561 0L27.386 3.19298C26.0965 5.03509 24.9708 6.95906 24.0088 8.96491C23.0672 10.9503 22.5965 13.0789 22.5965 15.3509V21H15.7193Z" fill="#A3D2E0" />
                          </svg><?php echo wpautop(wp_kses_post($depoimento)); ?>
                        </blockquote><?php endif; ?>

                      <?php if ($autor_id || $nome_autor || $cargo_autor): ?>
                        <div class="c-cases-destaque__author">
                          <?php if ($autor_id): ?><div class="c-cases-destaque__author-photo"><?php echo wp_get_attachment_image($autor_id, 'thumbnail', false, ['class' => 'c-cases-destaque__author-img']); ?></div><?php endif; ?>
                          <div class="c-cases-destaque__author-info">
                            <?php if ($nome_autor): ?><p class="c-cases-destaque__author-name"><?php echo esc_html($nome_autor); ?></p><?php endif; ?>
                            <?php if ($cargo_autor): ?><p class="c-cases-destaque__author-role"><?php echo esc_html($cargo_autor); ?></p><?php endif; ?>
                          </div>
                        </div>
                      <?php endif; ?>


                    </div>
                    <?php if ($btn_url): ?>
                      <div class="c-cases-destaque__cta">
                        <a class="button button__blue c-cases-destaque__button" href="<?php echo esc_url($btn_url); ?>" <?php echo $btn_target ? ' target="' . esc_attr($btn_target) . '"' : ''; ?>>
                          <?php echo esc_html($btn_title ?: __('Saiba mais', 'textdomain')); ?>
                          <svg width="48" height="33" viewBox="0 0 48 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_d_371_20928)">
                              <rect x="1.91406" y="0.956543" width="43.2625" height="29.131" rx="14.5655" fill="#4353FA" shape-rendering="crispEdges" />
                              <path d="M22.002 12.4353L25.0886 15.522L22.002 18.6086" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                              <filter id="filter0_d_371_20928" x="0.000927567" y="-2.44975e-05" width="47.088" height="32.9573" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                <feOffset dy="0.956567" />
                                <feGaussianBlur stdDeviation="0.956567" />
                                <feComposite in2="hardAlpha" operator="out" />
                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.05 0" />
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_371_20928" />
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_371_20928" result="shape" />
                              </filter>
                            </defs>
                          </svg>

                        </a>
                      </div>
                    <?php endif; ?>
                  </div>
                </article>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="arrows-control">
          <button class="c-cases-destaque__arrow c-cases-destaque__arrow--prev" type="button" aria-label="Anterior"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="43" viewBox="0 0 25 43" fill="none">
              <path d="M22.0234 40.4309L2.01975 21.2263L22.0234 2.02161" stroke="#4353FA" stroke-width="4.04309" stroke-linecap="round" stroke-linejoin="round" />
            </svg></button>
          <button class="c-cases-destaque__arrow c-cases-destaque__arrow--next" type="button" aria-label="Próximo"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="43" viewBox="0 0 25 43" fill="none">
              <path d="M2.02149 40.4309L22.0252 21.2263L2.02148 2.02161" stroke="#4353FA" stroke-width="4.04309" stroke-linecap="round" stroke-linejoin="round" />
            </svg></button>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>