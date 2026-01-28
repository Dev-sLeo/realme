<?php
class Main_Menu_Walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth = 2, $args = array(), $id = 0)
    {
        $indent = str_repeat("\t", $depth);
        // // var_dump($args->has_children);
        $attr_target = !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        if ($depth == 0) {
            $item_classes = implode(' ', $item->classes);

            if (str_contains($item_classes, 'menu-item-has-children')) {
                if ($item->url != '') {
                    $output .= sprintf("\n<li class='%s c-main-menu__item'><a class='c-main-menu__link' onfocus='blur();' $attr_target href='%s'><span>%s</span><svg xmlns='http://www.w3.org/2000/svg' width='15' height='9' viewBox='0 0 15 9' fill='none'>
<path d='M14.0176 1L7.66246 7.5L1.16246 1' stroke='#8F7ED7' stroke-width='1.5' stroke-miterlimit='10'/>
</svg></a>", $item_classes, $item->url, $item->title);
                } else {
                    $output .= sprintf("\n<li class='%s c-main-menu__item'><span>%s</span><svg xmlns='http://www.w3.org/2000/svg' width='15' height='9' viewBox='0 0 15 9' fill='none'>
<path d='M14.0176 1L7.66246 7.5L1.16246 1' stroke='#8F7ED7' stroke-width='1.5' stroke-miterlimit='10'/>
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
            $item_classes = implode(' ', $item->classes);
            $output .= sprintf("\n<li class='c-main-menu__sub-sub-item %s'><a class='sub-menu-item' onfocus='blur();' $attr_target href='%s'><span>%s</span></a>", $item_classes, $item->url, $item->title);
        } else if ($depth == 2) {
            $item_classes = implode(' ', $item->classes);
            $output .= sprintf("\n<li class='c-main-menu__sub-sub-item %s'><a class='sub-sub-menu-item' onfocus='blur();' $attr_target href='%s'><span>%s</span></a>", $item_classes, $item->url, $item->title);
        }
    }

    function end_el(&$output, $item, $depth = 1, $args = array(), $id = 0)
    {
        $output .= "</li>\n";
    }
}