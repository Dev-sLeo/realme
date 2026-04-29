<?php
global $tpl_engine;

class Main_Menu_Mobile_Walker extends Walker_Nav_Menu
{
	private array $top_cfg = [
		'has_controller' => false,
		'enable_mega'    => false,
		'menu_id'        => 0,
		'controller_id'  => 0,
	];

	private function arrow_svg(): string
	{
		return "<svg width='10' height='10' viewBox='0 0 10 10' fill='none' xmlns='http://www.w3.org/2000/svg'>
      <path d='M8.0874 3.76556L5.00073 6.85223L1.91406 3.76556'
        stroke='#4353FA' stroke-width='1.66667' stroke-linecap='round' stroke-linejoin='round'/>
    </svg>";
	}

	private function normalize_menu_id($menu_field): int
	{
		$menu_id = 0;

		if (is_numeric($menu_field)) {
			$menu_id = (int) $menu_field;
		} elseif (is_array($menu_field) && isset($menu_field['term_id'])) {
			$menu_id = (int) $menu_field['term_id'];
		} elseif ($menu_field instanceof WP_Term && isset($menu_field->term_id)) {
			$menu_id = (int) $menu_field->term_id;
		} elseif (is_array($menu_field) && isset($menu_field['ID']) && is_numeric($menu_field['ID'])) {
			$menu_id = (int) $menu_field['ID'];
		}

		return $menu_id > 0 ? $menu_id : 0;
	}

	private function find_controller(array $children): array
	{
		if (!function_exists('get_field') || empty($children)) {
			return ['has_controller' => false, 'enable_mega' => false, 'menu_id' => 0, 'controller_id' => 0];
		}

		foreach ($children as $child) {
			$cid = (int) $child->ID;

			$ativar = (bool) get_field('ativar', $cid);
			if (!$ativar) continue;

			$menu_field = get_field('menu', $cid);
			$menu_id = $this->normalize_menu_id($menu_field);

			if ($menu_id > 0) {
				return [
					'has_controller' => true,
					'enable_mega'    => true,
					'menu_id'        => $menu_id,
					'controller_id'  => $cid,
				];
			}
		}

		return ['has_controller' => false, 'enable_mega' => false, 'menu_id' => 0, 'controller_id' => 0];
	}

	private function get_menu_items(int $menu_id): array
	{
		$items = wp_get_nav_menu_items($menu_id);
		return is_array($items) ? $items : [];
	}

	private function build_tree(array $items): array
	{
		$by_id = [];
		foreach ($items as $it) {
			$it->children = [];
			$by_id[(int) $it->ID] = $it;
		}

		$roots = [];
		foreach ($items as $it) {
			$pid = (int) $it->menu_item_parent;
			if ($pid && isset($by_id[$pid])) {
				$by_id[$pid]->children[] = $it;
			} else {
				$roots[] = $it;
			}
		}

		return ['by_id' => $by_id, 'roots' => $roots];
	}

	private function render_mobile_mega_items(int $menu_id): string
	{
		if ($menu_id <= 0) return '';

		$items = $this->get_menu_items($menu_id);
		$tree  = $this->build_tree($items);
		$roots = $tree['roots'];

		if (empty($roots)) return '';

		$arrow = $this->arrow_svg();
		$out = '';

		foreach ($roots as $root) {
			$root_id = (int) $root->ID;
			$title = esc_html((string) $root->title);
			$url = !empty($root->url) ? esc_url($root->url) : '';
			$has_children = !empty($root->children);

			$out .= "<li class='c-main-menu__sub-item menu-item-{$root_id}'>";
			$out .= $url
				? "<a class='sub-menu-item' onfocus='blur();' href='{$url}'><span>{$title}</span></a>"
				: "<span class='sub-menu-item'><span>{$title}</span></span>";

			if ($has_children) {
				$panel_id = 'mm_' . $menu_id . '_' . $root_id;
				$out .= "<button class='c-main-menu__toggle js-open-submenu' type='button' aria-expanded='false' aria-label='" . esc_attr__('Abrir submenu', 'textdomain') . "' data-subpanel='" . esc_attr($panel_id) . "'>{$arrow}</button>";
				$out .= "<ul class='c-main-menu__sub-sub-menu' data-subpanel-body='" . esc_attr($panel_id) . "' style='display:none;'>";

				foreach ($root->children as $child) {
					$child_id = (int) $child->ID;
					$ctitle = esc_html((string) $child->title);
					$curl = !empty($child->url) ? esc_url($child->url) : '';

					$out .= "<li class='c-main-menu__sub-sub-item menu-item-{$child_id}'>";
					$out .= $curl
						? "<a class='sub-sub-menu-item' onfocus='blur();' href='{$curl}'><span>{$ctitle}</span></a>"
						: "<span class='sub-sub-menu-item'><span>{$ctitle}</span></span>";
					$out .= "</li>";
				}

				$out .= "</ul>";
			}

			$out .= "</li>";
		}

		return $out;
	}

