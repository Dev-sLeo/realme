<?php global $tpl_engine; ?>
<?php
/**
 * Component: Feature Card
 * Uso:
 * $tpl_engine->partial('components/feature-card/feature-card', [
 *   'vars' => [
 *     'image'  => $image, // ID ou array ACF
 *     'title'  => 'WhatsApp Unificado',
 *     'text'   => 'Centralize todas as conversas...',
 *     'items'  => ['Número único...', 'Histórico...', 'Status...'],
 *     'button' => ['url' => '#', 'title' => 'Veja mais', 'target' => ''],
 *   ]
 * ]);
 */

$vars   = $vars ?? [];
$image  = $vars['image'] ?? null;
$title  = $vars['title'] ?? '';
$text   = $vars['text'] ?? '';
$items  = $vars['items'] ?? [];
$button = $vars['button'] ?? null;
$theme  = $vars['theme'] ?? 'default';

if (empty($title) && empty($text) && empty($image) && empty($items) && empty($button)) return;

// normaliza image id
$image_id = null;
if (is_array($image) && !empty($image['ID'])) $image_id = (int) $image['ID'];
if (is_numeric($image)) $image_id = (int) $image;

$classes = 'c-feature-card';
$classes .= ' c-feature-card--' . esc_attr($theme);
?>

<article class="<?= esc_attr($classes); ?>">
  <div class="c-feature-card__media">
    <?php if ($image_id): ?>
      <div class="c-feature-card__media-inner">
        <?= wp_get_attachment_image($image_id, 'large', false, [
          'class' => 'c-feature-card__image',
          'loading' => 'lazy',
        ]); ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="c-feature-card__body">
    <?php if (!empty($title)) : ?>
      <h3 class="c-feature-card__title"><?= esc_html($title); ?></h3>
    <?php endif; ?>

    <?php if (!empty($text)) : ?>
      <div class="c-feature-card__text">
        <?= wpautop(wp_kses_post($text)); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($items) && is_array($items)) : ?>
      <ul class="c-feature-card__list" role="list">
        <?php foreach ($items as $it): ?>
          <?php if (empty($it)) continue; ?>
          <li class="c-feature-card__item">
            <span class="c-feature-card__check" aria-hidden="true">
              <?= $tpl_engine->svg('icons/check'); ?>
            </span>
            <span class="c-feature-card__item-text"><?= esc_html($it); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>


    <div class="c-feature-card__actions">
      <a class="c-feature-card__button button button-border__blue"
        href="<?= esc_url($button); ?>">
        <span class="c-button__label">Saiba mais</span>
      </a>
    </div>

  </div>
</article>