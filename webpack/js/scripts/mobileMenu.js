"use strict";

export default function () {
    const hamburguer = document.querySelector(".hamburguer-menu");
    const hiddenScroll = document.querySelector("body");
    const mobileButton = document.querySelector(".menu-mobile .button");
    const menu = document.querySelector(".menu-mobile");
    const close = menu.querySelector(".close-icon");
    hamburguer.addEventListener("click", () => {
        menu.classList.toggle("active");
        hiddenScroll.style.overflow = menu.classList.contains("active")
            ? "hidden"
            : "auto";
    });
    close.addEventListener("click", () => {
        menu.classList.toggle("active");
        hiddenScroll.style.overflow = menu.classList.contains("active")
            ? "hidden"
            : "auto";
    });

    mobileButton.addEventListener("click", () => {
        menu.classList.toggle("active");
        hiddenScroll.style.overflow = menu.classList.contains("active")
            ? "hidden"
            : "auto";
    });
}
