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
<div class="menu-mobile">
  <div class="close-icon">
    <?php $tpl_engine->svg('close-icon') ?>
  </div>
  <div class="menu-container">
    <?= $main_menu ?>
    <div class="button-container">
      <a href="#contato" class="button button-border__white">Contato</a>
    </div>
  </div>
</div>