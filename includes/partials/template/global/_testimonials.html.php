<?php
$section = get_field('testimonials');
$section = is_array($section) ? $section : [];

$sub_title = isset($section['sub_titulo']) ? (string) $section['sub_titulo'] : '';
$title     = isset($section['title']) ? (string) $section['title'] : '';
$text      = isset($section['text']) ? (string) $section['text'] : '';

$terms = get_terms([
  'taxonomy'   => 'tipo',
  'hide_empty' => true,
]);

$has_header = ($sub_title !== '' || $title !== '' || $text !== '');
$has_terms  = (!is_wp_error($terms) && !empty($terms));

if (!$has_header && !$has_terms) {
  return;
}

$nonce = wp_create_nonce('testimonials_filter');

$svg_prev = '<svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 20L0.999996 10.5L10.5 1" stroke="#4353FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$svg_next = '<svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 20L10.5 10.5L1 1" stroke="#4353FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>

<section
  class="o-testimonials js-module"
  aria-label="Depoimentos"
  data-action="testimonials_filter"
  data-nonce="<?php echo esc_attr($nonce); ?>">
  <div class="s-container">

    <?php if ($has_header) : ?>
      <header class="o-testimonials__header">
        <?php if ($sub_title !== '') : ?>
          <div class="o-testimonials__subtitle subtitulo"><?php echo $sub_title; ?></div>
        <?php endif; ?>

        <?php if ($title !== '') : ?>
          <h2 class="o-testimonials__title title__normal"><?php echo $title; ?></h2>
        <?php endif; ?>

        <?php if ($text !== '') : ?>
          <div class="o-testimonials__text text__normal"><?php echo $text; ?></div>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <div class="o-testimonials__slider" aria-busy="false">
      <button class="o-testimonials__nav o-testimonials__nav--prev" type="button" aria-label="Anterior" data-testimonials-prev>
        <?php echo $svg_prev; ?>
      </button>

      <div class="swiper o-testimonials__swiper" data-swiper="testimonials">
        <div class="swiper-wrapper js-list"></div>
      </div>

      <button class="o-testimonials__nav o-testimonials__nav--next" type="button" aria-label="Próximo" data-testimonials-next>
        <?php echo $svg_next; ?>
      </button>
    </div>

    <div class="o-testimonials__empty js-empty" hidden>
      <div class="text__normal">Nenhum depoimento encontrado.</div>
    </div>

  </div>
</section>