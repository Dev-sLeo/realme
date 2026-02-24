<?php
$data = isset($data) && is_array($data) ? $data : get_field('comparison');
if (empty($data) || !is_array($data)) {
  return;
}
$left = is_array($data['left'] ?? null) ? $data['left'] : array();
$right = is_array($data['right'] ?? null) ? $data['right'] : array();
$cta = is_array($data['cta'] ?? null) ? $data['cta'] : array();
?>
<section class="o-product-comparison">
  <div class="s-container">
    <header class="o-product-comparison__header">
      <?php if (!empty($data['eyebrown'])) : ?>
        <span class="subtitulo"><?php echo esc_html($data['eyebrown']); ?></span>
      <?php endif; ?>
      <?php if (!empty($data['title'])) : ?><h2 class="title__normal"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
      <?php if (!empty($data['description'])) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($data['description'])); ?></div><?php endif; ?>
    </header>
    <div class="o-product-comparison__columns">
      <article class="o-product-comparison__column">
        <?php if (!empty($left['title'])) : ?><h3><?php echo esc_html($left['title']); ?></h3><?php endif; ?>
        <?php if (!empty($left['items']) && is_array($left['items'])) : ?>
          <ul>
            <?php foreach ($left['items'] as $item) : if (empty($item['text'])) continue; ?><li class="is-<?php echo esc_attr($item['state'] ?? 'neutral'); ?>"><?php echo esc_html($item['text']); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </article>
      <article class="o-product-comparison__column">
        <?php if (!empty($right['title'])) : ?><h3><?php echo esc_html($right['title']); ?></h3><?php endif; ?>
        <?php if (!empty($right['items']) && is_array($right['items'])) : ?>
          <ul>
            <?php foreach ($right['items'] as $item) : if (empty($item['text'])) continue; ?><li class="is-<?php echo esc_attr($item['state'] ?? 'neutral'); ?>"><?php echo esc_html($item['text']); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </article>
    </div>
    <?php if (!empty($cta['url']) && !empty($cta['title'])) : ?>
      <div class="o-product-comparison__actions">
        <a class="button button__blue" href="<?php echo esc_url($cta['url']); ?>" target="<?php echo esc_attr($cta['target'] ?? '_self'); ?>"><?php echo esc_html($cta['title']); ?></a>
      </div>
    <?php endif; ?>
  </div>
</section>