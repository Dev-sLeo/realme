<?php global $tpl_engine; ?>
<?php

$recursos = $data ?? get_field('recursos');
if (empty($recursos)) return;

$titulo    = $recursos['titulo'] ?? '';
$descricao = $recursos['descricao'] ?? '';
$cards     = $recursos['lista'] ?? [];

if (empty($titulo) && empty($descricao) && empty($cards)) return;
?>

<section class="o-recursos" aria-labelledby="recursos-title">

  <div class="o-recursos__container">
    <header class="o-recursos__header">
      <div class="s-container">
        <?php if (!empty($titulo)) : ?>
          <h2 class="o-recursos__title title__normal" id="recursos-title" data-animate="fade-up" data-animate-delay="0.1"><?= esc_html($titulo); ?></h2>
        <?php endif; ?>

        <?php if (!empty($descricao)) : ?>
          <div class="o-recursos__description text__normal">
            <?= $descricao ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="o-recursos__scroll" aria-hidden="true">
        <?= $tpl_engine->svg('icons/arrow-down-section'); ?>
      </div>
    </header>
    <div class="s-container">


      <?php if (!empty($cards) && is_array($cards)) : ?>
        <div class="o-recursos__grid" role="list" data-animate="fade-up" data-animate-delay="0.2">
          <?php foreach ($cards as $i => $card) : ?>
            <?php
            $img     = $card['imagem'] ?? null;
            $ctitle  = $card['titulo'] ?? '';
            $cdesc   = $card['descricao'] ?? '';
            $bullets = $card['lista'] ?? []; // repeater interno
            $link    = $card['link'] ?? null;

            // repeater interno: [ ['item' => '...'], ... ] => ['...', '...']
            $items = [];
            if (!empty($bullets) && is_array($bullets)) {
              foreach ($bullets as $b) {
                $it = $b['item'] ?? '';
                if (!empty($it)) $items[] = $it;
              }
            }

            if (empty($ctitle) && empty($cdesc) && empty($img) && empty($items) && empty($link)) continue;

            $tpl_engine->partial('components/cards/recurso', array(
              'vars' => array(
                'image'  => $img,
                'title'  => $ctitle,
                'text'   => $cdesc,
                'items'  => $items,
                'button' => $link,
                'theme'  => 'default',
              )
            ));
            ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>