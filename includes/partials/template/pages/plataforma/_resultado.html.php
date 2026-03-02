<?php global $tpl_engine; ?>
<?php

$data = $data ?? get_field('resultados');
$data = is_array($data) ? $data : [];

if (empty($data))
  return;

$sub_titulo = isset($data['sub_titulo']) ? (string) $data['sub_titulo'] : '';
$titulo = isset($data['titulo']) ? (string) $data['titulo'] : '';
$texto = isset($data['texto']) ? (string) $data['texto'] : '';

$button = (!empty($data['button']) && is_array($data['button'])) ? $data['button'] : null;
$imagem = (!empty($data['imagem']) && is_array($data['imagem'])) ? $data['imagem'] : null;

$title_cards = isset($data['title_cards']) ? (string) $data['title_cards'] : '';
$description_cards = isset($data['description_cards']) ? (string) $data['description_cards'] : '';

$lista = (!empty($data['lista']) && is_array($data['lista'])) ? $data['lista'] : [];
$cards = (!empty($data['cards']) && is_array($data['cards'])) ? $data['cards'] : [];

$btn_has = (!empty($button['url']) && !empty($button['title']));
$btn_url = $btn_has ? (string) $button['url'] : '';
$btn_lbl = $btn_has ? (string) $button['title'] : '';
$btn_tgt = ($btn_has && !empty($button['target'])) ? (string) $button['target'] : '_self';
$btn_rel = ($btn_has && $btn_tgt === '_blank') ? 'noopener noreferrer' : '';

$img_id = (!empty($imagem['ID'])) ? (int) $imagem['ID'] : 0;
$img_alt = (!empty($imagem['alt'])) ? (string) $imagem['alt'] : '';

$has_top_left = ($sub_titulo !== '' || $titulo !== '' || $texto !== '' || !empty($lista) || $btn_has);
$has_top_right = (bool) $img_id;
$has_top = ($has_top_left || $has_top_right);

$has_bottom = ($title_cards !== '' || $description_cards !== '' || !empty($cards));

if (!$has_top && !$has_bottom)
  return;
?>

