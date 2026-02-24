import Swiper from "swiper";
import { Navigation } from "swiper/modules";

export default function () {
    const root = document.querySelector(
        '.c-cases-destaque__slider[data-swiper="cases-destaque"]',
    );

    if (!root) return;

    const prevEl = root.querySelector(".c-cases-destaque__arrow--prev");
    const nextEl = root.querySelector(".c-cases-destaque__arrow--next");

    return new Swiper(root, {
        modules: [Navigation],
        loop: true,
        slidesPerView: 1,
        spaceBetween: 16,
        navigation: {
            prevEl,
            nextEl,
        },
    });
}
