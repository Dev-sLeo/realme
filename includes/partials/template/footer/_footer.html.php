<?php global $tpl_engine; ?>
<?php
// ACF (Options Page) — Location: 'tema'
$footer = get_field('footer', 'tema') ?: [];

// BGs (performance: <picture> carrega só o necessário pelo media query)
$bg_desktop = $footer['background'] ?? null;
$bg_mobile  = $footer['background_mobile'] ?? null;

// Conteúdo
$cards       = $footer['cards'] ?? [];
$logo_footer = $footer['logo_footer'] ?? null;
$sociais     = $footer['redes_sociais'] ?? [];
$copy        = $footer['copy'] ?? '';
$link_upsites = $footer['link_upsites'] ?? '';

// Menus
$footer_menu_1 = wp_nav_menu([
  'theme_location' => 'footer',
  'depth'          => 1,
  'container'      => '',
  'menu_class'     => 'c-footer-menu__list',
  'echo'           => false,
]);

$footer_menu_2 = wp_nav_menu([
  'theme_location' => 'footer2',
  'depth'          => 1,
  'container'      => '',
  'menu_class'     => 'c-footer-menu__list',
  'echo'           => false,
]);

$footer_menu_3 = wp_nav_menu([
  'theme_location' => 'footer3',
  'depth'          => 1,
  'container'      => '',
  'menu_class'     => 'c-footer-menu__list',
  'echo'           => false,
]);

$footer_menu_4 = wp_nav_menu([
  'theme_location' => 'footer4',
  'depth'          => 1,
  'container'      => '',
  'menu_class'     => 'c-footer-menu__list',
  'echo'           => false,
]);

// Helpers
$year = date('Y');

$render_logo = function () use ($logo_footer) {
  // 1) Logo do ACF
  if (!empty($logo_footer) && !empty($logo_footer['ID'])) {
    return wp_get_attachment_image($logo_footer['ID'], 'full', false, ['loading' => 'lazy']);
  }

  // 2) Logo padrão do WP
  if (has_custom_logo()) {
    ob_start();
    the_custom_logo();
    return ob_get_clean();
  }

  // 3) Fallback texto
  return '<span class="o-footer__logo-text">ConVida</span>';
};

$render_social_icon = function ($item) use ($tpl_engine) {
  // Esperado no repeater: icone (ex: instagram/facebook/whatsapp) + link (url)
  $icon_key = $item['icone'] ?? $item['icon'] ?? '';
  $img      = $item['imagem'] ?? $item['image'] ?? null;

  if (!empty($img) && !empty($img['ID'])) {
    return wp_get_attachment_image($img['ID'], 'full', false, ['loading' => 'lazy']);
  }

  if ($icon_key) {
    // usa seus svgs em /redes-sociais/
    ob_start();
    $tpl_engine->svg('redes-sociais/' . sanitize_title($icon_key));
    return ob_get_clean();
  }

  // fallback simples
  return '';
};
?>

