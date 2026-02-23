<?php get_header(); ?>
<?php global $tpl_engine; ?>
<div class="single-produto">
  <?php $tpl_engine->partial('template/pages/single/product/hero') ?>
  <?php $tpl_engine->partial('template/pages/single/product/pain-points') ?>
  <?php $tpl_engine->partial('template/pages/single/product/features-grid') ?>
  <?php $tpl_engine->partial('template/pages/single/product/how-it-works') ?>
  <?php $tpl_engine->partial('template/pages/single/product/technology') ?>
  <?php $tpl_engine->partial('template/pages/single/product/security') ?>
  <?php $tpl_engine->partial('template/pages/single/product/comparison') ?>
  <?php $tpl_engine->partial('template/pages/single/product/faq') ?>
  <?php $tpl_engine->partial('template/pages/single/product/benefits') ?>
  <?php $tpl_engine->partial('template/pages/single/product/final-cta') ?>
</div>
<?php get_footer(); ?>
