<?php

/**
 * Módulo: Hero Plataforma
 * Grupo ACF: hero_plataforma
 * Campos: titulo, descricao, botao_primario (link), botao_secundario (link), imagem (image)
 */

$data = isset($data) && is_array($data) ? $data : get_field('hero_plataforma');
if (empty($data) || !is_array($data)) {
  return;
}

$titulo   = isset($data['titulo']) ? trim((string) $data['titulo']) : '';
$descricao = isset($data['descricao']) ? (string) $data['descricao'] : '';

$btn_primary   = isset($data['botao_primario']) ? $data['botao_primario'] : null;
$btn_secondary = isset($data['botao_secundario']) ? $data['botao_secundario'] : null;

$imagem = isset($data['imagem']) ? $data['imagem'] : null;
$imagem_id = is_array($imagem) && !empty($imagem['ID']) ? (int) $imagem['ID'] : 0;

$has_primary = is_array($btn_primary) && !empty($btn_primary['url']) && !empty($btn_primary['title']);
$has_secondary = is_array($btn_secondary) && !empty($btn_secondary['url']) && !empty($btn_secondary['title']);

if (!$titulo && !trim($descricao) && !$has_primary && !$has_secondary && !$imagem_id) {
  return;
}

$primary_target = $has_primary ? (!empty($btn_primary['target']) ? $btn_primary['target'] : '_self') : '_self';
$secondary_target = $has_secondary ? (!empty($btn_secondary['target']) ? $btn_secondary['target'] : '_self') : '_self';
?>

<section class="o-hero-platform" aria-label="<?php echo esc_attr($titulo ?: 'Hero'); ?>">
  <div class="o-hero-platform__container">
    <div class="o-hero-platform__grid">

      <div class="o-hero-platform__content">
        <?php if ($titulo) : ?>
          <h1 class="o-hero-platform__title" data-animate="fade-up" data-animate-delay="0.1">
            <?php echo esc_html($titulo); ?>
          </h1>
        <?php endif; ?>

        <?php if (trim($descricao)) : ?>
          <div class="o-hero-platform__text" data-animate="fade-up" data-animate-delay="0.2">
            <?php echo wpautop(wp_kses_post($descricao)); ?>
          </div>
        <?php endif; ?>

        <?php if ($has_primary || $has_secondary) : ?>
          <div class="o-hero-platform__actions" role="group" aria-label="Ações" data-animate="fade-up" data-animate-delay="0.3">
            <?php if ($has_primary) : ?>
              <a
                class="c-button c-button--primary"
                href="<?php echo esc_url($btn_primary['url']); ?>"
                target="<?php echo esc_attr($primary_target); ?>"
                <?php echo ($primary_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($btn_primary['title']); ?>
              </a>
            <?php endif; ?>

            <?php if ($has_secondary) : ?>
              <a
                class="c-button c-button--secondary"
                href="<?php echo esc_url($btn_secondary['url']); ?>"
                target="<?php echo esc_attr($secondary_target); ?>"
                <?php echo ($secondary_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($btn_secondary['title']); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($imagem_id) : ?>
        <div class="o-hero-platform__media" data-animate="fade-up" data-animate-delay="0.4">
          <?php
          echo wp_get_attachment_image(
            $imagem_id,
            'large',
            false,
            array(
              'class'   => 'o-hero-platform__image',
              'loading' => 'eager',
            )
          );
          ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>