<section class="o-unifique o-unifique--resultados" aria-label="Resultados">
  <div class="s-container">

    <?php if ($has_top): ?>
      <div class="o-unifique__top">
        <div class="o-unifique__content">

          <?php if ($sub_titulo !== ''): ?>
            <div class="o-unifique__subtitle subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?= esc_html($sub_titulo); ?></div>
          <?php endif; ?>

          <?php if ($titulo !== ''): ?>
            <h2 class="o-unifique__title title__normal" data-animate="fade-up" data-animate-delay="0.2"><?= esc_html($titulo); ?></h2>
          <?php endif; ?>

          <?php if ($texto !== ''): ?>
            <div class="o-unifique__text text__normal" data-animate="fade-up" data-animate-delay="0.3">
              <?= wpautop(wp_kses_post($texto)); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($lista)): ?>
            <ul class="o-unifique__list" role="list" data-animate="fade-up" data-animate-delay="0.4">
              <?php foreach ($lista as $row):
                if (!is_array($row))
                  continue;

                $pct = isset($row['porcentagem']) ? $row['porcentagem'] : '';
                $ltxt = isset($row['texto']) ? (string) $row['texto'] : '';

                // ✅ NOVO: icone do item (pode vir como array ou ID)
                $licone = $row['icone'] ?? null;
                $licone_id = 0;
                $licone_alt = '';

                if (is_array($licone)) {
                  $licone_id = (int) ($licone['ID'] ?? 0);
                  $licone_alt = (string) ($licone['alt'] ?? ($licone['title'] ?? ''));
                } else {
                  $licone_id = (int) $licone;
                }

                if ($pct === 0 && $ltxt === '')
                  continue;
              ?>
                <li class="o-unifique__list-item">
                  <?php if ($pct): ?>
                    <strong class="o-unifique__list-pct"><?= esc_html($pct); ?></strong>
                  <?php endif; ?>

                  <span class="o-unifique__list-icon" aria-hidden="true">
                    <?php if ($licone_id): ?>
                      <?php
                      echo wp_get_attachment_image(
                        $licone_id,
                        'thumbnail',
                        false,
                        [
                          'loading' => 'lazy',
                          'decoding' => 'async',
                          'alt' => esc_attr($licone_alt),
                        ]
                      );
                      ?>
                    <?php else: ?>
                      <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.832031 0.833252L6.83203 6.83325L0.832031 12.8333" stroke="#4353FA" stroke-width="1.66667"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    <?php endif; ?>
                  </span>

                  <?php if ($ltxt !== ''): ?>
                    <span class="o-unifique__list-text"><?= esc_html($ltxt); ?></span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if ($btn_has): ?>
            <div class="o-unifique__actions" data-animate="fade-up" data-animate-delay="0.5">
              <a class="button button__blue" href="<?= esc_url($btn_url); ?>" target="<?= esc_attr($btn_tgt); ?>"
                <?= $btn_rel ? 'rel="' . esc_attr($btn_rel) . '"' : ''; ?>>
                <?= esc_html($btn_lbl); ?>
              </a>
            </div>
          <?php endif; ?>

        </div>

        <?php if ($img_id): ?>
          <div class="o-unifique__media" data-animate="fade-up" data-animate-delay="0.6">
            <figure class="o-unifique__figure">
              <?php
              echo wp_get_attachment_image(
                $img_id,
                'large',
                false,
                [
                  'class' => 'o-unifique__image',
                  'alt' => $img_alt,
                  'loading' => 'lazy',
                  'decoding' => 'async',
                ]
              );
              ?>
            </figure>
          </div>
        <?php endif; ?>

      </div>
    <?php endif; ?>

    <?php if ($has_bottom): ?>
      <div class="o-unifique__bottom" data-animate="fade-up" data-animate-delay="0.7">

        <?php if ($title_cards !== '' || $description_cards !== ''): ?>
          <div class="o-unifique__bottom-head">
            <?php if ($title_cards !== ''): ?>
              <h3 class="o-unifique__cards-title"><?= esc_html($title_cards); ?></h3>
            <?php endif; ?>

            <?php if ($description_cards !== ''): ?>
              <div class="o-unifique__cards-description">
                <?= wpautop(wp_kses_post($description_cards)); ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($cards)): ?>
          <div class="o-unifique__cards o-unifique__cards--steps" role="list">
            <?php foreach ($cards as $i => $card):
              if (!is_array($card))
                continue;

              $num = isset($card['numero']) ? (int) $card['numero'] : 0;
              $ct = isset($card['titulo']) ? (string) $card['titulo'] : '';
              $cd = isset($card['description']) ? (string) $card['description'] : '';

              if ($num === 0 && $ct === '' && $cd === '')
                continue;

              $num_lbl = $num ? str_pad((string) $num, 2, '0', STR_PAD_LEFT) : '';
            ?>
              <article class="c-step o-unifique__card" role="listitem" data-animate="fade-up" data-animate-delay="<?php echo 0.8 + ($i * 0.05); ?>">
                <div class="c-step__top">
                  <?php if ($num_lbl !== ''): ?>
                    <div class="c-step__number"><?= esc_html($num_lbl); ?></div>
                  <?php endif; ?>

                  <span class="c-step__arrow" aria-hidden="true"><svg width="12" height="21" viewBox="0 0 12 21" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path d="M1 20L10.5 10.5L1 1" stroke="#85C3D6" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </span>
                </div>

                <?php if ($ct !== ''): ?>
                  <h4 class="c-step__title"><?= esc_html($ct); ?></h4>
                <?php endif; ?>

                <?php if ($cd !== ''): ?>
                  <div class="c-step__text">
                    <?= wpautop(wp_kses_post($cd)); ?>
                  </div>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    <?php endif; ?>

  </div>
</section>