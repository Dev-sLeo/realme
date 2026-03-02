<?php

/**
 * Módulo: Integrações
 * Grupo ACF (dentro do group_home_modulos): integrations
 */

$data = isset($data) && is_array($data) ? $data : get_field('integrations');
if (empty($data) || !is_array($data)) {
  return;
}

$sub_titulo         = isset($data['sub_titulo']) ? trim((string) $data['sub_titulo']) : '';
$title              = isset($data['title']) ? trim((string) $data['title']) : '';
$text               = isset($data['text']) ? (string) $data['text'] : '';
$text_right         = isset($data['text_right']) ? trim((string) $data['text_right']) : '';
$description_footer  = isset($data['description_footer']) ? trim((string) $data['description_footer']) : '';
$button             = isset($data['button']) ? $data['button'] : null;

$logos = (isset($data['logos']) && is_array($data['logos'])) ? $data['logos'] : [];

$has_logos  = !empty($logos);
$has_button = is_array($button) && !empty($button['url']) && !empty($button['title']);

if (
  !$sub_titulo &&
  !$title &&
  !trim($text) &&
  !$text_right &&
  !$has_logos &&
  !$description_footer &&
  !$has_button
) {
  return;
}

$button_target = $has_button ? (!empty($button['target']) ? $button['target'] : '_self') : '_self';
?>

