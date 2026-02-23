<?php
$data = isset($data) && is_array($data) ? $data : get_field('faq');
if (empty($data) || !is_array($data)) {
  return;
}
?>
<section class="o-product-faq">
  <div class="s-container">
    <header class="o-product-faq__header">
      <?php if (!empty($data['title'])) : ?><h2 class="title__normal"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
      <?php if (!empty($data['description'])) : ?><div class="text__normal"><?php echo wpautop(wp_kses_post($data['description'])); ?></div><?php endif; ?>
    </header>
    <?php if (!empty($data['items']) && is_array($data['items'])) : ?>
      <div class="o-product-faq__list">
        <?php foreach ($data['items'] as $item) :
          $question = $item['question'] ?? '';
          $answer = $item['answer'] ?? '';
          if (empty($question) && empty($answer)) {
            continue;
          }
        ?>
          <details class="o-product-faq__item">
            <summary><?php echo esc_html($question); ?></summary>
            <div><?php echo wpautop(wp_kses_post($answer)); ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
