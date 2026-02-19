<?php
global $tpl_engine;

class Main_Menu_Mobile_Walker extends Walker_Nav_Menu
{
	private function arrow_svg(): string
	{
		return "<svg width='10' height='10' viewBox='0 0 10 10' fill='none' xmlns='http://www.w3.org/2000/svg'>
      <path d='M8.0874 3.76556L5.00073 6.85223L1.91406 3.76556'
        stroke='#4353FA' stroke-width='1.66667' stroke-linecap='round' stroke-linejoin='round'/>
    </svg>";
	}

	public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
	{
		$item_id      = (int) $item->ID;
		$item_classes = implode(' ', (array) $item->classes);
		$attr_target  = !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$has_children = str_contains($item_classes, 'menu-item-has-children');

		$title = esc_html($item->title);
		$url   = !empty($item->url) ? esc_url($item->url) : '';

		$toggle_svg = $this->arrow_svg();

		/* ==========================
     * DEPTH 0 (igual desktop)
     * ========================== */
		if ($depth === 0) {

			$output .= "<li class='{$item_classes} c-main-menu__item'>";

			$output .= "<div class='c-main-menu__item-head'>";

			if ($url) {
				$output .= "<a class='c-main-menu__link' onfocus='blur();' {$attr_target} href='{$url}'><span>{$title}</span></a>";
			} else {
				$output .= "<span class='c-main-menu__link'><span>{$title}</span></span>";
			}

			if ($has_children) {
				// mantém a classe do desktop + adiciona o hook do JS mobile
				$output .= "<button class='c-main-menu__toggle js-open-submenu' type='button'
          aria-expanded='false'
          aria-label='" . esc_attr__("Abrir submenu", "textdomain") . "'
          data-parent='" . esc_attr($item->title) . "'>
          {$toggle_svg}
        </button>";
			}

			$output .= "</div>"; // head
			return;
		}

		/* ==========================
     * DEPTH 1 (submenu) - igual desktop
     * ========================== */
		if ($depth === 1) {

			$output .= "<li class='c-main-menu__sub-item {$item_classes}'>";

			// Link padrão do desktop (card no seu desktop)
			$output .= "<a class='sub-menu-item' onfocus='blur();' {$attr_target} href='" . esc_url($item->url) . "'>
        <span>{$title}</span>
      </a>";

			// Se tiver filhos no nível 1, cria toggle também (opcional)
			// (se você NÃO quer sub-sub no mobile, pode remover esse bloco)
			if ($has_children) {
				$output .= "<button class='c-main-menu__toggle js-open-submenu' type='button'
          aria-expanded='false'
          aria-label='" . esc_attr__("Abrir submenu", "textdomain") . "'
          data-parent='" . esc_attr($item->title) . "'>
          {$toggle_svg}
        </button>";
			}

			return;
		}

		/* ==========================
     * DEPTH 2 (sub-sub) - igual desktop
     * ========================== */
		if ($depth === 2) {
			$output .= "<li class='c-main-menu__sub-sub-item {$item_classes}'>
        <a class='sub-sub-menu-item' onfocus='blur();' {$attr_target} href='" . esc_url($item->url) . "'>
          <span>{$title}</span>
        </a>";
			return;
		}
	}

	public function end_el(&$output, $item, $depth = 0, $args = [])
	{
		$output .= "</li>";
	}

	public function start_lvl(&$output, $depth = 0, $args = [])
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		// DEPTH 0 abre o painel / wrapper do submenu (mobile)
		// mas com classes do desktop dentro
		if ($depth === 0) {
			$output .= "\n{$indent}<div class='c-submenu-trigger-wrapper'>
        <div class='sub-menu'>
          <div class='sub-menu-scroll-content'>
            <ul class='c-main-menu__sub-menu c-main-menu__sub-menu--grid'>";
			return;
		}

		// DEPTH 1 (sub-submenu) igual desktop
		if ($depth === 1) {
			$output .= "\n{$indent}<ul class='c-main-menu__sub-sub-menu'>";
			return;
		}
	}

	public function end_lvl(&$output, $depth = 0, $args = [])
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		if ($depth === 0) {
			$output .= "\n{$indent}</ul></div></div></div>";
			return;
		}

		if ($depth === 1) {
			$output .= "\n{$indent}</ul>";
			return;
		}
	}
}
