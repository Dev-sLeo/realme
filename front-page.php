<?php //Template name: Home Page 
?>
<?php get_header(); ?>
<?php
global $tpl_engine;

?>
<div class="home">
  <?php $tpl_engine->partial('template/pages/home/hero-header') ?>
  <?php $tpl_engine->partial('template/pages/home/especialidades') ?>
  <?php $tpl_engine->partial('template/pages/home/essencia') ?>
  <?php $tpl_engine->partial('template/pages/home/testimonials') ?>
  <?php $tpl_engine->partial('template/pages/home/convenios') ?>
  <?php $tpl_engine->partial('template/pages/home/about') ?>
  <?php $tpl_engine->partial('template/pages/home/blog') ?>
</div>
<?php get_footer(); ?>