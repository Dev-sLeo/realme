<?php
$data = $data ?? get_field('section_unifique_atendimento');
$data = is_array($data) ? $data : [];

if (empty($data)) {
  return;
}

$subtitle    = isset($data['subtitulo']) ? (string) $data['subtitulo'] : '';
$title       = isset($data['title']) ? (string) $data['title'] : '';
$text        = isset($data['text']) ? (string) $data['text'] : '';
$button      = (!empty($data['button']) && is_array($data['button'])) ? $data['button'] : null;
$image       = (!empty($data['image']) && is_array($data['image'])) ? $data['image'] : null;

$title_cards = isset($data['title_cards']) ? (string) $data['title_cards'] : '';
$cards       = (!empty($data['cards']) && is_array($data['cards'])) ? $data['cards'] : [];

$has_top = ($subtitle !== '' || $title !== '' || $text !== '' || !empty($button) || !empty($image));
$has_bottom = ($title_cards !== '' || !empty($cards));

if (!$has_top && !$has_bottom) {
  return;
}

$img_id  = (!empty($image['ID'])) ? (int) $image['ID'] : 0;
$img_alt = (!empty($image['alt'])) ? (string) $image['alt'] : '';

$btn_has = (!empty($button['url']));
$btn_url = $btn_has ? (string) $button['url'] : '';
$btn_lbl = ($btn_has && !empty($button['title'])) ? (string) $button['title'] : '';
$btn_tgt = ($btn_has && !empty($button['target'])) ? (string) $button['target'] : '_self';
$btn_rel = ($btn_has && $btn_tgt === '_blank') ? 'noopener noreferrer' : '';
?>

<section class="o-unifique" aria-label="Unifique o atendimento">
  <div class="s-container">

    <?php if ($has_top) : ?>
      <div class="o-unifique__top">
        <div class="o-unifique__content">

          <?php if ($subtitle !== '') : ?>
            <div class="o-unifique__subtitle subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?php echo $subtitle; ?></div>
          <?php endif; ?>

          <?php if ($title !== '') : ?>
            <h2 class="o-unifique__title title__normal"><?php echo $title; ?></h2>
          <?php endif; ?>

          <?php if ($text !== '') : ?>
            <div class="o-unifique__text text__normal" data-animate="fade-up" data-animate-delay="0.2"><?php echo $text; ?></div>
          <?php endif; ?>

          <?php if ($btn_has) : ?>
            <div class="o-unifique__actions" data-animate="fade-up" data-animate-delay="0.25">
              <a class="button button__blue"
                href="<?php echo $btn_url; ?>"
                target="<?php echo $btn_tgt; ?>" <?php echo $btn_rel ? ' rel="' . $btn_rel . '"' : ''; ?>>
                <?php echo $btn_lbl; ?>
              </a>
            </div>
          <?php endif; ?>

        </div>

        <?php if ($img_id) : ?>
          <div class="o-unifique__media" data-animate="fade-up" data-animate-delay="0.3">
            <figure class="o-unifique__figure">
              <?php
              echo wp_get_attachment_image(
                $img_id,
                'large',
                false,
                [
                  'class'    => 'o-unifique__image',
                  'alt'      => $img_alt,
                  'loading'  => 'lazy',
                  'decoding' => 'async',
                ]
              );
              ?>
            </figure>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($has_bottom) : ?>
      <div class="o-unifique__bottom">

        <?php if ($title_cards !== '') : ?>
          <h3 class="o-unifique__cards-title"><?php echo $title_cards; ?></h3>
        <?php endif; ?>

        <?php if (!empty($cards)) : ?>
          <div class="o-unifique__cards" role="list">
            <?php foreach ($cards as $i => $card) :
              if (!is_array($card)) continue;

              $icon = (!empty($card['icon']) && is_array($card['icon'])) ? $card['icon'] : null;
              $ct   = isset($card['title']) ? (string) $card['title'] : '';

              $icon_id  = (!empty($icon['ID'])) ? (int) $icon['ID'] : 0;
              $icon_alt = (!empty($icon['alt'])) ? (string) $icon['alt'] : '';
            ?>
              <div class="c-feature o-unifique__card" role="listitem" data-animate="fade-up" data-animate-delay="<?php echo 0.2 + ($i * 0.05); ?>">
                <?php if ($icon_id) : ?>
                  <div class="c-feature__icon">
                    <?php
                    echo wp_get_attachment_image(
                      $icon_id,
                      'thumbnail',
                      false,
                      [
                        'class'    => 'c-feature__icon-img',
                        'alt'      => $icon_alt,
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                      ]
                    );
                    ?>
                  </div>
                <?php endif; ?>

                <?php if ($ct !== '') : ?>
                  <div class="c-feature__title"><?php echo $ct; ?></div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    <?php endif; ?>

  </div>
</section>