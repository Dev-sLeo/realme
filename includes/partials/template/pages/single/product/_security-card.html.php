<?php

/**
 * Módulo: Security Cards
 * Grupo ACF (dentro do group_home_modulos): security_cards
 */

$data = isset($data) && is_array($data) ? $data : get_field('security_cards');
if (empty($data) || !is_array($data)) {
  return;
}

$eyebrown     = isset($data['eyebrown']) ? trim((string) $data['eyebrown']) : '';
$title        = isset($data['title']) ? trim((string) $data['title']) : '';
$description  = isset($data['description']) ? (string) $data['description'] : '';
$cards        = (isset($data['cards']) && is_array($data['cards'])) ? $data['cards'] : [];

$has_cards = !empty($cards);

if (!$eyebrown && !$title && !trim($description) && !$has_cards) {
  return;
}
?>

<section class="o-security-cards" aria-label="<?php echo esc_attr($title ?: 'Segurança'); ?>">
  <div class="s-container">
    <div class="o-security-cards__container">

      <header class="o-security-cards__header">
        <?php if ($eyebrown) : ?>
          <p class="o-security-cards__eyebrow subtitulo">
            <?php echo esc_html($eyebrown); ?>
          </p>
        <?php endif; ?>

        <?php if ($title) : ?>
          <h2 class="o-security-cards__title title__normal">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>

        <?php if (trim($description)) : ?>
          <div class="o-security-cards__description text__normal">
            <?php echo wpautop(wp_kses_post($description)); ?>
          </div>
        <?php endif; ?>
      </header>

      <?php if ($has_cards) : ?>
        <div class="o-security-cards__grid" role="list">
          <?php foreach ($cards as $card) :

            if (!is_array($card)) continue;

            $icon = $card['icone'] ?? null;
            $card_title = isset($card['title']) ? trim((string) $card['title']) : '';
            $card_desc  = isset($card['description']) ? (string) $card['description'] : '';

            if (!$card_title && !trim($card_desc) && empty($icon)) {
              continue;
            }

            // normaliza image id (padrão do tema)
            $icon_id = null;
            if (is_array($icon) && !empty($icon['ID'])) $icon_id = (int) $icon['ID'];
            if (is_numeric($icon)) $icon_id = (int) $icon;

          ?>
            <article class="o-security-cards__card" role="listitem">
              <?php if ($icon_id) : ?>
                <div class="o-security-cards__card-icon">
                  <?php echo wp_get_attachment_image($icon_id, 'full', false, [
                    'class' => 'o-security-cards__icon',
                    'loading' => 'lazy',
                  ]); ?>
                </div>
              <?php endif; ?>

              <?php if ($card_title) : ?>
                <h3 class="o-security-cards__card-title">
                  <?php echo esc_html($card_title); ?>
                </h3>
              <?php endif; ?>

              <?php if (trim($card_desc)) : ?>
                <div class="o-security-cards__card-text">
                  <?php echo wpautop(wp_kses_post($card_desc)); ?>
                </div>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>