import Swiper from "swiper";
import { A11y } from "swiper/modules";
import "swiper/css";

export default function () {
    const root = document.querySelector(
        '.o-about__swiper[data-swiper="about-features"]',
    );
    if (!root) return;

    const swiper = new Swiper(root, {
        modules: [A11y],
        slidesPerView: 1,
        spaceBetween: 16,
        watchOverflow: true,
        a11y: true,
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 44,
            },
        },
    });

    const onResize = () => swiper.update();
    window.addEventListener("resize", onResize);
}
