<?php get_header(); ?>
<?php global $tpl_engine; ?>
<div class="single-case s-border-sides">
  <?php $tpl_engine->partial('template/pages/single/cases/hero') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/content') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/testimonials') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/results') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta', ['data' => get_field('cta_case')]) ?>
</div>
<?php get_footer(); ?>