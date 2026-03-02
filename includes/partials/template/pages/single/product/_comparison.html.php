<?php
$data = isset($data) && is_array($data) ? $data : get_field('comparison');
if (empty($data) || !is_array($data)) {
  return;
}

$left  = is_array($data['left'] ?? null) ? $data['left'] : [];
$right = is_array($data['right'] ?? null) ? $data['right'] : [];
$cta   = is_array($data['cta'] ?? null) ? $data['cta'] : [];
$cta_2 = is_array($data['cta_2'] ?? null) ? $data['cta_2'] : [];

/**
 * Render da coluna (mesma estrutura para left/right)
 */
$render_column = function (array $col, string $side = '') {

  $title = isset($col['title']) ? trim((string) $col['title']) : '';
  $items = isset($col['items']) && is_array($col['items']) ? $col['items'] : [];
  $last  = isset($col['last_item']) && is_array($col['last_item']) ? $col['last_item'] : [];

  $has_any = $title || !empty($items) || !empty(array_filter($last));
  if (!$has_any) return;

  $side_class = $side ? ' is-' . $side : '';
?>
  <article class="o-product-comparison__column<?php echo esc_attr($side_class); ?>">
    <?php if ($title) : ?>
      <h3 data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <?php if (!empty($items)) : ?>
      <ul>
        <?php foreach ($items as $i => $item) :
          if (!is_array($item)) continue;

          $text  = isset($item['text']) ? trim((string) $item['text']) : '';
          $state = isset($item['state']) ? (string) $item['state'] : 'negative';

          if ($text === '') continue;
        ?>
          <li class="o-product-comparison__item is-<?php echo esc_attr($state); ?>" data-animate="fade-up" data-animate-delay="<?php echo 0.2 + ($i * 0.1); ?>s">

            <span class="o-product-comparison__icon" aria-hidden="true">
              <?php if ($state === 'positive') : ?>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M5 14L8.5 17.5L19 6.5"
                    stroke="#85C3D6"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>

              <?php elseif ($state === 'negative') : ?>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M18 6L12 12M12 12L6 18M12 12L18 18M12 12L6 6"
                    stroke="#FF3636"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>

              <?php endif; ?>
            </span>

            <span class="o-product-comparison__text">
              <?php echo esc_html($text); ?>
            </span>

          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php
    $last_title = isset($last['title']) ? trim((string) $last['title']) : '';
    $last_desc  = isset($last['description']) ? trim((string) $last['description']) : '';
    ?>
    <?php if ($last_title || $last_desc) : ?>
      <div class="o-product-comparison__last">
        <?php if ($last_title) : ?>
          <strong class="o-product-comparison__last-title" data-animate="fade-up" data-animate-delay="0.1">
            <?php echo esc_html($last_title); ?>
          </strong>
        <?php endif; ?>

        <?php if ($last_desc) : ?>
          <span class="o-product-comparison__last-desc" data-animate="fade-up" data-animate-delay="0.2">
            <?php echo esc_html($last_desc); ?>
          </span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </article>
<?php
};
?>

<section class="o-product-comparison">
  <div class="s-container">

    <header class="o-product-comparison__header">
      <?php if (!empty($data['eyebrown'])) : ?>
        <span class="subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($data['eyebrown']); ?></span>
      <?php endif; ?>

      <?php if (!empty($data['title'])) : ?>
        <h2 class="title__normal" data-animate="fade-up" data-animate-delay="0.2"><?php echo esc_html($data['title']); ?></h2>
      <?php endif; ?>

      <?php if (!empty($data['description'])) : ?>
        <div class="text__normal" data-animate="fade-up" data-animate-delay="0.3"><?php echo wpautop(wp_kses_post($data['description'])); ?></div>
      <?php endif; ?>
    </header>
    <div class="o-product-comparison__columns-container">
      <div class="hero-bg">
        <img src="<?= get_stylesheet_directory_uri() ?>/public/image/bg-comparsion.webp" alt="">
      </div>
      <div class="o-product-comparison__columns" data-animate="fade-up" data-animate-delay="0.4">
        <?php $render_column($left, 'left'); ?>
        <?php $render_column($right, 'right'); ?>
      </div>
    </div>

    <?php if ((!empty($cta['url']) && !empty($cta['title'])) || (!empty($cta_2['url']) && !empty($cta_2['title']))) : ?>
      <div class="o-product-comparison__actions">
        <?php if (!empty($cta['url']) && !empty($cta['title'])) : ?>
          <a class="button button__blue"
            href="<?php echo esc_url($cta['url']); ?>"
            target="<?php echo esc_attr($cta['target'] ?? '_self'); ?>">
            <?php echo esc_html($cta['title']); ?>
          </a>
        <?php endif; ?>

        <?php if (!empty($cta_2['url']) && !empty($cta_2['title'])) : ?>
          <a class="button button-border__blue"
            href="<?php echo esc_url($cta_2['url']); ?>"
            target="<?php echo esc_attr($cta_2['target'] ?? '_self'); ?>">
            <?php echo esc_html($cta_2['title']); ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</section>