<section class="o-integrations" aria-label="<?php echo esc_attr($title ?: 'Integrações'); ?>">
  <div class="s-container">
    <div class="o-integrations__container">

      <header class="o-integrations__header">
        <?php if ($sub_titulo) : ?>
          <p class="o-integrations__eyebrow subtitulo" data-animate="fade-up" data-animate-delay="0.1">
            <?php echo esc_html($sub_titulo); ?>
          </p>
        <?php endif; ?>

        <div class="o-integrations__header-grid">
          <div class="o-integrations__header-left">
            <?php if ($title) : ?>
              <h2 class="o-integrations__title title__normal" data-animate="fade-up" data-animate-delay="0.15">
                <?php echo esc_html($title); ?>
              </h2>
            <?php endif; ?>

            <?php if (trim($text)) : ?>
              <div class="o-integrations__text text__normal" data-animate="fade-up" data-animate-delay="0.2">
                <?php echo wpautop(wp_kses_post($text)); ?>
              </div>
            <?php endif; ?>
          </div>

          <?php if ($text_right) : ?>
            <div class="o-integrations__header-right" data-animate="fade-up" data-animate-delay="0.25">
              <p class="o-integrations__text-right">
                <?php echo esc_html($text_right); ?>
              </p>
            </div>
          <?php endif; ?>
        </div>
      </header>

      <?php if ($has_logos) : ?>
        <div
          class="splide o-integrations__splide"
          aria-label="<?php echo esc_attr($title ?: 'Integrações'); ?>" data-animate="fade-up" data-animate-delay="0.3">
          <div class="splide__track">
            <ul class="splide__list">
              <?php foreach ($logos as $row) :
                if (!is_array($row)) {
                  continue;
                }

                // Subcampos do repeater (mantidos como no seu código)
                $image = isset($row['image']) ? $row['image'] : null;
                $image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;

                $link = isset($row['link']) ? $row['link'] : null;
                $has_link = is_array($link) && !empty($link['url']);
                $link_target = $has_link ? (!empty($link['target']) ? $link['target'] : '_self') : '_self';

                if (!$image_id && !$has_link) {
                  continue;
                }

                $aria = (!empty($link['title'])) ? (string) $link['title'] : 'Integração';
              ?>
                <li class="splide__slide">
                  <div class="c-integration-logo">
                    <?php if ($has_link) : ?>
                      <a
                        class="c-integration-logo__link"
                        href="<?php echo esc_url($link['url']); ?>"
                        target="<?php echo esc_attr($link_target); ?>"
                        <?php echo ($link_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>
                        aria-label="<?php echo esc_attr($aria); ?>">
                      <?php endif; ?>

                      <?php if ($image_id) : ?>
                        <?php
                        echo wp_get_attachment_image(
                          $image_id,
                          'medium',
                          false,
                          array(
                            'class'   => 'c-integration-logo__img',
                            'loading' => 'lazy',
                          )
                        );
                        ?>
                      <?php elseif (!empty($link['title'])) : ?>
                        <span class="c-integration-logo__text"><?php echo esc_html($link['title']); ?></span>
                      <?php endif; ?>

                      <?php if ($has_link) : ?>
                      </a>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($description_footer || $has_button) : ?>
        <footer class="o-integrations__footer" data-animate="fade-up" data-animate-delay="0.35">
          <?php if ($description_footer) : ?>
            <div class="o-integrations__footer-left">
              <p class="o-integrations__footer-text">
                <?php echo esc_html($description_footer); ?>
              </p>

              <span class="o-integrations__footer-icon" aria-hidden="true">
                <!-- Se você tiver padrão de SVG no tema, troque por: $tpl_engine->svg('caminho/do/svg') -->
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="28" viewBox="0 0 36 28" fill="none">
                  <path d="M27.6659 12.5369L28.8511 11.7519C29.5897 11.2884 30.1718 10.834 30.6798 10.488L31.3708 10.0089L31.9131 9.60407C32.2353 9.37068 32.487 9.19909 32.683 9.07711C33.4643 8.58457 33.3567 8.89434 33.4813 9.0475C33.6058 9.20066 33.9103 9.15273 33.2548 9.85805C33.0897 10.0311 32.8652 10.2529 32.5628 10.5347L32.0506 11.0102L31.3767 11.5488C30.8856 11.9385 30.2878 12.4247 29.5373 12.9208L28.327 13.7432C27.8811 14.0293 27.3979 14.3062 26.8814 14.6225C24.4385 16.0579 21.8639 17.2566 19.1929 18.2021C16.2973 19.2218 13.3011 19.9293 10.2552 20.3126C7.83672 20.6303 5.39247 20.7051 2.95905 20.5358C2.23194 20.4928 1.50781 20.4089 0.790112 20.2846C0.28017 20.203 0.00411977 20.1353 0.010455 20.0933C0.0279859 19.8445 4.55673 20.3266 10.1605 19.3283C13.1158 18.8236 16.0153 18.0342 18.8186 16.9713C21.3959 15.989 23.8858 14.7913 26.262 13.3908L27.6557 12.5462" fill="black" fill-opacity="0.6" />
                  <path d="M32.2456 8.05178C31.3741 7.94478 30.5957 7.82898 29.9543 7.71909C28.6444 7.49664 27.8441 7.2766 27.863 7.11908C27.8819 6.96156 28.7097 6.89176 30.0457 6.911C30.7119 6.92016 31.4992 6.95115 32.377 7.00036C32.8206 7.03008 33.2539 7.05331 33.7683 7.10041C34.0979 7.12261 34.4151 7.23462 34.6855 7.42428C34.8714 7.56755 35.0107 7.76275 35.0858 7.98519C35.155 8.19926 35.1763 8.42591 35.1483 8.64914C35.0968 8.94986 35.024 9.24655 34.9305 9.53698C34.8665 9.79811 34.7763 10.0528 34.6954 10.3019C34.5299 10.7992 34.3653 11.2769 34.1921 11.7248C33.8486 12.6253 33.4934 13.4122 33.1684 14.0602C32.5146 15.3552 31.9886 16.0805 31.8626 16.0139C31.7365 15.9474 32.0169 15.0947 32.4814 13.7493C32.7141 13.0747 32.9854 12.291 33.2711 11.3842C33.4154 10.9411 33.5587 10.47 33.7019 9.98306L33.9147 9.24429C33.9856 9.01937 34.0419 8.79009 34.0831 8.5579C34.0926 8.39017 34.0918 8.42557 34.0385 8.36898C33.9064 8.28523 33.7559 8.23475 33.6 8.22186C33.1349 8.16709 32.681 8.11507 32.255 8.06199" fill="black" fill-opacity="0.6" />
                </svg>
              </span>
            </div>
          <?php endif; ?>

          <?php if ($text_right) : ?>
            <div class="o-integrations__header-right" data-animate="fade-up" data-animate-delay="0.4">
              <p class="o-integrations__text-right text__normal">
                <?php echo esc_html($text_right); ?>
              </p>
            </div>
          <?php endif; ?>

          <?php if ($has_button) : ?>
            <div class="o-integrations__footer-right" data-animate="fade-up" data-animate-delay="0.45">
              <a
                class="c-button button button-border__blue"
                href="<?php echo esc_url($button['url']); ?>"
                target="<?php echo esc_attr($button_target); ?>"
                <?php echo ($button_target === '_blank') ? ' rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($button['title']); ?>
              </a>
            </div>
          <?php endif; ?>
        </footer>
      <?php endif; ?>

    </div>
  </div>
</section>