<?php //Template name: Archive Cases
?>
<?php get_header(); ?>
<?php global $tpl_engine; ?>
<div class="archive-cases s-border-sides">
  <?php $tpl_engine->partial('template/pages/archive/case/hero'); ?>
  <?php $tpl_engine->partial('template/pages/archive/case/cases-feature'); ?>
  <?php $tpl_engine->partial('template/pages/archive/case/other-results'); ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta', ['data' => get_field('cta_final')]) ?>
</div>
<?php get_footer(); ?>