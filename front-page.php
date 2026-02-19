<?php //Template name: Home Page 
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="home">
  <?php $tpl_engine->partial('template/pages/home/hero') ?>
  <div class="full-vertical-line"></div>
  <?php $tpl_engine->partial('template/pages/home/logos') ?>
  <?php $tpl_engine->partial('template/pages/home/unifique-atendimento') ?>
  <?php $tpl_engine->partial('template/pages/home/unifique-atendimento', ['data' => get_field('section_rafa_ia')]) ?>
  <?php $tpl_engine->partial('template/pages/home/tudo-precisa') ?>
  <?php $tpl_engine->partial('template/pages/home/testimonials') ?>
  <?php $tpl_engine->partial('template/pages/home/cta-proximo-caso-sucesso') ?>
  <?php $tpl_engine->partial('template/pages/home/case-studies') ?>
  <?php $tpl_engine->partial('template/pages/home/integrations') ?>
  <?php $tpl_engine->partial('template/pages/home/section-about') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta') ?>
</div>
<?php get_footer(); ?>