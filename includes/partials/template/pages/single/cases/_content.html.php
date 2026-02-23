<?php
$data = isset($data) && is_array($data) ? $data : get_field('case_content');

if (empty($data)) {
  return;
}

$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$video = $data['video'] ?? '';
$challenge = $data['challenge'] ?? '';
$solution = $data['solution'] ?? '';
$results = $data['results'] ?? [];

$has_header = ($title || $description);
$has_video = !empty($video);
$has_body = ($challenge || $solution);
$has_results = (!empty($results) && is_array($results));
?>

<section class="c-case-content">
  <div class="o-container">

    <?php if ($has_header): ?>
      <header class="c-case-content__header">
        <?php if ($title): ?>
          <h1 class="title__super c-case-content__title">
            <?php echo esc_html($title); ?>
          </h1>
        <?php endif; ?>

        <?php if ($description): ?>
          <p class="text__normal c-case-content__description">
            <?php echo esc_html($description); ?>
          </p>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <?php if ($has_video): ?>
      <?php $embed = wp_oembed_get($video); ?>
      <?php if ($embed): ?>
        <div class="c-case-content__video">
          <div class="c-case-content__video-inner">
            <?php echo $embed; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <div class="c-case-content__grid">

      <div class="c-case-content__main">
        <?php if ($has_body): ?>
          <div class="c-case-content__sections">

            <?php if ($challenge): ?>
              <section class="c-case-content__section c-case-content__section--challenge">
                <h2 class="title__normal c-case-content__section-title">
                  <?php echo esc_html__('O Desafio', 'textdomain'); ?>
                </h2>
                <div class="c-case-content__section-text text__normal">
                  <?php echo wpautop(wp_kses_post($challenge)); ?>
                </div>
              </section>
            <?php endif; ?>

            <?php if ($solution): ?>
              <section class="c-case-content__section c-case-content__section--solution">
                <h2 class="title__normal c-case-content__section-title">
                  <?php echo esc_html__('A Solução', 'textdomain'); ?>
                </h2>
                <div class="c-case-content__section-text text__normal">
                  <?php echo wpautop(wp_kses_post($solution)); ?>
                </div>
              </section>
            <?php endif; ?>

          </div>
        <?php endif; ?>
      </div>

      <?php if ($has_results): ?>
        <aside class="c-case-content__aside"
          aria-label="<?php echo esc_attr__('Resultados em números', 'textdomain'); ?>">
          <h2 class="title__normal c-case-content__aside-title">
            <?php echo esc_html__('Resultados em Números', 'textdomain'); ?>
          </h2>

          <div class="c-case-content__results">
            <?php foreach ($results as $row):
              if (!is_array($row)) {
                continue;
              }
              $number = $row['number'] ?? '';
              $label = $row['label'] ?? '';
              if (!$number && !$label) {
                continue;
              }
              ?>
              <div class="c-case-content__result">
                <div class="c-case-content__result-inner">
                  <?php if ($number): ?>
                    <p class="title__normal c-case-content__result-number">
                      <?php echo esc_html($number); ?>
                    </p>
                  <?php endif; ?>
                  <?php if ($label): ?>
                    <p class="text__normal c-case-content__result-label">
                      <?php echo esc_html($label); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </aside>
      <?php endif; ?>

    </div>

  </div>
</section>