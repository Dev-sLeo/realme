<?php

/**
 * Módulo: CTA Próximo Caso de Sucesso
 * Grupo ACF: cta_proximo_caso_sucesso
 * Campos: title (text), text (textarea), button_primary (link), button_secondary (link)
 */

$data = isset($data) && is_array($data) ? $data : get_field('cta_proximo_caso_sucesso');
if (empty($data) || !is_array($data)) {
  return;
}

$title           = isset($data['title']) ? trim((string) $data['title']) : '';
$text            = isset($data['text']) ? (string) $data['text'] : '';
$button_primary  = isset($data['button_primary']) ? $data['button_primary'] : null;
$button_secondary = isset($data['button_secondary']) ? $data['button_secondary'] : null;

// Early return quando não houver nada relevante para renderizar
$has_primary   = is_array($button_primary) && !empty($button_primary['url']) && !empty($button_primary['title']);
$has_secondary = is_array($button_secondary) && !empty($button_secondary['url']) && !empty($button_secondary['title']);

if (!$title && !trim($text) && !$has_primary && !$has_secondary) {
  return;
}

$primary_target   = $has_primary ? (!empty($button_primary['target']) ? $button_primary['target'] : '_self') : '_self';
$secondary_target = $has_secondary ? (!empty($button_secondary['target']) ? $button_secondary['target'] : '_self') : '_self';
?>

<section class="o-cta-next" aria-label="<?php echo esc_attr($title ?: 'Chamada para ação'); ?>">
  <div class="s-container">
    <div class="o-cta-next__container">
      <div class="c-cta-card">
        <?php if ($title) : ?>
          <h2 class="c-cta-card__title title__normal" data-animate="fade-up" data-animate-delay="0.1">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>

        <?php if (trim($text)) : ?>
          <div class="c-cta-card__text text__normal" data-animate="fade-up" data-animate-delay="0.15">
            <?= $text ?>
          </div>
        <?php endif; ?>

        <?php if ($has_primary || $has_secondary) : ?>
          <div class="c-cta-card__actions" role="group" aria-label="Ações" data-animate="fade-up" data-animate-delay="0.2">
            <?php if ($has_primary) : ?>
              <a
                class="button button__blue c-button--primary"
                href="<?php echo esc_url($button_primary['url']); ?>"
                target="<?php echo esc_attr($primary_target); ?>"
                <?php echo ($primary_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($button_primary['title']); ?>
              </a>
            <?php endif; ?>

            <?php if ($has_secondary) : ?>
              <a
                class="button button-border__blue c-button--secondary"
                href="<?php echo esc_url($button_secondary['url']); ?>"
                target="<?php echo esc_attr($secondary_target); ?>"
                <?php echo ($secondary_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($button_secondary['title']); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>