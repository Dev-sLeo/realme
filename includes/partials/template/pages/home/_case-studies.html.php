<?php

/**
 * Módulo: Cases de sucesso
 * Grupo ACF (dentro do group_home_modulos): case_studies
 */

$data = isset($data) && is_array($data) ? $data : get_field('case_studies');
if (empty($data) || !is_array($data)) {
  return;
}

$sub_titulo = isset($data['sub_titulo']) ? trim((string) $data['sub_titulo']) : '';
$title      = isset($data['title']) ? trim((string) $data['title']) : '';
$text       = isset($data['text']) ? (string) $data['text'] : '';

$items  = (isset($data['items']) && is_array($data['items'])) ? $data['items'] : [];
$button = isset($data['button']) ? $data['button'] : null;

$has_items  = !empty($items);
$has_button = is_array($button) && !empty($button['url']) && !empty($button['title']);

if (!$sub_titulo && !$title && !trim($text) && !$has_items && !$has_button) {
  return;
}

$button_target = $has_button ? (!empty($button['target']) ? $button['target'] : '_self') : '_self';
?>

<section class="o-case-studies" aria-label="<?php echo esc_attr($title ?: 'Cases de sucesso'); ?>">
  <div class="s-container">
    <div class="o-case-studies__container">

      <header class="o-case-studies__header">
        <?php if ($sub_titulo) : ?>
          <p class="o-case-studies__eyebrow subtitulo">
            <?php echo esc_html($sub_titulo); ?>
          </p>
        <?php endif; ?>

        <?php if ($title) : ?>
          <h2 class="o-case-studies__title title__normal">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>

        <?php if (trim($text)) : ?>
          <div class="o-case-studies__text text__normal">
            <?php echo wpautop(wp_kses_post($text)); ?>
          </div>
        <?php endif; ?>
      </header>

      <?php if ($has_items) : ?>
        <div class="o-case-studies__slider">

          <div class="swiper o-case-studies__swiper" data-swiper="case-studies">
            <div class="swiper-wrapper">
              <?php foreach ($items as $item) :
                if (!is_array($item)) {
                  continue;
                }

                // Campos conforme imagem (subcampos do repeater)
                $metrica      = isset($item['metrica']) ? trim((string) $item['metrica']) : '';
                $icone        = isset($item['icone']) ? $item['icone'] : null; // imagem
                $descricao    = isset($item['descricao']) ? trim((string) $item['descricao']) : '';
                $texto_item   = isset($item['texto']) ? (string) $item['texto'] : '';

                $logo         = isset($item['logo']) ? $item['logo'] : null; // imagem
                $nome_cliente = isset($item['nome_cliente']) ? trim((string) $item['nome_cliente']) : '';
                $local        = isset($item['local']) ? trim((string) $item['local']) : '';

                $icone_id = is_array($icone) && !empty($icone['ID']) ? (int) $icone['ID'] : 0;
                $logo_id  = is_array($logo) && !empty($logo['ID']) ? (int) $logo['ID'] : 0;

                // Se estiver tudo vazio, pula
                if (
                  !$metrica &&
                  !$descricao &&
                  !trim($texto_item) &&
                  !$icone_id &&
                  !$logo_id &&
                  !$nome_cliente &&
                  !$local
                ) {
                  continue;
                }
              ?>
                <div class="swiper-slide">
                  <article class="c-case-card">
                    <div class="c-case-card__content">
                      <div class="c-case-card__content-header">
                        <div class="c-case-card__content-header-icon">

                          <?php if ($metrica) : ?>
                            <p class="c-case-card__metric">
                              <?php echo esc_html($metrica); ?>
                            </p>
                          <?php endif; ?>

                          <?php if ($icone_id) : ?>
                            <div class="c-case-card__icon" aria-hidden="true">
                              <?php
                              echo wp_get_attachment_image(
                                $icone_id,
                                'thumbnail',
                                false,
                                array('class' => 'c-case-card__icon-img', 'loading' => 'lazy')
                              );
                              ?>
                            </div>
                          <?php endif; ?>

                        </div>

                        <?php if ($descricao) : ?>
                          <h3 class="c-case-card__title">
                            <?php echo esc_html($descricao); ?>
                          </h3>
                        <?php endif; ?>
                      </div>
                    </div>

                    <?php if (trim($texto_item)) : ?>
                      <div class="c-case-card__text">
                        <svg width="28" height="21" viewBox="0 0 28 21" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                          <path d="M0 21V15.4737C0 13.7953 0.296784 12.0146 0.890351 10.1316C1.50439 8.22807 2.3845 6.3962 3.5307 4.63597C4.69737 2.85526 6.09941 1.30994 7.73684 0L11.6667 3.19298C10.3772 5.03509 9.25146 6.95906 8.28947 8.96491C7.34795 10.9503 6.87719 13.0789 6.87719 15.3509V21H0ZM15.7193 21V15.4737C15.7193 13.7953 16.0161 12.0146 16.6096 10.1316C17.2237 8.22807 18.1038 6.3962 19.25 4.63597C20.4167 2.85526 21.8187 1.30994 23.4561 0L27.386 3.19298C26.0965 5.03509 24.9708 6.95906 24.0088 8.96491C23.0672 10.9503 22.5965 13.0789 22.5965 15.3509V21H15.7193Z" fill="#A3D2E0" />
                        </svg>

                        <?php echo wpautop(wp_kses_post($texto_item)); ?>
                      </div>
                    <?php endif; ?>

                    <div class="c-case-card__footer">
                      <?php if ($logo_id) : ?>
                        <div class="c-case-card__logo" aria-hidden="true">
                          <?php
                          echo wp_get_attachment_image(
                            $logo_id,
                            'medium',
                            false,
                            array('class' => 'c-case-card__logo-img', 'loading' => 'lazy')
                          );
                          ?>
                        </div>
                      <?php endif; ?>

                      <?php if ($nome_cliente || $local) : ?>
                        <div class="c-case-card__meta">
                          <?php if ($nome_cliente) : ?>
                            <p class="c-case-card__client">
                              <?php echo esc_html($nome_cliente); ?>
                            </p>
                          <?php endif; ?>

                          <?php if ($local) : ?>
                            <p class="c-case-card__place">
                              <?php echo esc_html($local); ?>
                            </p>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </article>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="arrow-container">
              <button class="o-case-studies__arrow o-case-studies__arrow--prev" type="button" aria-label="Anterior"><svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M10.5 20L0.999996 10.5L10.5 1" stroke="#4353FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

              </button>
              <button class="o-case-studies__arrow o-case-studies__arrow--next" type="button" aria-label="Próximo"><svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 20L10.5 10.5L1 1" stroke="#4353FA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>
            </div>
          </div>

        </div>
      <?php endif; ?>

      <?php if ($has_button) : ?>
        <div class="o-case-studies__cta">
          <a
            class="c-button button button__blue"
            href="<?php echo esc_url($button['url']); ?>"
            target="<?php echo esc_attr($button_target); ?>"
            <?php echo ($button_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
            <?php echo esc_html($button['title']); ?>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
  <svg width="86" height="86" viewBox="0 0 86 86" fill="none" xmlns="http://www.w3.org/2000/svg" class="arrow-down-section">
    <circle cx="43" cy="43" r="43" fill="white" />
    <path d="M53 40L43.5 49.5L34 40" stroke="#85C3D6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
  </svg>



</section>