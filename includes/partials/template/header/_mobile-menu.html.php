<?php global $tpl_engine; ?>
<?php

$main_menu = wp_nav_menu(array(
  'theme_location' => 'mobile',
  'depth'          => 3,
  'container'      => '',
  'menu_class'     => 'c-main-menu__list',
  'walker'         => new Main_Menu_Mobile_Walker(),
  'echo' => false,
));

$footer = get_field('footer', 'tema');

$contato    = $footer['contato'] ?? [];
$cta_desc   = $contato['description'] ?? '';
$cta_btn    = $contato['button'] ?? null;     // link array
$social     = $contato['social_media'] ?? [];

$social_icon_map = [
  'facebook'  => 'redes-sociais/facebook',
  'instagram' => 'redes-sociais/instagram',
  'linkedin'  => 'redes-sociais/linkedin',
  'youtube'  => 'redes-sociais/youtube',
];

?>
<div class="menu-mobile">
  <div class="close-icon">
    <?php $tpl_engine->svg('close-icon') ?>
  </div>
  <div class="menu-container">
    <?= $main_menu ?>
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
  </div>
</div>