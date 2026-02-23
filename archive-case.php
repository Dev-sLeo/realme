<?php //Template name: Archive Cases
?>
<?php get_header(); ?>
<?php global $tpl_engine; ?>
<?php
$cta = get_field('cta_final');
$cta_data = array(
  'title' => $cta['titulo'] ?? '',
  'text' => $cta['descricao'] ?? '',
  'button_primary' => $cta['botao_primario'] ?? array(),
  'button_secondary' => $cta['botao_secundario'] ?? array(),
);
?>
<div class="archive-cases s-border-sides">
  <?php $tpl_engine->partial('template/pages/archive/case/hero'); ?>
  <?php $tpl_engine->partial('template/pages/archive/case/cases-feature'); ?>
  <?php $tpl_engine->partial('template/pages/archive/case/other-results'); ?>
  <?php $tpl_engine->partial('template/pages/home/final-cta', $cta_data); ?>
</div>
<?php get_footer(); ?>
