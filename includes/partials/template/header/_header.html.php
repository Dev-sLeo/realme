<?php global $tpl_engine; ?>
<?php

$main_menu = wp_nav_menu(array(
  'theme_location' => 'header',
  'depth' => 0,
  'container' => '',
  'menu_class' => 'c-main-menu__list',
  'echo' => false,
));

?>
<header class="o-header">
  <div class="s-container">
    <div class="o-header__content">
      <div class="logo">
        <?php if (has_custom_logo()) : ?>
          <?php if (pathinfo(wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0], PATHINFO_EXTENSION) === 'svg') : ?>
            <a href="<?= site_url() ?>" aria-label="Logo Principal">
              <?= processarArquivo(wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0]) ?>
            </a>
          <?php else : ?>
            <?php the_custom_logo() ?>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="menu">
        <?= $main_menu ?>
        <a href="#contato" class="button button-border__blue">Contato</a>
      </div>
      <div class="header__mobile">
        <div class="hamburguer-menu ">
          <div class="open">
            <?php $tpl_engine->svg('hamburguer-menu') ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>