<?php //Template name: Inteligencia
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="inteligencia-ia s-border-sides">
  <?php $tpl_engine->partial('template/global/hero') ?>
  <?php $tpl_engine->partial('template/pages/ia/como-funciona') ?>
  <?php $tpl_engine->partial('template/pages/ia/pq-nos') ?>
  <?php $tpl_engine->partial('template/pages/plataforma/recursos') ?>
  <?php $tpl_engine->partial('template/pages/plataforma/resultado') ?>
  <div class="o-testimonials-logo">
    <?php $tpl_engine->partial('template/global/testimonials') ?>
    <?php $tpl_engine->partial('template/pages/home/logos') ?>
  </div>
  <?php $tpl_engine->partial('template/pages/home/case-studies') ?>
  <?php $tpl_engine->partial('template/pages/plataforma/resultado', ['data' => get_field('calculadora_roi')]) ?>
  <?php $tpl_engine->partial('template/pages/home/integrations') ?>
  <?php $tpl_engine->partial('template/global/faq') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta') ?>
</div>
<?php get_footer(); ?>