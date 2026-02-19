<?php //Template name: Plataforma
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="plataforma">
  <?php $tpl_engine->partial('template/global/hero') ?>
  <?php $tpl_engine->partial('template/pages/plataforma/recursos') ?>
  <?php $tpl_engine->partial('template/pages/plataforma/resultado') ?>
  <?php $tpl_engine->partial('template/global/testimonials') ?>
  <?php $tpl_engine->partial('template/pages/home/logos') ?>
  <?php $tpl_engine->partial('template/pages/home/case-studies') ?>
  <?php $tpl_engine->partial('template/pages/home/integrations') ?>
  <?php $tpl_engine->partial('template/global/faq') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta') ?>
</div>
<?php get_footer(); ?>