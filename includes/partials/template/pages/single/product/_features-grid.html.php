<?php
$data = isset($data) && is_array($data) ? $data : get_field('features_grid');
if (empty($data) || !is_array($data)) {
  return;
}

$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$items = !empty($data['items']) && is_array($data['items']) ? $data['items'] : array();
if (empty($title) && empty($description) && empty($items)) {
  return;
}
?>
<section class="o-product-features-grid">
  <div class="s-container">
    <header class="o-product-features-grid__header">
      <?php if (!empty($title)) : ?><h2 class="title__normal"><?php echo esc_html($title); ?></h2><?php endif; ?>
      <?php if (!empty($description)) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($description)); ?></div><?php endif; ?>
    </header>

    <?php if (!empty($items)) : ?>
      <div class="o-product-features-grid__grid">
        <?php foreach ($items as $item) :
          $image = !empty($item['image']['ID']) ? (int) $item['image']['ID'] : 0;
          $item_title = $item['title'] ?? '';
          $item_text = $item['text'] ?? '';
          $link = is_array($item['link'] ?? null) ? $item['link'] : array();
          $url = $link['url'] ?? '';
          $link_title = $link['title'] ?? '';
          $target = $link['target'] ?? '_self';
        ?>
          <article class="o-product-feature-item">
            <?php if ($image) : ?><div class="o-product-feature-item__image"><?php echo wp_get_attachment_image($image, 'medium', false, array('loading' => 'lazy')); ?></div><?php endif; ?>
            <?php if (!empty($item_title)) : ?><h3 class="o-product-feature-item__title"><?php echo esc_html($item_title); ?></h3><?php endif; ?>
            <?php if (!empty($item_text)) : ?><div class="o-product-feature-item__text"><?php echo wpautop(wp_kses_post($item_text)); ?></div><?php endif; ?>
            <?php if (!empty($url) && !empty($link_title)) : ?>
              <a class="o-product-feature-item__link" href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>" <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($link_title); ?></a>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