	public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args = [], &$output = '')
	{
		if (!$element) return;

		$id_field = $this->db_fields['id'];
		$element_id = $element->$id_field;

		$args[0]->has_children = (isset($children_elements[$element_id]) && is_array($children_elements[$element_id]));

		if ($depth === 0 && !empty($children_elements[$element_id])) {
			$this->top_cfg = $this->find_controller($children_elements[$element_id]);
			$element->controller_cfg = $this->top_cfg;
		}

		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
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

		if ($depth === 0) {
			$this->top_cfg = (isset($item->controller_cfg) && is_array($item->controller_cfg))
				? $item->controller_cfg
				: ['has_controller' => false, 'enable_mega' => false, 'menu_id' => 0, 'controller_id' => 0];

			$has_mega = !empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id']);
			$should_toggle = $has_children || $has_mega;

			$output .= "<li class='{$item_classes} c-main-menu__item'>";
			$output .= "<div class='c-main-menu__item-head'>";

			if ($url) {
				$output .= "<a class='c-main-menu__link' onfocus='blur();' {$attr_target} href='{$url}'><span>{$title}</span></a>";
			} else {
				$output .= "<span class='c-main-menu__link'><span>{$title}</span></span>";
			}

			if ($should_toggle) {
				$output .= "<button class='c-main-menu__toggle js-open-submenu' type='button' aria-expanded='false' aria-label='" . esc_attr__('Abrir submenu', 'textdomain') . "' data-parent='" . esc_attr($item->title) . "'>{$toggle_svg}</button>";
			}

			$output .= "</div>";
			return;
		}

		if (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id'])) {
			return;
		}

		if ($depth === 1) {
			$output .= "<li class='c-main-menu__sub-item {$item_classes}'>";
			$output .= "<a class='sub-menu-item' onfocus='blur();' {$attr_target} href='" . esc_url($item->url) . "'><span>{$title}</span></a>";

			if ($has_children) {
				$output .= "<button class='c-main-menu__toggle js-open-submenu' type='button' aria-expanded='false' aria-label='" . esc_attr__('Abrir submenu', 'textdomain') . "' data-parent='" . esc_attr($item->title) . "'>{$toggle_svg}</button>";
			}

			return;
		}

		if ($depth === 2) {
			$output .= "<li class='c-main-menu__sub-sub-item {$item_classes}'>";
			$output .= "<a class='sub-menu-item' onfocus='blur();' {$attr_target} href='" . esc_url($item->url) . "'>";
			$output .= "<span>{$title}</span>";
			$output .= "</a>";
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

		if ($depth === 0) {
			if (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id'])) {
				$items_html = $this->render_mobile_mega_items((int) $this->top_cfg['menu_id']);

				$output .= "\n{$indent}<div class='c-submenu-trigger-wrapper'>
        <div class='sub-menu'>
          <div class='sub-menu-scroll-content'>
            <ul class='c-main-menu__sub-menu c-main-menu__sub-menu--grid'>{$items_html}</ul>
          </div>
        </div>
      </div>";

				return;
			}

			$output .= "\n{$indent}<div class='c-submenu-trigger-wrapper'>
        <div class='sub-menu'>
          <div class='sub-menu-scroll-content'>
            <ul class='c-main-menu__sub-menu c-main-menu__sub-menu--grid'>";
			return;
		}

		if ($depth === 1) {
			$output .= "\n{$indent}<ul class='c-main-menu__sub-sub-menu'>";
			return;
		}
	}

	public function end_lvl(&$output, $depth = 0, $args = [])
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		if ($depth === 0) {
			if (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id'])) {
				return;
			}

			$output .= "\n{$indent}</ul></div></div></div>";
			return;
		}

		if ($depth === 1) {
			$output .= "\n{$indent}</ul>";
			return;
		}
	}
}
