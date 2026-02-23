<?php get_header(); ?>
<?php global $tpl_engine; ?>
<?php
$cta = get_field('cta_case');
$cta_data = array(
  'title' => $cta['title'] ?? '',
  'text' => $cta['description'] ?? '',
  'button_primary' => $cta['button_primary'] ?? array(),
  'button_secondary' => $cta['button_secondary'] ?? array(),
);
?>
<div class="single-case">
  <?php $tpl_engine->partial('template/pages/single/cases/hero') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/content') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/testimonials') ?>
  <?php $tpl_engine->partial('template/pages/single/cases/results') ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta', $cta_data) ?>
</div>
<?php get_footer(); ?>
