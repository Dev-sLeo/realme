<?php //Template name: Contato
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="contato s-border-sides">
  <?php $tpl_engine->partial('template/pages/contact/contact') ?>
  <div class="o-testimonials-logo">
    <?php $tpl_engine->partial('template/global/testimonials') ?>
    <?php $tpl_engine->partial('template/pages/home/logos') ?>
  </div>
</div>
<?php get_footer(); ?>