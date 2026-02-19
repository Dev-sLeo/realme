"use strict";

export default function () {
    const body = document.body;
    const menu = document.querySelector(".menu-mobile");
    const hamburguer = document.querySelector(".hamburguer-menu");

    if (!menu || !hamburguer) return;

    const close = menu.querySelector(".close-icon");
    const mobileButton = menu.querySelector(".menu-mobile .button");

    const setMenuOpen = (open) => {
        menu.classList.toggle("active", open);
        body.style.overflow = open ? "hidden" : "auto";
        if (!open) closeAllSubmenus();
    };

    const toggleMenu = () => setMenuOpen(!menu.classList.contains("active"));

    hamburguer.addEventListener("click", toggleMenu);
    if (close) close.addEventListener("click", () => setMenuOpen(false));
    if (mobileButton)
        mobileButton.addEventListener("click", () => setMenuOpen(false));

    const subMenus = menu.querySelectorAll(".menu-item-has-children");

    const getToggle = (li) => li.querySelector(".c-main-menu__toggle");
    const getDropdown = (li) =>
        li.querySelector(".c-main-menu__dropdown, .c-submenu-trigger-wrapper");

    const closeSubmenu = (li) => {
        li.classList.remove("is-open");
        const t = getToggle(li);
        if (t) t.setAttribute("aria-expanded", "false");

        const dd = getDropdown(li);
        if (dd) dd.classList.remove("is-open");
    };

    const openSubmenu = (li) => {
        li.classList.add("is-open");
        const t = getToggle(li);
        if (t) t.setAttribute("aria-expanded", "true");

        const dd = getDropdown(li);
        if (dd) dd.classList.add("is-open");
    };

    const closeAllSubmenus = () => {
        subMenus.forEach(closeSubmenu);
    };

    subMenus.forEach((li) => {
        const toggle = getToggle(li);
        const dropdown = getDropdown(li);

        // se não tem toggle ou dropdown, ignora
        if (!toggle || !dropdown) return;

        toggle.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = li.classList.contains("is-open");

            // accordion: fecha os outros
            subMenus.forEach((other) => {
                if (other !== li) closeSubmenu(other);
            });

            // toggle atual
            if (isOpen) {
                closeSubmenu(li);
            } else {
                openSubmenu(li);
            }
        });
    });

    // Opcional: clique fora fecha submenus (se quiser, descomente)
    // menu.addEventListener("click", (e) => {
    //   const clickedInsideSubmenu = e.target.closest(".menu-item-has-children");
    //   if (!clickedInsideSubmenu) closeAllSubmenus();
    // });
}
