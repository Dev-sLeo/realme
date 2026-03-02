<?php global $tpl_engine; ?>
<?php
/**
 * Módulo: Como a Rafa funciona
 * Grupo ACF: como_funciona
 *
 * Campos:
 * - titulo (text)
 * - descricao (textarea)
 * - cards (repeater) -> titulo (text), descricao (textarea)
 * - button_primary (link)
 * - button_secondary (link)
 */

$data = $data ?? get_field('como_funciona');
$data = is_array($data) ? $data : [];
if (empty($data))
  return;

$titulo = !empty($data['titulo']) ? (string) $data['titulo'] : '';
$descricao = !empty($data['descricao']) ? (string) $data['descricao'] : '';

$cards = (!empty($data['cards']) && is_array($data['cards'])) ? $data['cards'] : [];

$button_primary = (!empty($data['button_primary']) && is_array($data['button_primary'])) ? $data['button_primary'] : null;
$button_secondary = (!empty($data['button_secondary']) && is_array($data['button_secondary'])) ? $data['button_secondary'] : null;

$has_header = ($titulo !== '' || $descricao !== '');
$has_cards = (!empty($cards));
$has_actions = (!empty($button_primary) || !empty($button_secondary));

if (!$has_header && !$has_cards && !$has_actions)
  return;

/**
 * wpautop com classe no primeiro <p>
 */
$format_p_class = function ($content, $p_class) {
  $content = trim((string) $content);
  if ($content === '')
    return '';

  $html = wpautop(wp_kses_post($content));
  $html = preg_replace('/<p>/', '<p class="' . esc_attr($p_class) . '">', $html, 1);

  if (strpos($html, '<p') === false) {
    $html = '<p class="' . esc_attr($p_class) . '">' . wp_kses_post($content) . '</p>';
  }

  return $html;
};

$descricao_html = $format_p_class($descricao, 'text__normal');

$btn_norm = function ($btn) {
  if (empty($btn) || !is_array($btn) || empty($btn['url']) || empty($btn['title']))
    return null;

  $url = (string) $btn['url'];
  $lbl = (string) $btn['title'];
  $tgt = !empty($btn['target']) ? (string) $btn['target'] : '_self';
  $rel = ($tgt === '_blank') ? 'noopener noreferrer' : '';

  return [
    'url' => $url,
    'lbl' => $lbl,
    'tgt' => $tgt,
    'rel' => $rel,
  ];
};

$btn_primary = $btn_norm($button_primary);
$btn_secondary = $btn_norm($button_secondary);
?>

<section class="o-como-funciona" aria-label="Como a Rafa funciona">
  <div class="s-container">

    <?php if ($has_header): ?>
      <header class="o-como-funciona__header">
        <?php if ($titulo !== ''): ?>
          <h2 class="o-como-funciona__title title__normal" data-animate="fade-up" data-animate-delay="0.1"><?= esc_html($titulo); ?></h2>
        <?php endif; ?>

        <?php if ($descricao_html !== ''): ?>
          <div class="o-como-funciona__description">
            <?= $descricao_html; ?>
          </div>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <?php if ($has_cards): ?>
      <div class="o-como-funciona__cards" role="list">
        <?php foreach ($cards as $i => $card):
          if (!is_array($card))
            continue;

          $ct = !empty($card['titulo']) ? (string) $card['titulo'] : '';
          $cd = !empty($card['descricao']) ? (string) $card['descricao'] : '';

          if ($ct === '' && $cd === '')
            continue;

          $num = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
          $cd_html = $format_p_class($cd, 'text__normal');
        ?>
          <article class="c-step o-como-funciona__card" role="listitem" data-animate="fade-up" data-animate-delay="<?php echo 0.2 + ($i * 0.05); ?>">
            <div class="c-step__top">
              <span class="c-step__number" aria-hidden="true"><?= esc_html($num); ?></span>

              <span class="c-step__arrow" aria-hidden="true">
                <?= $tpl_engine->svg('icons/arrow-right'); ?>
              </span>
            </div>

            <?php if ($ct !== ''): ?>
              <h3 class="c-step__title"><?= esc_html($ct); ?></h3>
            <?php endif; ?>

            <?php if ($cd_html !== ''): ?>
              <div class="c-step__text">
                <?= $cd_html; ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($btn_primary || $btn_secondary): ?>
      <div class="o-como-funciona__actions" data-animate="fade-up" data-animate-delay="<?php echo 0.2 + ($has_cards ? (count($cards) * 0.05) : 0); ?>">

        <?php if ($btn_primary): ?>
          <a class="button button__blue" href="<?= esc_url($btn_primary['url']); ?>"
            target="<?= esc_attr($btn_primary['tgt']); ?>" <?= $btn_primary['rel'] ? 'rel="' . esc_attr($btn_primary['rel']) . '"' : ''; ?>>
            <?= esc_html($btn_primary['lbl']); ?>
          </a>
        <?php endif; ?>

        <?php if ($btn_secondary): ?>
          <a class="button button-border__blue" href="<?= esc_url($btn_secondary['url']); ?>"
            target="<?= esc_attr($btn_secondary['tgt']); ?>" <?= $btn_secondary['rel'] ? 'rel="' . esc_attr($btn_secondary['rel']) . '"' : ''; ?>>
            <?= esc_html($btn_secondary['lbl']); ?>
          </a>
        <?php endif; ?>

      </div>
    <?php endif; ?>

  </div>
</section>