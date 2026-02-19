<?php //Template name: Inteligencia
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="inteligencia-ia">
  <?php $tpl_engine->partial('template/global/hero') ?>

  <?php $tpl_engine->partial('template/pages/home/testimonials') ?>
  <?php $tpl_engine->partial('template/pages/home/logos') ?>
  <?php $tpl_engine->partial('template/pages/home/case-studies') ?>
  <?php $tpl_engine->partial('template/pages/home/integrations') ?>
  <?php $tpl_engine->partial('template/global/faq') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta') ?>
</div>
<?php get_footer(); ?>