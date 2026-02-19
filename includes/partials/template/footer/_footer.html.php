<?php
global $tpl_engine;

$footer = get_field('footer', 'tema');
if (empty($footer) || !is_array($footer)) return;

// ACF blocks
$info       = $footer['information'] ?? [];
$logo       = $info['logo'] ?? null;          // image array
$desc       = $info['description'] ?? '';
$infos      = $info['infos'] ?? [];

$title_1    = $footer['menu_1'] ?? 'Produtos';
$title_2    = $footer['menu_2'] ?? 'Soluções';

$contato    = $footer['contato'] ?? [];
$cta_desc   = $contato['description'] ?? '';
$cta_btn    = $contato['button'] ?? null;     // link array
$social     = $contato['social_media'] ?? [];

$copy_block = $footer['copy'] ?? [];
$copy_text  = $copy_block['copy'] ?? '';
$up_link    = $copy_block['link_upsites'] ?? '';

// Helpers
$sanitize_phone = function (string $v) {
  return preg_replace('/(?!^\+)[^\d]/', '', trim($v));
};

$build_href = function (string $rede, string $value) use ($sanitize_phone) {
  $value = trim($value);

  if ($rede === 'telefone') {
    if (str_starts_with($value, 'tel:') || str_starts_with($value, 'http')) return $value;
    return 'tel:' . $sanitize_phone($value);
  }

  if ($rede === 'email') {
    if (str_starts_with($value, 'mailto:') || str_starts_with($value, 'http')) return $value;
    return 'mailto:' . $value;
  }

  if ($rede === 'whatsapp') {
    if (str_starts_with($value, 'http')) return $value;

    $num = $sanitize_phone($value);
    $num = ltrim($num, '+');
    return $num ? 'https://wa.me/' . $num : $value;
  }

  return $value;
};

$info_icon_map = [
  'whatsapp' => 'information/whatsapp',
  'telefone' => 'information/telefone',
  'email'    => 'information/email',
];

$social_icon_map = [
  'facebook'  => 'redes-sociais/facebook',
  'instagram' => 'redes-sociais/instagram',
  'linkedin'  => 'redes-sociais/linkedin',
  'youtube'  => 'redes-sociais/youtube',
];

// Menus: 1) theme_location 2) por nome do menu igual ao título
$render_footer_menu = function (string $theme_location, string $fallback_name) {
  if ($theme_location && has_nav_menu($theme_location)) {
    wp_nav_menu([
      'theme_location' => $theme_location,
      'container'      => false,
      'menu_class'     => 'c-footer__menu',
      'fallback_cb'    => false,
      'depth'          => 1,
    ]);
    return;
  }

  if ($fallback_name) {
    $term = get_term_by('name', $fallback_name, 'nav_menu');
    if ($term && !is_wp_error($term)) {
      wp_nav_menu([
        'menu'          => (int) $term->term_id,
        'container'     => false,
        'menu_class'    => 'c-footer__menu',
        'fallback_cb'   => false,
        'depth'         => 1,
      ]);
      return;
    }
  }

  echo '<ul class="c-footer__menu"></ul>';
};
?>

<footer class="c-footer" aria-label="Rodapé">
  <div class="s-container">

    <div class="c-footer__grid">

      <!-- COL 1: Info -->
      <div class="c-footer__col c-footer__col--info">
        <?php if (!empty($logo['ID'])) : ?>
          <div class="c-footer__logo">
            <?= wp_get_attachment_image((int) $logo['ID'], 'full', false, ['loading' => 'lazy']); ?>
          </div>
        <?php endif; ?>

        <?php if ($desc) : ?>
          <div class="c-footer__desc text__small">
            <?= $desc ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($infos) && is_array($infos)) : ?>
          <ul class="c-footer__infos" aria-label="Informações de contato">
            <?php foreach ($infos as $row) :
              $rede = $row['rede'] ?? '';
              $val  = $row['link'] ?? '';
              if (!$rede || !$val) continue;

              $href = $build_href($rede, $val);
              $svg  = $info_icon_map[$rede] ?? 'icon/social/link';
            ?>
              <li class="c-footer__infos-item">
                <span class="c-footer__infos-icon" aria-hidden="true">
                  <?php $tpl_engine->svg($svg); ?>
                </span>

                <a class="c-footer__infos-link text__small" href="<?= esc_url($href); ?>" target="_blank" rel="noopener">
                  <?= esc_html($val); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- COL 2: Menu 1 -->
      <nav class="c-footer__col c-footer__col--menu" aria-label="<?= esc_attr($title_1); ?>">
        <h3 class="c-footer__title text__small">
          <strong><?= esc_html($title_1); ?></strong>
        </h3>
        <?php $render_footer_menu('footer_menu_1', $title_1); ?>
      </nav>

      <!-- COL 3: Menu 2 -->
      <nav class="c-footer__col c-footer__col--menu" aria-label="<?= esc_attr($title_2); ?>">
        <h3 class="c-footer__title text__small"><strong><?= esc_html($title_2); ?></strong></h3>
        <?php $render_footer_menu('footer_menu_2', $title_2); ?>
      </nav>

      <!-- COL 4: CTA -->
      <div class="c-footer__col c-footer__col--cta" aria-label="Entre em contato">
        <h3 class="c-footer__title text__small"><strong>Entre em contato</strong></h3>

        <?php if ($cta_desc) : ?>
          <div class="c-footer__cta-desc text__small">
            <?= $cta_desc ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($cta_btn) && is_array($cta_btn) && !empty($cta_btn['url']) && !empty($cta_btn['title'])) : ?>
          <div class="c-footer__cta-action">
            <a class="c-footer__button button button__blue"
              href="<?= esc_url($cta_btn['url']); ?>"
              target="<?= esc_attr($cta_btn['target'] ?: '_self'); ?>"
              rel="<?= ($cta_btn['target'] === '_blank') ? 'noopener' : ''; ?>">
              <?= esc_html($cta_btn['title']); ?>
            </a>
          </div>
        <?php endif; ?>

        <?php if (!empty($social) && is_array($social)) : ?>
          <ul class="c-footer__social" aria-label="Redes sociais">
            <?php foreach ($social as $row) :
              $rede = $row['rede'] ?? '';
              $link = $row['link'] ?? '';
              if (!$rede) continue;

              $svg = $social_icon_map[$rede] ?? 'icon/social/link';
            ?>
              <li class="c-footer__social-item">
                <a class="c-footer__social-link"
                  href="<?= esc_url($link); ?>"
                  target="_blank"
                  rel="noopener"
                  aria-label="<?= esc_attr(ucfirst($rede)); ?>">
                  <span class="c-footer__social-icon" aria-hidden="true">
                    <?php $tpl_engine->svg($svg); ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

    </div><!-- /.c-footer__grid -->

    <div class="c-footer__bottom" aria-label="Créditos">
      <div class="c-footer__bottom-left">
        <?php if ($copy_text) : ?>
          <p class="c-footer__copy text__small"><?= esc_html($copy_text); ?></p>
        <?php endif; ?>
      </div>

      <div class="c-footer__bottom-right">
        <?php if ($up_link) : ?>
          <a class="c-footer__upsites text__small" href="<?= esc_url($up_link); ?>" target="_blank" rel="noopener">
            Criação de Site por Upsites
          </a>
        <?php endif; ?>
      </div>
    </div>

  </div><!-- /.s-container -->
</footer>