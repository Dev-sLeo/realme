<?php //Template name: Contato
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="contato s-border-sides">
  <?php $tpl_engine->partial('template/pages/contact/contact') ?>
  <?php $tpl_engine->partial('template/pages/home/logos') ?>
  <?php $tpl_engine->partial('template/global/testimonials') ?>
</div>
<?php get_footer(); ?>