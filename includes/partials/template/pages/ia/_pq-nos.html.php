<?php global $tpl_engine; ?>
<?php
/**
 * Módulo: Por que nós (menu/tabs com imagem)
 * Grupo ACF: por_que_nos
 *
 * Campos:
 * - sub_titulo (text)
 * - titulo (text)
 * - menus (repeater) -> titulo (text), imagem (image)
 */

$data = $data ?? get_field('por_que_nos');
$data = is_array($data) ? $data : [];
if (empty($data))
  return;

$sub_titulo = !empty($data['sub_titulo']) ? (string) $data['sub_titulo'] : '';
$titulo = !empty($data['titulo']) ? (string) $data['titulo'] : '';
$menus = (!empty($data['menus']) && is_array($data['menus'])) ? $data['menus'] : [];

$has_header = ($sub_titulo !== '' || $titulo !== '');
$has_menus = (!empty($menus));

if (!$has_header && !$has_menus)
  return;

// filtra menus válidos (precisa ao menos título ou imagem)
$tabs = [];
foreach ($menus as $m) {
  if (!is_array($m))
    continue;

  $mt = !empty($m['titulo']) ? (string) $m['titulo'] : '';
  $img = (!empty($m['imagem']) && is_array($m['imagem'])) ? $m['imagem'] : null;

  $img_id = (!empty($img['ID'])) ? (int) $img['ID'] : 0;
  $img_alt = (!empty($img['alt'])) ? (string) $img['alt'] : '';

  if ($mt === '' && !$img_id)
    continue;

  $tabs[] = [
    'title' => $mt,
    'img_id' => $img_id,
    'img_alt' => $img_alt,
  ];
}

if (empty($tabs))
  return;

// id base único (evita conflito se tiver mais de 1 módulo na página)
$uid = 'pq-' . wp_generate_uuid4();
?>

<section class="o-por-que-nos" aria-label="Por que nós">
  <div class="s-container">

    <?php if ($has_header): ?>
      <header class="o-por-que-nos__header">
        <?php if ($sub_titulo !== ''): ?>
          <div class="o-por-que-nos__subtitle subtitulo" data-animate="fade-up" data-animate-delay="0.1">
            <?= esc_html($sub_titulo); ?>
          </div>
        <?php endif; ?>


      </header>
    <?php endif; ?>

    <div class="o-por-que-nos__wrap" data-tabs-root="<?= esc_attr($uid); ?>">
      <div class="o-por-que-nos__wrap-header">
        <?php if ($titulo !== ''): ?>
          <h2 class="o-por-que-nos__title title__normal" data-animate="fade-up" data-animate-delay="0.1">
            <?= esc_html($titulo); ?>
          </h2>
        <?php endif; ?>

        <div class="o-por-que-nos__tabs" role="tablist" aria-label="Opções">
          <?php foreach ($tabs as $i => $tab):
            $is_active = ($i === 0);
            $tab_id = $uid . '-tab-' . $i;
            $panel_id = $uid . '-panel-' . $i;
            $label = ($tab['title'] !== '') ? $tab['title'] : ('Opção ' . ($i + 1));
          ?>
            <button class="o-por-que-nos__tab<?= $is_active ? ' is-active' : ''; ?>" type="button" role="tab"
              id="<?= esc_attr($tab_id); ?>" aria-controls="<?= esc_attr($panel_id); ?>"
              aria-selected="<?= $is_active ? 'true' : 'false'; ?>" tabindex="<?= $is_active ? '0' : '-1'; ?>"
              data-tab="<?= esc_attr($i); ?>" data-animate="fade-up" data-animate-delay="<?php echo 0.2 + ($i * 0.05); ?>">
              <?= esc_html($label); ?>
            </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="o-por-que-nos__panels" role="presentation" data-animate="fade-up" data-animate-delay="0.25">
        <?php foreach ($tabs as $i => $tab):
          $is_active = ($i === 0);
          $tab_id = $uid . '-tab-' . $i;
          $panel_id = $uid . '-panel-' . $i;
        ?>
          <div class="o-por-que-nos__panel<?= $is_active ? ' is-active' : ''; ?>" role="tabpanel"
            id="<?= esc_attr($panel_id); ?>" aria-labelledby="<?= esc_attr($tab_id); ?>" <?= $is_active ? '' : 'hidden'; ?>
            data-panel="
          <?= esc_attr($i); ?>">
            <?php if (!empty($tab['img_id'])): ?>
              <div class="o-por-que-nos__media">
                <?= wp_get_attachment_image(
                  $tab['img_id'],
                  'full',
                  false,
                  [
                    'class' => 'o-por-que-nos__image',
                    'alt' => $tab['img_alt'],
                    'loading' => 'lazy',
                    'decoding' => 'async',
                  ]
                ); ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

    </div>

  </div>
</section>

<script>
  (function() {
    var root = document.querySelector('[data-tabs-root="<?= esc_js($uid); ?>"]');
    if (!root) return;

    var tabs = root.querySelectorAll('[role="tab"]');
    var panels = root.querySelectorAll('[role="tabpanel"]');

    function activate(index, focusTab) {
      tabs.forEach(function(t, i) {
        var active = i === index;
        t.classList.toggle('is-active', active);
        t.setAttribute('aria-selected', active ? 'true' : 'false');
        t.setAttribute('tabindex', active ? '0' : '-1');
        if (focusTab && active) t.focus();
      });

      panels.forEach(function(p, i) {
        var active = i === index;
        p.classList.toggle('is-active', active);
        if (active) p.removeAttribute('hidden');
        else p.setAttribute('hidden', '');
      });
    }

    tabs.forEach(function(tab) {
      tab.addEventListener('click', function() {
        var idx = parseInt(tab.getAttribute('data-tab') || '0', 10);
        activate(idx, false);
      });

      tab.addEventListener('keydown', function(e) {
        var current = Array.prototype.indexOf.call(tabs, tab);
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
          e.preventDefault();
          activate((current + 1) % tabs.length, true);
        }
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
          e.preventDefault();
          activate((current - 1 + tabs.length) % tabs.length, true);
        }
        if (e.key === 'Home') {
          e.preventDefault();
          activate(0, true);
        }
        if (e.key === 'End') {
          e.preventDefault();
          activate(tabs.length - 1, true);
        }
      });
    });
  })();
</script>