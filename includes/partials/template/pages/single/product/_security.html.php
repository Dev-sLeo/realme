<?php
$data = isset($data) && is_array($data) ? $data : get_field('security');
$data = is_array($data) ? $data : [];

if (empty($data)) {
  return;
}

$eyebrow     = isset($data['eyebrown']) ? (string) $data['eyebrown'] : '';
$title       = isset($data['title']) ? (string) $data['title'] : '';
$description = isset($data['description']) ? (string) $data['description'] : '';
$bullets     = (!empty($data['bullets']) && is_array($data['bullets'])) ? $data['bullets'] : [];
$mockup      = (!empty($data['mockup']) && is_array($data['mockup'])) ? $data['mockup'] : null;

$mockup_id  = (!empty($mockup['ID'])) ? (int) $mockup['ID'] : 0;
$mockup_alt = (!empty($mockup['alt'])) ? (string) $mockup['alt'] : '';

$has_left  = ($eyebrow !== '' || $title !== '' || $description !== '' || !empty($bullets));
$has_right = (bool) $mockup_id;

if (!$has_left && !$has_right) {
  return;
}
?>

<section class="o-product-security" aria-label="Segurança e compliance">
  <div class="s-container">

    <div class="o-product-security__top">
      <?php if ($has_left) : ?>
        <div class="o-product-security__content">

          <header class="o-product-security__header">
            <?php if ($eyebrow !== '') : ?>
              <span class="o-product-security__eyebrow subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo esc_html($eyebrow); ?></span>
            <?php endif; ?>

            <?php if ($title !== '') : ?>
              <h2 class="o-product-security__title title__normal " data-animate="fade-up" data-animate-delay="0.2"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($description !== '') : ?>
              <div class="o-product-security__desc text__normal" data-animate="fade-up" data-animate-delay="0.3"><?php echo wpautop(wp_kses_post($description)); ?></div>
            <?php endif; ?>
          </header>

          <?php if (!empty($bullets)) : ?>
            <div class="o-product-security__bullets" role="list">
              <?php foreach ($bullets as $i => $bullet) :
                if (!is_array($bullet)) continue;

                $b_icon = (!empty($bullet['icon']) && is_array($bullet['icon'])) ? $bullet['icon'] : null;
                $b_title = isset($bullet['title']) ? (string) $bullet['title'] : '';
                $b_text  = isset($bullet['text']) ? (string) $bullet['text'] : '';

                if ($b_title === '' && $b_text === '' && empty($b_icon)) continue;

                $b_icon_id  = (!empty($b_icon['ID'])) ? (int) $b_icon['ID'] : 0;
                $b_icon_alt = (!empty($b_icon['alt'])) ? (string) $b_icon['alt'] : '';
              ?>
                <div class="c-feature o-product-security__bullet" role="listitem" data-animate="fade-up" data-animate-delay="<?php echo 0.4 + ($i * 0.1); ?>s">
                  <div class="c-feature__header">
                    <?php if ($b_icon_id) : ?>
                      <div class="c-feature__icon o-product-security__bullet-icon">
                        <?php
                        echo wp_get_attachment_image(
                          $b_icon_id,
                          'thumbnail',
                          false,
                          [
                            'class'    => 'c-feature__icon-img',
                            'alt'      => $b_icon_alt,
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                          ]
                        );
                        ?>
                      </div>
                    <?php endif; ?>
                    <?php if ($b_title !== '') : ?>
                      <div class="c-feature__title o-product-security__bullet-title"><?php echo esc_html($b_title); ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="c-feature__body o-product-security__bullet-body">


                    <?php if ($b_text !== '') : ?>
                      <div class="c-feature__text o-product-security__bullet-text"><?php echo esc_html($b_text); ?></div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </div>
      <?php endif; ?>

      <?php if ($has_right) : ?>
        <div class="o-product-security__media" data-animate="fade-up" data-animate-delay="0.4">
          <figure class="o-product-security__figure">
            <?php
            echo wp_get_attachment_image(
              $mockup_id,
              'large',
              false,
              [
                'class'    => 'o-product-security__mockup',
                'alt'      => $mockup_alt,
                'loading'  => 'lazy',
                'decoding' => 'async',
              ]
            );
            ?>
          </figure>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>