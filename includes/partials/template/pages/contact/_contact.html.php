<?php global $tpl_engine; ?>
<?php
/**
 * Módulo: Contact (Fale com a gente)
 * Grupo ACF: hero
 *
 * Campos:
 * - subtitle (textarea)
 * - title (text)
 * - description (textarea)
 * - desc_icon (text)
 * - lista (repeater) -> item (text)
 * - title_list (text)
 * - desc_form (textarea)  // descrição do form
 * - formulario (CF7 Form)
 *
 * (opcional) title_form (text) // título do form (se existir no ACF)
 */

$data = $data ?? get_field('hero');
$data = is_array($data) ? $data : [];
if (empty($data))
  return;

$subtitle = !empty($data['subtitle']) ? (string) $data['subtitle'] : '';
$title = !empty($data['title']) ? (string) $data['title'] : '';
$description = !empty($data['description']) ? (string) $data['description'] : '';

$desc_icon = !empty($data['desc_icon']) ? (string) $data['desc_icon'] : '';
$title_list = !empty($data['title_list']) ? (string) $data['title_list'] : '';
$desc_form = !empty($data['desc_form']) ? (string) $data['desc_form'] : '';

// (se você tiver/for criar no ACF)
$title_form = !empty($data['title_form']) ? (string) $data['title_form'] : '';

$lista = (!empty($data['lista']) && is_array($data['lista'])) ? $data['lista'] : [];

$formulario = $data['formulario'] ?? null;

// normaliza CF7
$cf7_id = 0;
if (is_numeric($formulario))
  $cf7_id = (int) $formulario;
if (is_array($formulario) && !empty($formulario['id']))
  $cf7_id = (int) $formulario['id'];
if (is_object($formulario) && !empty($formulario->id))
  $cf7_id = (int) $formulario->id;

$cf7_shortcode = '';
if ($cf7_id > 0) {
  $cf7_shortcode = '[contact-form-7 id="' . $cf7_id . '"]';
} elseif (is_string($formulario) && $formulario !== '') {
  $cf7_shortcode = $formulario;
}

$has_left = ($subtitle !== '' || $title !== '' || $description !== '' || $desc_icon !== '' || $title_list !== '' || !empty($lista));
$has_right = ($title_form !== '' || $desc_form !== '' || $cf7_shortcode !== '');

if (!$has_left && !$has_right)
  return;
?>

<section class="o-contact" aria-label="Contato">
  <div class="hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/public/image/patern-contact.webp" alt="">
  </div>
  <div class="s-container">

    <div class="o-contact__wrap">

      <?php if ($has_left): ?>
        <div class="o-contact__content">

          <?php if ($subtitle !== ''): ?>
            <div class="o-contact__subtitle subtitulo" data-animate="fade-up" data-animate-delay="0.1"><?= esc_html($subtitle); ?></div>
          <?php
          endif; ?>

          <?php if ($title !== ''): ?>
            <h2 class="o-contact__title title__super" data-animate="fade-up" data-animate-delay="0.2"><?= esc_html($title); ?></h2>
          <?php
          endif; ?>

          <?php if ($description !== ''): ?>
            <div class="o-contact__description" data-animate="fade-up" data-animate-delay="0.3">
              <?= wpautop(wp_kses_post($description)); ?>
            </div>
          <?php
          endif; ?>
          <?php if ($desc_icon !== ''): ?>
            <div class="o-contact__highlight" data-animate="fade-up" data-animate-delay="0.4">
              <span class="o-contact__highlight-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M9.83311 5.40789L11.0711 4.16992C12.465 2.77598 14.2899 2.19779 16.2213 2.10331C16.9725 2.06656 17.3481 2.04818 17.6493 2.34938C17.9505 2.65057 17.9321 3.02617 17.8954 3.77739C17.8009 5.70873 17.2227 7.53367 15.8288 8.92757L14.5908 10.1656C13.5713 11.1851 13.2814 11.475 13.4954 12.5808C13.7067 13.4256 13.9111 14.2436 13.2969 14.8578C12.5518 15.6029 11.8721 15.6029 11.127 14.8578L5.14085 8.87165C4.39577 8.12655 4.39575 7.4469 5.14085 6.70181C5.75509 6.08756 6.57311 6.29202 7.41784 6.50322C8.5237 6.71726 8.81361 6.42739 9.83311 5.40789Z"
                    stroke="#4353FA" stroke-width="1.25" stroke-linejoin="round" />
                  <path d="M14.1641 5.83331H14.1716" stroke="#4353FA" stroke-width="1.66667" stroke-linecap="round"
                    stroke-linejoin="round" />
                  <path d="M2.08203 17.9167L6.2487 13.75" stroke="#4353FA" stroke-width="1.25" stroke-linecap="round" />
                  <path d="M7.08203 17.9167L8.7487 16.25" stroke="#4353FA" stroke-width="1.25" stroke-linecap="round" />
                  <path d="M2.08203 12.9167L3.7487 11.25" stroke="#4353FA" stroke-width="1.25" stroke-linecap="round" />
                </svg>

              </span>
              <span class="o-contact__highlight-text" data-animate="fade-up" data-animate-delay="0.4"><?= esc_html($desc_icon); ?></span>
            </div>
          <?php
          endif; ?>

          <?php if ($title_list !== '' || !empty($lista)): ?>
            <div class="o-contact__list-block">

              <?php if ($title_list !== ''): ?>
                <h3 class="o-contact__list-title" data-animate="fade-up" data-animate-delay="0.5"><?= esc_html($title_list); ?></h3>
              <?php
              endif; ?>

              <?php if (!empty($lista)): ?>
                <ul class="o-contact__list" role="list">
                  <?php foreach ($lista as $i => $row):
                    if (!is_array($row))
                      continue;
                    $item = !empty($row['item']) ? (string) $row['item'] : '';
                    if ($item === '')
                      continue;
                  ?>
                    <li class="o-contact__list-item" data-animate="fade-up" data-animate-delay="<?php echo 0.6 + ($i * 0.05); ?>">
                      <span class="o-contact__list-icon" aria-hidden="true">
                        <?= $tpl_engine->svg('icons/check'); ?>
                      </span>
                      <span class="o-contact__list-text"><?= esc_html($item); ?></span>
                    </li>
                  <?php
                  endforeach; ?>
                </ul>
              <?php
              endif; ?>

            </div>
          <?php
          endif; ?>

        </div>
      <?php
      endif; ?>

      <?php if ($has_right): ?>
        <div class="o-contact__aside" data-animate="fade-up" data-animate-delay="0.7">
          <div class="o-contact__form-card">

            <?php if ($title_form !== '' || $desc_form !== ''): ?>
              <header class="o-contact__form-head">
                <?php if ($title_form !== ''): ?>
                  <h3 class="o-contact__form-title"><?= esc_html($title_form); ?></h3>
                <?php
                endif; ?>

                <?php if ($desc_form !== ''): ?>
                  <div class="o-contact__form-desc">
                    <?= wpautop(wp_kses_post($desc_form)); ?>
                  </div>
                <?php
                endif; ?>
              </header>
            <?php
            endif; ?>

            <?php if ($cf7_shortcode !== ''): ?>
              <div class="o-contact__form">
                <?= do_shortcode($cf7_shortcode); ?>
              </div>
            <?php
            endif; ?>

          </div>
        </div>
      <?php
      endif; ?>

    </div>

  </div>
</section>