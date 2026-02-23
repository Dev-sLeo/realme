import Swiper from "swiper";
import { Navigation } from "swiper/modules";

export default function () {
    const root = document.querySelector(
        '.o-product-benefits__swiper[data-swiper="product-benefits"]',
    );

    if (!root) return;

    const prevEl = root.querySelector(".o-product-benefits__arrow--prev");
    const nextEl = root.querySelector(".o-product-benefits__arrow--next");

    return new Swiper(root, {
        modules: [Navigation],
        slidesPerView: 1,
        spaceBetween: 16,
        navigation: {
            prevEl,
            nextEl,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
}
