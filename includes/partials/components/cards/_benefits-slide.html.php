<?php global $tpl_engine; ?>
<?php
/**
 * Component: Benefits Slide Card
 * Uso:
 * $tpl_engine->partial('components/cards/_benefits-slide', [
 *   'vars' => [
 *     'image' => $card['image'], // array ACF com chave 'ID'
 *     'title' => 'Título do card',
 *     'text'  => 'Descrição do card...',
 *     'list'  => [['text' => 'Item 1'], ['text' => 'Item 2']],
 *     'cta'   => ['url' => '#', 'target' => '_self'],
 *   ]
 * ]);
 */

$vars  = $vars ?? [];
$image = $vars['image'] ?? null;
$title = $vars['title'] ?? '';
$text  = $vars['text'] ?? '';
$list  = is_array($vars['list'] ?? null) ? $vars['list'] : [];
$cta   = is_array($vars['cta'] ?? null) ? $vars['cta'] : [];

if (empty($title) && empty($text) && empty($image) && empty($list)) return;

$image_id = null;
if (is_array($image) && !empty($image['ID'])) $image_id = (int) $image['ID'];
if (is_numeric($image)) $image_id = (int) $image;
?>

<article class="swiper-slide o-product-benefits__slide">
  <?php if ($image_id) : ?>
    <div class="o-product-benefits__image">
      <?= wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($title)) : ?>
    <h3><?= esc_html($title); ?></h3>
  <?php endif; ?>

  <?php if (!empty($text)) : ?>
    <div><?= wpautop(wp_kses_post($text)); ?></div>
  <?php endif; ?>

  <?php if (!empty($list)) : ?>
    <ul>
      <?php foreach ($list as $item) :
        if (empty($item['text'])) continue;
      ?>
        <li>
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18.3346 10.0003C18.3346 5.39795 14.6036 1.66699 10.0013 1.66699C5.39893 1.66699 1.66797 5.39795 1.66797 10.0003C1.66797 14.6027 5.39893 18.3337 10.0013 18.3337C14.6036 18.3337 18.3346 14.6027 18.3346 10.0003Z" stroke="#5290A3" stroke-width="1.5" />
            <path d="M6.66797 10.4167L8.7513 12.5L13.3346 7.5" stroke="#5290A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <?= esc_html($item['text']); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <?php if (!empty($cta['url'])) : ?>
    <a
      class="o-product-benefits__cta button button-border__blue"
      href="<?= esc_url($cta['url']); ?>"
      target="<?= esc_attr($cta['target'] ?? '_self'); ?>">Ver mais</a>
  <?php endif; ?>
</article>