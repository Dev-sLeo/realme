<?php get_header(); ?>
<?php global $tpl_engine; ?>
<div class="single-produto s-border-sides">
  <?php $tpl_engine->partial('template/pages/single/product/hero') ?>
  <?php $tpl_engine->partial('template/pages/single/product/pain-points') ?>
  <?php $tpl_engine->partial('template/pages/single/product/features-grid') ?>
  <?php $tpl_engine->partial('template/pages/single/product/how-it-works') ?>
  <?php $tpl_engine->partial('template/pages/single/product/technology') ?>
  <?php $tpl_engine->partial('template/pages/single/product/security') ?>
  <?php $tpl_engine->partial('template/pages/single/product/security-card') ?>
  <?php $tpl_engine->partial('template/pages/single/product/comparison') ?>
  <?php $tpl_engine->partial('template/global/faq') ?>
  <?php $tpl_engine->partial('template/pages/single/product/benefits') ?>
  <?php $tpl_engine->partial('template/pages/single/product/final-cta') ?>
</div>
<?php get_footer(); ?>