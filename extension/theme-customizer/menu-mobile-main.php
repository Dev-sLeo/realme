<?php
global $tpl_engine;

class Main_Menu_Mobile_Walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';
		$attr_target = !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';

		$item_classes = implode(' ', $item->classes);

		if ($depth == 0) {
			

			if (str_contains($item_classes, 'menu-item-has-children')) {
				if ($item->url != '') {
					$output .= sprintf("\n<li class='%s c-main-menu__item'><a class='c-main-menu__link' onfocus='blur();' $attr_target href='%s'><span>%s</span><svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8' fill='none'>
<path d='M10.875 0.750031L6.10863 5.62503L1.23363 0.75003' stroke='#8F7ED7' stroke-width='2' stroke-miterlimit='10'/>
</svg></a>", $item_classes, $item->url, $item->title);
				} else {
					$output .= sprintf("\n<li class='%s c-main-menu__item'><span>%s</span><svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8' fill='none'>
<path d='M10.875 0.750031L6.10863 5.62503L1.23363 0.75003' stroke='#8F7ED7' stroke-width='2' stroke-miterlimit='10'/>
</svg>", $item_classes, $item->title);
				}
			} else {
				if ($item->url != '') {
					$output .= sprintf("\n<li class='%s c-main-menu__item'><a class='c-main-menu__link' onfocus='blur();' $attr_target href='%s'><span>%s</span></a>", $item_classes, $item->url, $item->title);
				} else {
					$output .= sprintf("\n<li class='%s c-main-menu__item'><span>%s</span>", $item_classes, $item->title);
				}
			}
			
		} else if ($depth == 1) {
			$output .= sprintf("\n<li class='c-main-menu__sub-item'><a class='%s sub-menu-item' onfocus='blur();' $attr_target href='%s'><span>%s</span></a>", $item_classes, $item->url, $item->title);
		} else if ($depth == 2) {
			$output .= sprintf("\n<li class='c-main-menu__sub-item'><a class='%s sub-sub-menu-item' onfocus='blur();' $attr_target href='%s'>%s</a>", $item_classes, $item->url, $item->title);
		}
	}

	function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$output .= "</li>\n";
	}

	function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';
		if ($depth == 0) {
			$output .= "\n$indent<div class='c-submenu-trigger-wrapper'><div class='c-submenu-top-content js-submenu-trigger-back'><span class='c-submenu__close js-close-submenu'><svg xmlns='http://www.w3.org/2000/svg' width='9' height='16' viewBox='0 0 9 16' fill='none'>
<path d='M7.99995 1.5L1.49995 7.85512L7.99995 14.3551' stroke='white' stroke-width='2' stroke-miterlimit='10'/>
</svg><span> Voltar</span></div><div class='s-container s-containers-container--mobile-fluid'><div class=\"sub-menu\"><div class=\"sub-menu-scroll-content\"><span class='c-submenu-title js-submenu-parent-name'></span>\n<ul class='c-main-menu-mobile__sub-list'>";
		} else if ($depth == 1) {
			$output .= "\n$indent<ul class='c-main-menu-mobile__sub-list'>";
		} else {
			$output .= "\n$indent<ul class='c-main-menu-mobile__sub-list'>";
		}
	}

	function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';
		if ($depth == 0) {
			$output .= "$indent</ul></div></div></div></div>";
		} else {
			$output .= "$indent</ul>";
		}
	}
}
