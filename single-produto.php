<?php get_header(); ?>
<?php global $tpl_engine; ?>
<div class="single-produto">
  <?php $tpl_engine->partial('template/pages/single/pruduto/hero') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/results') ?>
  <?php $tpl_engine->partial('template/global/faq') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta') ?>
</div>
<?php get_footer(); ?>