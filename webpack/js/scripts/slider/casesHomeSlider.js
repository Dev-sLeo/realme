import Swiper from "swiper";
import { Navigation, Mousewheel } from "swiper/modules";
import "swiper/css";

export default function () {
    const root = document.querySelector(
        '.o-case-studies__swiper[data-swiper="case-studies"]',
    );
    if (!root) return;

    const sliderWrap = root.closest(".o-case-studies__slider");
    const btnPrev = root.querySelector(".o-case-studies__arrow--prev");
    const btnNext = root.querySelector(".o-case-studies__arrow--next");
    const arrows = root.querySelector(".arrow-container");

    const isDesktop = () => window.matchMedia("(min-width: 1024px)").matches;

    const setNavState = (s) => {
        const desk = isDesktop();

        if (arrows) arrows.style.display = desk ? "none" : "";
        if (btnPrev) btnPrev.disabled = desk;
        if (btnNext) btnNext.disabled = desk;

        if (desk) s.navigation.disable();
        else s.navigation.enable();
    };

    const calcDesktopHeight = (s) => {
        if (!sliderWrap) return;

        if (!isDesktop()) {
            sliderWrap.style.height = "";
            return;
        }

        const total = s.slides?.length || 0;
        const visible = Math.min(3, total);

        if (visible <= 0) {
            sliderWrap.style.height = "";
            return;
        }

        const space = Number(s.params.spaceBetween || 0);

        sliderWrap.style.height = "auto";

        const wrapper = s.wrapperEl;
        if (wrapper) wrapper.offsetHeight;

        let sum = 0;

        for (let i = 0; i < visible; i++) {
            const slide = s.slides[i];
            if (!slide) continue;

            const card =
                slide.querySelector(".c-case-card") || slide.firstElementChild;
            const h = card ? card.offsetHeight : slide.offsetHeight;

            sum += Math.ceil(h || 0);
        }

        const height = sum + space * (visible - 1);
        sliderWrap.style.height = `${height}px`;
    };

    const forceUpdate = (s) => {
        requestAnimationFrame(() => {
            s.update();
            requestAnimationFrame(() => {
                calcDesktopHeight(s);
                s.update();
            });
        });
    };

    const swiper = new Swiper(root, {
        modules: [Navigation, Mousewheel],
        slidesPerView: 1,
        direction: "horizontal",
        spaceBetween: 16,
        mousewheel: false,
        navigation: {
            prevEl: btnPrev,
            nextEl: btnNext,
            enabled: true,
        },
        breakpoints: {
            1024: {
                direction: "vertical",
                slidesPerView: "auto",
                spaceBetween: 24,
                mousewheel: true,
            },
        },
        observer: true,
        observeParents: true,
        on: {
            init(s) {
                setNavState(s);
                forceUpdate(s);
            },
            breakpoint(s) {
                setNavState(s);
                forceUpdate(s);
            },
            resize(s) {
                forceUpdate(s);
            },
            imagesReady(s) {
                forceUpdate(s);
            },
            observerUpdate(s) {
                forceUpdate(s);
            },
        },
    });

    if ("ResizeObserver" in window) {
        const ro = new ResizeObserver(() => forceUpdate(swiper));
        if (sliderWrap) ro.observe(sliderWrap);
        ro.observe(root);
        swiper.on("destroy", () => ro.disconnect());
    }

    window.addEventListener("load", () => forceUpdate(swiper), {
        passive: true,
        once: true,
    });

    return swiper;
}