<footer class="o-footer" role="contentinfo">

  <?php // BG responsivo otimizado (1280+ desktop, abaixo mobile/tablet) 
  ?>
  <?php if (!empty($bg_desktop) || !empty($bg_mobile)) : ?>
    <div class="o-footer__bg" aria-hidden="true">
      <picture>
        <?php if (!empty($bg_mobile) && !empty($bg_mobile['ID'])) : ?>
          <source media="(max-width: 1279px)" srcset="<?= esc_attr(wp_get_attachment_image_srcset($bg_mobile['ID'], 'full')); ?>">
        <?php endif; ?>

        <?php if (!empty($bg_desktop) && !empty($bg_desktop['ID'])) : ?>
          <source media="(min-width: 1280px)" srcset="<?= esc_attr(wp_get_attachment_image_srcset($bg_desktop['ID'], 'full')); ?>">
        <?php endif; ?>

        <?php
        $fallback = !empty($bg_desktop) ? $bg_desktop : $bg_mobile;
        if (!empty($fallback) && !empty($fallback['ID'])) :
          $fallback_src = wp_get_attachment_image_url($fallback['ID'], 'full');
        ?>
          <img
            src="<?= esc_url($fallback_src); ?>"
            alt=""
            loading="lazy"
            decoding="async">
        <?php endif; ?>
      </picture>
    </div>
  <?php endif; ?>

  <div class="s-container">

    <div class="o-footer__top">
      <div class="o-footer__grid">

        <!-- COL 1: Brand -->
        <div class="o-footer__col o-footer__brand">
          <div class="o-footer__logo">
            <a class="o-footer__logo-link" href="<?= esc_url(home_url('/')) ?>" aria-label="Ir para Home">
              <?= $render_logo(); ?>
            </a>
          </div>

          <div class="o-footer__social">
            <?php if (!empty($sociais)) : ?>
              <?php foreach ($sociais as $social) :
                $url = $social['link'] ?? $social['url'] ?? '#';
                $label = $social['label'] ?? $social['icone'] ?? 'Rede social';
              ?>
                <a class="o-footer__social-link"
                  href="<?= esc_url($url ?: '#'); ?>"
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="<?= esc_attr($label); ?>">
                  <?= $render_social_icon($social); ?>
                </a>
              <?php endforeach; ?>
            <?php else : ?>
              <!-- fallback se ainda não preencher no ACF -->
              <a class="o-footer__social-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <?php $tpl_engine->svg('redes-sociais/instagram') ?>
              </a>
              <a class="o-footer__social-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                <?php $tpl_engine->svg('redes-sociais/facebook') ?>
              </a>
              <a class="o-footer__social-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                <?php $tpl_engine->svg('redes-sociais/whatsapp') ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- COL 2: Cards Endereços -->
        <div class="o-footer__col o-footer__cards">
          <div class="o-footer-cards">

            <?php if (!empty($cards)) : ?>
              <?php foreach ($cards as $card) :
                // Ajuste os keys abaixo exatamente conforme você criou no repeater (mantive tolerante)
                $c_title     = $card['titulo'] ?? $card['title'] ?? 'Título do endereço';
                $c_endereco  = $card['endereco'] ?? $card['address'] ?? '';
                $c_tel       = $card['telefone'] ?? $card['tel'] ?? '';
                $c_whats     = $card['whatsapp'] ?? $card['whats'] ?? '';
                $c_horario   = $card['horario'] ?? $card['hours'] ?? '';
              ?>
                <?php
                // Dentro do foreach ($cards as $card)

                $c_icon         = $card['icone'] ?? null;
                $c_title        = $card['titulo'] ?? '';
                $c_endereco     = $card['endereco'] ?? '';
                $c_link_endereco = $card['link_endereco'] ?? '';

                $c_whats        = $card['whatsapp'] ?? '';
                $c_tel          = $card['telefone'] ?? '';
                $c_horario      = $card['horario'] ?? '';

                // helpers (telefone/whatsapp)
                $only_digits = function ($v) {
                  return preg_replace('/\D+/', '', (string) $v);
                };

                $make_tel_href = function ($raw) use ($only_digits) {
                  $digits = $only_digits($raw);
                  if (!$digits) return '';
                  // Se vier sem DDI e tiver 10/11 dígitos, assume BR (+55)
                  if (strlen($digits) === 10 || strlen($digits) === 11) $digits = '55' . $digits;
                  return 'tel:+' . $digits;
                };

                $make_wa_href = function ($raw) use ($only_digits) {
                  $digits = $only_digits($raw);
                  if (!$digits) return '';
                  // Se vier sem DDI e tiver 10/11 dígitos, assume BR (+55)
                  if (strlen($digits) === 10 || strlen($digits) === 11) $digits = '55' . $digits;
                  return 'https://wa.me/' . $digits;
                };

                $tel_href   = $make_tel_href($c_tel);
                $wa_href    = $make_wa_href($c_whats);
                $addr_href  = $c_link_endereco ? esc_url($c_link_endereco) : '';
                ?>

                <article class="c-footer-card">
                  <header class="c-footer-card__header">

                    <?php if (!empty($c_icon) && !empty($c_icon['ID'])) : ?>
                      <span class="c-footer-card__icon" aria-hidden="true">
                        <?= wp_get_attachment_image($c_icon['ID'], 'thumbnail', false, ['loading' => 'lazy']) ?>
                      </span>
                    <?php else : ?>
                      <span class="c-footer-card__icon" aria-hidden="true">
                        <!-- seu SVG fallback (mantido) -->
                        <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="27" cy="27" r="27" fill="#4B7A61" />
                          <path d="M30.2142 23.143C30.2142 24.9182 28.7751 26.3573 26.9999 26.3573C25.2247 26.3573 23.7856 24.9182 23.7856 23.143C23.7856 21.3678 25.2247 19.9287 26.9999 19.9287C28.7751 19.9287 30.2142 21.3678 30.2142 23.143Z" stroke="white" stroke-width="1.92857" />
                          <path d="M28.6167 34.0629C28.183 34.4805 27.6034 34.714 27.0003 34.714C26.397 34.714 25.8174 34.4805 25.3837 34.0629C21.4127 30.215 16.091 25.9165 18.6862 19.6759C20.0894 16.3017 23.4578 14.1426 27.0003 14.1426C30.5427 14.1426 33.911 16.3017 35.3142 19.6759C37.9062 25.9087 32.5975 30.2283 28.6167 34.0629Z" stroke="white" stroke-width="1.92857" />
                          <path d="M34.7142 37.2852C34.7142 38.7054 31.2604 39.8566 26.9999 39.8566C22.7394 39.8566 19.2856 38.7054 19.2856 37.2852" stroke="white" stroke-width="1.92857" stroke-linecap="round" />
                        </svg>
                      </span>
                    <?php endif; ?>

                    <?php if ($c_title) : ?>
                      <h3 class="c-footer-card__title"><?= esc_html($c_title); ?></h3>
                    <?php endif; ?>
                  </header>

                  <div class="c-footer-card__body">

                    <?php if ($c_endereco) : ?>
                      <p class="c-footer-card__text">
                        <?php if ($addr_href) : ?>
                          <a href="<?= $addr_href; ?>" target="_blank" rel="noopener noreferrer">
                            <?= esc_html($c_endereco); ?>
                          </a>
                        <?php else : ?>
                          <?= esc_html($c_endereco); ?>
                        <?php endif; ?>
                      </p>
                    <?php endif; ?>

                    <?php if ($c_tel) : ?>
                      <p class="c-footer-card__text">
                        <strong>Telefone:</strong>
                        <?php if ($tel_href) : ?>
                          <a href="<?= esc_url($tel_href); ?>"><?= esc_html($c_tel); ?></a>
                        <?php else : ?>
                          <?= esc_html($c_tel); ?>
                        <?php endif; ?>
                      </p>
                    <?php endif; ?>

                    <?php if ($c_whats) : ?>
                      <p class="c-footer-card__text">
                        <strong>WhatsApp:</strong>
                        <?php if ($wa_href) : ?>
                          <a href="<?= esc_url($wa_href); ?>" target="_blank" rel="noopener noreferrer"><?= esc_html($c_whats); ?></a>
                        <?php else : ?>
                          <?= esc_html($c_whats); ?>
                        <?php endif; ?>
                      </p>
                    <?php endif; ?>

                    <?php if ($c_horario) : ?>
                      <p class="c-footer-card__text">
                        <strong>Horário:</strong> <?= esc_html($c_horario); ?>
                      </p>
                    <?php endif; ?>

                  </div>
                </article>

              <?php endforeach; ?>
            <?php else : ?>
              <!-- fallback (se ainda não preencher no ACF) -->
              <article class="c-footer-card">
                <header class="c-footer-card__header">
                  <span class="c-footer-card__icon" aria-hidden="true">
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="27" cy="27" r="27" fill="#4B7A61" />
                      <path d="M30.2142 23.143C30.2142 24.9182 28.7751 26.3573 26.9999 26.3573C25.2247 26.3573 23.7856 24.9182 23.7856 23.143C23.7856 21.3678 25.2247 19.9287 26.9999 19.9287C28.7751 19.9287 30.2142 21.3678 30.2142 23.143Z" stroke="white" stroke-width="1.92857" />
                      <path d="M28.6167 34.0629C28.183 34.4805 27.6034 34.714 27.0003 34.714C26.397 34.714 25.8174 34.4805 25.3837 34.0629C21.4127 30.215 16.091 25.9165 18.6862 19.6759C20.0894 16.3017 23.4578 14.1426 27.0003 14.1426C30.5427 14.1426 33.911 16.3017 35.3142 19.6759C37.9062 25.9087 32.5975 30.2283 28.6167 34.0629Z" stroke="white" stroke-width="1.92857" />
                      <path d="M34.7142 37.2852C34.7142 38.7054 31.2604 39.8566 26.9999 39.8566C22.7394 39.8566 19.2856 38.7054 19.2856 37.2852" stroke="white" stroke-width="1.92857" stroke-linecap="round" />
                    </svg>
                  </span>
                  <h3 class="c-footer-card__title">CONVENCIONAL / FUNDOS 2</h3>
                </header>
                <div class="c-footer-card__body">
                  <p class="c-footer-card__text">Rua Vinte e Seis, 1043 - Vila Yara, Osasco - SP</p>
                  <p class="c-footer-card__text"><strong>Telefone:</strong> (11) 0000-0000<br><strong>WhatsApp:</strong> (11) 00000-0000</p>
                  <p class="c-footer-card__text"><strong>Horário:</strong> Seg à Sex - 08:00 às 18:00</p>
                </div>
              </article>
            <?php endif; ?>

          </div>
        </div>

        <!-- COL 3: Menu 1 -->
        <div class="o-footer__col o-footer__nav">
          <div class="o-footer__nav-brands">
            <div class="o-footer__logo">
              <a class="o-footer__logo-link" href="<?= esc_url(home_url('/')) ?>" aria-label="Ir para Home">
                <?= $render_logo(); ?>
              </a>
            </div>

            <div class="o-footer__social">
              <?php if (!empty($sociais)) : ?>
                <?php foreach ($sociais as $social) :
                  $url = $social['link'] ?? $social['url'] ?? '#';
                  $label = $social['rede_social'] ?? $social['icone'] ?? 'Rede social';
                ?>
                  <a class="o-footer__social-link"
                    href="<?= esc_url($url ?: '#'); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?= esc_attr($label); ?>">
                    <?php $tpl_engine->svg('redes-sociais/' . $label); ?>
                  </a>
                <?php endforeach; ?>
              <?php else : ?>
                <a class="o-footer__social-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                  <?php $tpl_engine->svg('redes-sociais/instagram') ?>
                </a>
                <a class="o-footer__social-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                  <?php $tpl_engine->svg('redes-sociais/facebook') ?>
                </a>
                <a class="o-footer__social-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                  <?php $tpl_engine->svg('redes-sociais/whatsapp') ?>
                </a>
              <?php endif; ?>
            </div>
          </div>

          <nav class="c-footer-menu" aria-label="Menu do footer 1">
            <?= $footer_menu_1 ?: '<ul class="c-footer-menu__list"><li class="c-footer-menu__item"><a class="c-footer-menu__link" href="#">Adicionar menu: footer</a></li></ul>'; ?>
          </nav>

          <nav class="c-footer-menu" aria-label="Menu do footer 2">
            <?= $footer_menu_2 ?: '<ul class="c-footer-menu__list"><li class="c-footer-menu__item"><a class="c-footer-menu__link" href="#">Adicionar menu: footer2</a></li></ul>'; ?>
          </nav>

          <nav class="c-footer-menu" aria-label="Menu do footer 3">
            <?= $footer_menu_3 ?: '<ul class="c-footer-menu__list"><li class="c-footer-menu__item"><a class="c-footer-menu__link" href="#">Adicionar menu: footer3</a></li></ul>'; ?>
          </nav>

          <nav class="c-footer-menu" aria-label="Menu do footer 3">
            <?= $footer_menu_4 ?: '<ul class="c-footer-menu__list"><li class="c-footer-menu__item"><a class="c-footer-menu__link" href="#">Adicionar menu: footer4</a></li></ul>'; ?>
          </nav>
        </div>

      </div>
    </div>

    <div class="o-footer__bottom">
      <p class="o-footer__copyright">
        <?php
        // Se você preencher "copy" no ACF, ele substitui o texto inteiro
        if ($copy) {
          $copy_render = str_replace(['{year}', '{{year}}'], $year, $copy);
          echo wp_kses_post($copy_render);
        } else {
          $up_href = $link_upsites ?: '#';
          echo '©' . esc_html($year) . ' ConVida — Desenvolvido por <a href="' . esc_url($up_href) . '" target="_blank" rel="noopener noreferrer">Upsites</a>';
        }
        ?>
      </p>
    </div>

  </div>
</footer>