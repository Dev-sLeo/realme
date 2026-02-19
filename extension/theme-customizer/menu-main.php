<?php

class Main_Menu_Walker extends Walker_Nav_Menu
{
	private array $top_cfg = [
		'has_controller' => false,
		'enable_mega'    => false,
		'menu_id'        => 0,
		'controller_id'  => 0,
	];

	private array $opened_li_depth = [];

	private function toggle_svg(): string
	{
		return '<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M8.0874 3.76556L5.00073 6.85223L1.91406 3.76556" stroke="#4353FA" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>';
	}

	private function arrow_svg(): string
	{
		return '<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M8.0874 3.76556L5.00073 6.85223L1.91406 3.76556" stroke="#4353FA" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>';
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

	private function get_menu_term_field(string $field, int $menu_id)
	{
		if (!function_exists('get_field')) return null;

		$val = get_field($field, 'term_' . $menu_id);

		if ($val === null || $val === false || $val === '') {
			$val = get_field($field, $menu_id);
		}

		return $val;
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

	private function get_icon_html(int $menu_item_id): string
	{
		if (!function_exists('get_field')) return '';

		$enable = (bool) get_field('ativar_icone', $menu_item_id);
		if (!$enable) return '';

		$icon = get_field('icone', $menu_item_id);

		if (is_array($icon) && !empty($icon['ID'])) {
			return wp_get_attachment_image((int) $icon['ID'], 'thumbnail', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		}

		if (is_numeric($icon)) {
			return wp_get_attachment_image((int) $icon, 'thumbnail', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		}

		return '';
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

	private function render_link_button($link, string $class): string
	{
		if (empty($link) || !is_array($link) || empty($link['url'])) return '';

		$url    = esc_url($link['url']);
		$title  = $link['title'] ?? '';
		$target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';

		return "<a class='{$class}' href='{$url}'{$target}><span>" . esc_html($title) . "</span></a>";
	}

	private function render_mega_banner(array $mega_banner): string
	{
		$img  = $mega_banner['imagem'] ?? null;
		$t    = $mega_banner['titulo'] ?? '';
		$d    = $mega_banner['descricao'] ?? '';
		$btn1 = $mega_banner['button'] ?? null;
		$btn2 = $mega_banner['button_2'] ?? null;

		$img_html = '';
		if (is_array($img) && !empty($img['ID'])) {
			$img_html = wp_get_attachment_image((int) $img['ID'], 'large', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		} elseif (is_numeric($img)) {
			$img_html = wp_get_attachment_image((int) $img, 'large', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		}

		$out  = "<div class='c-mega__banner'>";
		$out .= $img_html ? "<div class='c-mega__banner-media'>{$img_html}</div>" : "";
		$out .= "<div class='c-mega__banner-body'>";
		$out .= $t ? "<div class='c-mega__banner-title'>{$t}</div>" : "";
		$out .= $d ? "<div class='c-mega__banner-desc'>{$d}</div>" : "";

		$buttons = '';
		$buttons .= $this->render_link_button($btn1, 'c-mega__banner-btn c-mega__banner-btn--primary');
		$buttons .= $this->render_link_button($btn2, 'c-mega__banner-btn c-mega__banner-btn--secondary');

		if ($buttons) {
			$out .= "<div class='c-mega__banner-actions'>{$buttons}</div>";
		}

		$out .= "</div></div>";
		return $out;
	}

	private function render_banner_case(array $banner_case): string
	{
		$bg    = $banner_case['background'] ?? null;
		$logo  = $banner_case['logo'] ?? null;
		$t     = $banner_case['titulo'] ?? '';
		$d     = $banner_case['descricao'] ?? '';
		$estat = is_array($banner_case['estatistica'] ?? null) ? $banner_case['estatistica'] : [];
		$btn   = $banner_case['button'] ?? null;

		$bg_html = '';
		if (is_array($bg) && !empty($bg['ID'])) {
			$bg_html = wp_get_attachment_image((int) $bg['ID'], 'large', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		} elseif (is_numeric($bg)) {
			$bg_html = wp_get_attachment_image((int) $bg, 'large', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		}

		$logo_html = '';
		if (is_array($logo) && !empty($logo['ID'])) {
			$logo_html = wp_get_attachment_image((int) $logo['ID'], 'thumbnail', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		} elseif (is_numeric($logo)) {
			$logo_html = wp_get_attachment_image((int) $logo, 'thumbnail', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		}

		$estat_icon = '';
		if (!empty($estat['icone']) && is_array($estat['icone']) && !empty($estat['icone']['ID'])) {
			$estat_icon = wp_get_attachment_image((int) $estat['icone']['ID'], 'thumbnail', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		} elseif (!empty($estat['icone']) && is_numeric($estat['icone'])) {
			$estat_icon = wp_get_attachment_image((int) $estat['icone'], 'thumbnail', false, [
				'loading'  => 'lazy',
				'decoding' => 'async',
			]);
		}

		$estat_num  = $estat['numero'] ?? '';
		$estat_desc = $estat['descricao'] ?? '';

		$out  = "<div class='c-mega__case'>";

		if ($bg_html) {
			$out .= "<div class='c-mega__case-bg'>{$bg_html}";
			$out .= $logo_html ? "<div class='c-mega__case-logo'>{$logo_html}</div>" : "";
			$out .= "</div>";
		}

		$out .= "<div class='c-mega__case-body'>";
		$out .= $t ? "<div class='c-mega__case-title'>{$t}</div>" : "";
		$out .= $d ? "<div class='c-mega__case-desc'>{$d}</div>" : "";

		if ($estat_num || $estat_desc || $estat_icon) {
			$out .= "<div class='c-mega__case-stat'>";
			$out .= "<div class='c-mega__case-stat-top'>";
			$out .= $estat_icon ? "<span class='c-mega__case-stat-icon'>{$estat_icon}</span>" : "";
			$out .= $estat_num  ? "<span class='c-mega__case-stat-num'>{$estat_num}</span>" : "";
			$out .= "</div>";
			$out .= $estat_desc ? "<span class='c-mega__case-stat-desc'>{$estat_desc}</span>" : "";
			$out .= "</div>";
		}

		$btn_html = $this->render_link_button($btn, 'c-mega__case-btn');
		if ($btn_html) {
			$out .= "<div class='c-mega__case-actions'>{$btn_html}</div>";
		}

		$out .= "</div></div>";
		return $out;
	}

	private function render_type_banner(int $menu_id, array $cfg): string
	{
		$items = $this->get_menu_items($menu_id);
		$tree  = $this->build_tree($items);
		$roots = $tree['roots'];

		$out  = "<div class='c-main-menu__dropdown c-main-menu__dropdown--banner'>";
		$out .= "<div class='c-mega c-mega--banner'>";

		$out .= "<div class='c-mega__left'>" . $this->render_mega_banner((array) $cfg['mega_banner']) . "</div>";

		$out .= "<div class='c-mega__center'><ul class='c-mega__list'>";
		foreach ($roots as $it) {
			$it_id = (int) $it->ID;
			$icon  = $this->get_icon_html($it_id);

			$out .= "<li class='c-mega__list-item'>";
			$out .= "<a class='c-mega__list-link' onfocus='blur();' href='" . esc_url($it->url) . "'>";
			$out .= $icon ? "<span class='c-mega__list-icon'>{$icon}</span>" : "";
			$out .= "<span class='c-mega__list-text'>" . esc_html($it->title) . "</span>";
			$out .= "</a></li>";
		}
		$out .= "</ul></div>";

		$out .= "<div class='c-mega__right'>" . $this->render_banner_case((array) $cfg['banner_case']) . "</div>";

		$out .= "</div></div>";
		return $out;
	}

	private function render_type_list(int $menu_id): string
	{
		$items = $this->get_menu_items($menu_id);
		$tree  = $this->build_tree($items);
		$roots = $tree['roots'];

		$out  = "<div class='c-main-menu__dropdown c-main-menu__dropdown--mega'>";
		$out .= "<div class='c-mega c-mega--list'>";
		$out .= "<div class='c-mega__list-groups'>";

		foreach ($roots as $root) {
			$is_title = is_array($root->classes) && in_array('title-list', $root->classes, true);

			if ($is_title) {
				$out .= "<div class='c-mega__group'>";
				$out .= "<div class='c-mega__group-title'>" . esc_html($root->title) . "</div>";

				if (!empty($root->children)) {
					$out .= "<ul class='c-mega__group-items'>";
					foreach ($root->children as $child) {
						$out .= "<li class='c-mega__group-item'>";
						$out .= "<a class='c-mega__group-link' onfocus='blur();' href='" . esc_url($child->url) . "'>";
						$out .= "<span class='c-mega__group-arrow'>{$this->arrow_svg()}</span>";
						$out .= "<span class='c-mega__group-text'>" . esc_html($child->title) . "</span>";
						$out .= "</a></li>";
					}
					$out .= "</ul>";
				}

				$out .= "</div>";
				continue;
			}

			$out .= "<div class='c-mega__group'>";
			$out .= "<a class='c-mega__group-link--single' onfocus='blur();' href='" . esc_url($root->url) . "'>";
			$out .= "<span class='c-mega__group-text'>" . esc_html($root->title) . "</span>";
			$out .= "</a></div>";
		}

		$out .= "</div></div></div>";
		return $out;
	}

	private function render_submenu_right_panel(int $menu_id, bool $active, string $panel_id): string
	{
		$items = $this->get_menu_items($menu_id);
		$tree  = $this->build_tree($items);
		$roots = $tree['roots'];

		$style = $active ? "" : " style='display:none;'";
		$out  = "<div class='c-mega__panel' data-mega-panel='{$panel_id}'{$style}>";
		$out .= "<ul class='c-mega__list'>";

		foreach ($roots as $it) {
			$it_id = (int) $it->ID;
			$icon  = $this->get_icon_html($it_id);

			$out .= "<li class='c-mega__list-item'>";
			$out .= "<a class='c-mega__list-link' onfocus='blur();' href='" . esc_url($it->url) . "'>";
			$out .= $icon ? "<span class='c-mega__list-icon'>{$icon}</span>" : "";
			$out .= "<span class='c-mega__list-text'>" . esc_html($it->title) . "</span>";
			$out .= "</a>";
			$out .= "</li>";
		}

		$out .= "</ul>";
		$out .= "</div>";

		return $out;
	}


	private function get_submenu_menus_cfg(int $menu_id): array
	{
		$val = $this->get_menu_term_field('sub_menu', $menu_id);
		return is_array($val) ? $val : [];
	}

	/**
	 * type_menu = submenu
	 * - esquerda: tabs do ACF do MENU (term) -> sub_menu (repeater) [icone, title, menu]
	 * - meio: banner do MENU SELECIONADO
	 * - direita: itens do MENU SELECIONADO com ícones por item (ativar_icone/icone)
	 *
	 * Regras: "o menu é igual ao type lista" => a direita usa os itens/estrutura existente (grid/c-card), e os ícones vêm do menu selecionado.
	 */
	private function render_type_submenu_tabs_from_parent(int $parent_menu_id): string
	{
		$rows = $this->get_submenu_menus_cfg($parent_menu_id);
		if (empty($rows)) return '';

		$tabs = [];
		foreach ($rows as $row) {
			if (!is_array($row)) continue;

			$title   = (string) ($row['title'] ?? '');
			$menu_id = $this->normalize_menu_id($row['menu'] ?? 0);
			if (!$title || !$menu_id) continue;

			$icon = $row['icone'] ?? null;
			$icon_html = '';

			if (is_array($icon) && !empty($icon['ID'])) {
				$icon_html = wp_get_attachment_image((int) $icon['ID'], 'thumbnail', false, [
					'loading'  => 'lazy',
					'decoding' => 'async',
				]);
			} elseif (is_numeric($icon)) {
				$icon_html = wp_get_attachment_image((int) $icon, 'thumbnail', false, [
					'loading'  => 'lazy',
					'decoding' => 'async',
				]);
			}

			$tabs[] = [
				'title' => $title,
				'menu_id' => $menu_id,
				'icon_html' => $icon_html,
			];
		}

		if (empty($tabs)) return '';

		$uid = 'mega_' . $parent_menu_id . '_' . wp_rand(1000, 9999);

		$out  = "<div class='c-main-menu__dropdown c-main-menu__dropdown--mega'>";
		$out .= "<div class='c-mega c-mega--submenu-tabs' data-mega-tabs='{$uid}'>";

		// ESQUERDA: lista igual ao banner (c-mega__list)
		$out .= "<div class='c-mega__left'>";
		$out .= "<ul class='c-mega__list'>";

		foreach ($tabs as $i => $tab) {
			$is_active    = ($i === 0);
			$panel_id     = $uid . '_p' . $i;
			$active_class = $is_active ? ' is-active' : '';

			$out .= "<li class='c-mega__list-item'>";
			$out .= "<button type='button' class='c-mega__list-link{$active_class}' data-mega-tab='{$panel_id}' aria-expanded='" . ($is_active ? "true" : "false") . "'>";
			$out .= $tab['icon_html'] ? "<span class='c-mega__list-icon'>{$tab['icon_html']}</span>" : "";
			$out .= "<span class='c-mega__list-text'>" . esc_html($tab['title']) . "</span>";
			$out .= "</button>";
			$out .= "</li>";
		}

		$out .= "</ul>";
		$out .= "</div>";

		// MEIO: banner do menu selecionado (painéis)
		$out .= "<div class='c-mega__center'>";
		foreach ($tabs as $i => $tab) {
			$panel_id = $uid . '_p' . $i;
			$active   = ($i === 0);
			$style    = $active ? "" : " style='display:none;'";

			$cfg_sel = [
				'mega_banner' => (array) $this->get_menu_term_field('mega_banner', (int) $tab['menu_id']),
			];

			$out .= "<div class='c-mega__panel' data-mega-panel='{$panel_id}'{$style}>";
			$out .= $this->render_mega_banner((array) $cfg_sel['mega_banner']);
			$out .= "</div>";
		}
		$out .= "</div>";

		// DIREITA: lista igual ao banner (c-mega__list) com ícones do menu selecionado
		$out .= "<div class='c-mega__right'>";
		foreach ($tabs as $i => $tab) {
			$panel_id = $uid . '_p' . $i;
			$out .= $this->render_submenu_right_panel((int) $tab['menu_id'], $i === 0, $panel_id);
		}
		$out .= "</div>";

		$out .= "</div>";

		// JS tabs
		$out .= "<script>
    (function(){
      var root = document.querySelector('[data-mega-tabs=\"{$uid}\"]');
      if(!root) return;

      var tabBtns = root.querySelectorAll('[data-mega-tab]');
      var panels  = root.querySelectorAll('[data-mega-panel]');

      function setActive(id){
        tabBtns.forEach(function(b){
          var on = b.getAttribute('data-mega-tab') === id;
          b.classList.toggle('is-active', on);
          b.setAttribute('aria-expanded', on ? 'true' : 'false');
        });
        panels.forEach(function(p){
          p.style.display = (p.getAttribute('data-mega-panel') === id) ? '' : 'none';
        });
      }

      tabBtns.forEach(function(b){
        b.addEventListener('mouseenter', function(){ setActive(b.getAttribute('data-mega-tab')); });
        b.addEventListener('click', function(){ setActive(b.getAttribute('data-mega-tab')); });
      });
    })();
  </script>";

		$out .= "</div>";

		return $out;
	}


	private function render_mega_from_menu_id(int $menu_id): string
	{
		if ($menu_id <= 0) return '';

		$ativar_mega = (bool) $this->get_menu_term_field('ativar_mega', $menu_id);
		if (!$ativar_mega) {
			$items = $this->get_menu_items($menu_id);
			$tree  = $this->build_tree($items);

			$out  = "<div class='c-main-menu__dropdown c-main-menu__dropdown--mega'>";
			$out .= "<ul class='c-main-menu__sub-menu c-main-menu__sub-menu--grid'>";
			foreach ($tree['roots'] as $it) {
				$out .= "<li class='c-main-menu__sub-item'>";
				$out .= "<a class='sub-menu-item c-main-menu__card' onfocus='blur();' href='" . esc_url($it->url) . "'>";
				$out .= "<span class='c-main-menu__card-body'>";
				$out .= "<span class='c-main-menu__card-title'>" . esc_html($it->title) . "</span>";
				$out .= "</span></a></li>";
			}
			$out .= "</ul></div>";
			return $out;
		}

		$type = (string) $this->get_menu_term_field('type_menu', $menu_id);

		if ($type === 'banner') {
			$cfg = [
				'mega_banner' => (array) $this->get_menu_term_field('mega_banner', $menu_id),
				'banner_case' => (array) $this->get_menu_term_field('banner_case', $menu_id),
			];
			return $this->render_type_banner($menu_id, $cfg);
		}

		if ($type === 'submenu') {
			// ✅ submenu agora usa ACF sub_menu do MENU (pai) para listar menus
			// e renderiza banner/itens com base no menu selecionado.
			$tabs = $this->render_type_submenu_tabs_from_parent($menu_id);
			return $tabs ?: $this->render_type_list($menu_id);
		}

		// list
		return $this->render_type_list($menu_id);
	}

	/* ================= WALKER CORE ================= */

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
		$has_children = !empty($args->has_children);

		if ($depth === 0) {
			$this->top_cfg = (isset($item->controller_cfg) && is_array($item->controller_cfg))
				? $item->controller_cfg
				: ['has_controller' => false, 'enable_mega' => false, 'menu_id' => 0, 'controller_id' => 0];

			$output .= "<li class='{$item_classes} c-main-menu__item'>";
			$this->opened_li_depth[$depth] = true;

			$output .= "<div class='c-main-menu__item-head'>";

			if (!empty($item->url)) {
				$output .= "<a class='c-main-menu__link' onfocus='blur();' {$attr_target} href='" . esc_url($item->url) . "'>
          <span>" . esc_html($item->title) . "</span>
        </a>";
			} else {
				$output .= "<span class='c-main-menu__link'><span>" . esc_html($item->title) . "</span></span>";
			}

			if ($has_children || (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id']))) {
				$output .= "<button class='c-main-menu__toggle' type='button' aria-expanded='false'
          aria-label='" . esc_attr__('Abrir submenu', 'textdomain') . "'>
          " . $this->toggle_svg() . "
        </button>";
			}

			$output .= "</div>";
			return;
		}

		if (!empty($this->top_cfg['controller_id']) && $item_id === (int) $this->top_cfg['controller_id']) return;
		if (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id'])) return;

		$output .= "<li class='{$item_classes}'>";
		$this->opened_li_depth[$depth] = true;

		$output .= "<a onfocus='blur();' {$attr_target} href='" . esc_url($item->url) . "'>";
		$output .= esc_html($item->title);
		$output .= "</a>";
	}

	public function start_lvl(&$output, $depth = 0, $args = [])
	{
		if ($depth !== 0) {
			if (empty($this->top_cfg['enable_mega'])) $output .= "<ul class='sub-menu'>";
			return;
		}

		if (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id'])) {
			$mega = $this->render_mega_from_menu_id((int) $this->top_cfg['menu_id']);
			$output .= $mega ?: "<div class='c-main-menu__dropdown'><div class='c-mega__empty'></div></div>";
			return;
		}

		$output .= "<ul class='sub-menu'>";
	}

	public function end_lvl(&$output, $depth = 0, $args = [])
	{
		if ($depth !== 0) {
			if (empty($this->top_cfg['enable_mega'])) $output .= "</ul>";
			return;
		}

		if (!empty($this->top_cfg['enable_mega']) && !empty($this->top_cfg['menu_id'])) return;

		$output .= "</ul>";
	}

	public function end_el(&$output, $item, $depth = 0, $args = [])
	{
		if (!empty($this->top_cfg['controller_id']) && (int) $item->ID === (int) $this->top_cfg['controller_id']) return;
		if (!empty($this->top_cfg['enable_mega']) && $depth > 0) return;

		if (!empty($this->opened_li_depth[$depth])) {
			$output .= "</li>";
			unset($this->opened_li_depth[$depth]);
		}
	}
}
