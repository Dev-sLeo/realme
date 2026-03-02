<?php global $tpl_engine; ?>
<?php
$data = isset($data) && is_array($data) ? $data : get_field('how_it_works');
if (empty($data) || !is_array($data)) {
  return;
}
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$steps = is_array($data['steps'] ?? null) ? $data['steps'] : array();
$cta_primary = is_array($data['cta_primary'] ?? null) ? $data['cta_primary'] : array();
$cta_secondary = is_array($data['cta_secondary'] ?? null) ? $data['cta_secondary'] : array();
$resultado = is_array($data['resultado'] ?? null) ? $data['resultado'] : array();

$res_title = (string) ($resultado['title'] ?? '');
$res_number = (string) ($resultado['number'] ?? '');
$res_desc = (string) ($resultado['descriptin'] ?? '');
$res_icon = $resultado['icon'] ?? null;
$res_icon_html = '';
if (!empty($res_icon['ID'])) {
  $res_icon_html = wp_get_attachment_image((int) $res_icon['ID'], 'thumbnail', false, array('loading' => 'lazy', 'decoding' => 'async'));
}
?>
<section class="o-product-how-it-works">
  <div class="s-container">
    <header class="o-product-how-it-works__header">
      <?php if (!empty($title)) : ?><h2 class="title__normal" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if (!empty($description)) : ?><div class="text__normal" data-animate="fade-up" data-animate-delay="0.2"><?php echo wpautop(wp_kses_post($description)); ?></div><?php endif; ?>
    </header>

    <?php if (!empty($steps)) : ?>
      <div class="o-product-how-it-works__grid">
        <?php foreach ($steps as $i => $step) :
          $step_title = $step['title'] ?? '';
          $step_text = $step['text'] ?? '';
          $num = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
        ?>
          <article class="c-step o-como-funciona__card" role="listitem" data-animate="fade-up" data-animate-delay="<?php echo 0.3 + ($i * 0.1); ?>s">
            <div class="c-step__top">
              <span class="c-step__number" aria-hidden="true"><?= esc_html($num); ?></span>

              <span class="c-step__arrow" aria-hidden="true">
                <?= $tpl_engine->svg('icons/arrow-right'); ?>
              </span>
            </div>

            <?php if ($step_title !== '') : ?>
              <h3 class="c-step__title"><?= esc_html($step_title); ?></h3>
            <?php endif; ?>

            <?php if ($step_text !== '') : ?>
              <div class="c-step__text">
                <?= wpautop(wp_kses_post($step_text)); ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($res_title !== '' || $res_number !== '' || $res_desc !== '' || $res_icon_html !== '') : ?>
      <div class="o-product-how-it-works__result">
        <?php if ($res_title !== '') : ?>
          <div class="o-product-how-it-works__result-title" data-animate="fade-up" data-animate-delay="0.1"><?= esc_html($res_title); ?></div>
        <?php endif; ?>



        <?php if ($res_number !== '') : ?>
          <span class="o-product-how-it-works__result-number" data-animate="fade-up" data-animate-delay="0.2">
            <?php if ($res_icon_html !== '') : ?>
              <span class="o-product-how-it-works__result-icon" aria-hidden="true"><?= $res_icon_html; ?></span>
            <?php endif; ?>
            <?= esc_html($res_number); ?>
          </span>
        <?php endif; ?>

        <div class="o-product-how-it-works__result-body">
          <?php if ($res_desc !== '') : ?>
            <div class="o-product-how-it-works__result-desc" data-animate="fade-up" data-animate-delay="0.3"><?= wpautop(wp_kses_post($res_desc)); ?></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($cta_primary['url']) || !empty($cta_secondary['url'])) : ?>
      <div class="o-product-how-it-works__actions">
        <?php if (!empty($cta_primary['url']) && !empty($cta_primary['title'])) : ?><a class="button button__blue" href="<?php echo esc_url($cta_primary['url']); ?>" target="<?php echo esc_attr($cta_primary['target'] ?? '_self'); ?>"><?php echo esc_html($cta_primary['title']); ?></a><?php endif; ?>
        <?php if (!empty($cta_secondary['url']) && !empty($cta_secondary['title'])) : ?><a class="button button-border__blue" href="<?php echo esc_url($cta_secondary['url']); ?>" target="<?php echo esc_attr($cta_secondary['target'] ?? '_self'); ?>"><?php echo esc_html($cta_secondary['title']); ?></a><?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>