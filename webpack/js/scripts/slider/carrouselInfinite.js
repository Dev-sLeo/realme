"use strict";
import Splide from "@splidejs/splide";
import { AutoScroll } from "@splidejs/splide-extension-auto-scroll";

export default function () {
    document.addEventListener("DOMContentLoaded", () => {
        const clienteSlider = document.querySelector(
            ".o-integrations__splide.splide",
        );
        if (!clienteSlider) return;

        const splide = new Splide(clienteSlider, {
            type: "loop",
            perPage: 5,
            drag: "free",
            focus: "center",
            arrows: false,
            pagination: false,
            gap: "1.5rem",
            autoScroll: {
                speed: 0.5,
            },
            breakpoints: {
                1024: {
                    perPage: 3,
                },
                680: {
                    perPage: 2,
                },
            },
        });

        splide.mount({ AutoScroll });
    });
}
