import Swiper from "swiper";
import { Autoplay, Navigation, Pagination } from "swiper/modules";

function initArchiveHeroSlider() {
    const el = document.querySelector(
        '.js-archive-cases-hero-swiper[data-swiper="archive-cases-hero"]',
    );
    if (!el) return;

    new Swiper(el, {
        modules: [Autoplay],
        slidesPerView: 1.2,
        spaceBetween: 12,
        loop: true,
        speed: 4500,
        allowTouchMove: true,
        autoplay: {
            delay: 1,
            disableOnInteraction: false,
        },
        breakpoints: {
            768: { slidesPerView: 3.2, spaceBetween: 16 },
            1024: { slidesPerView: 5.2, spaceBetween: 16 },
            1366: { slidesPerView: 6.2, spaceBetween: 16 },
        },
    });
}

function initFeaturedCasesSlider() {
    const el = document.querySelector(
        '.js-cases-destaque-swiper[data-swiper="cases-destaque"]',
    );
    if (!el) return;

    const wrap = el.closest(".c-cases-destaque__slider-wrap");
    const prevEl = wrap?.querySelector(".c-cases-destaque__arrow--prev");
    const nextEl = wrap?.querySelector(".c-cases-destaque__arrow--next");
    const pagEl = el.querySelector(".c-cases-destaque__pagination");

    new Swiper(el, {
        modules: [Navigation, Pagination],
        slidesPerView: 1,
        spaceBetween: 24,
        navigation: { prevEl, nextEl },
        pagination: { el: pagEl, clickable: true },
    });
}

function initArchiveFilters() {
    const root = document.querySelector(".js-archive-cases-results");
    if (!root) return;

    const form = root.querySelector(".js-archive-cases-filters");
    const grid = root.querySelector(".js-archive-cases-grid");
    const catButtons = root.querySelectorAll(".js-cat-filter");
    const catInput = root.querySelector(".js-cat-input");
    const select = form ? form.querySelector('select[name="regiao"]') : null;

    const ajaxUrl = root.getAttribute("data-ajax-url");
    const postType = root.getAttribute("data-post-type");
    const taxCat = root.getAttribute("data-tax-cat");
    const taxRegiao = root.getAttribute("data-tax-regiao");
    const nonce = root.getAttribute("data-nonce");

    const setActiveCat = (slug) => {
        catButtons.forEach((btn) => {
            const isActive = (btn.getAttribute("data-cat") || "") === (slug || "");
            btn.classList.toggle("is-active", isActive);
            btn.setAttribute("aria-pressed", isActive ? "true" : "false");
        });
    };

    const fetchCases = async () => {
        if (!grid) return;

        const cat = catInput ? catInput.value || "" : "";
        const regiao = select ? select.value || "" : "";

        grid.setAttribute("aria-busy", "true");

        const body = new URLSearchParams();
        body.set("action", "archive_cases_filter");
        body.set("nonce", nonce || "");
        body.set("post_type", postType || "case");
        body.set("tax_cat", taxCat || "cat_case");
        body.set("tax_regiao", taxRegiao || "regiao");
        body.set("cat", cat);
        body.set("regiao", regiao);

        try {
            const response = await fetch(ajaxUrl, {
                method: "POST",
                headers: {
                    "Content-Type":
                        "application/x-www-form-urlencoded; charset=UTF-8",
                },
                body: body.toString(),
                credentials: "same-origin",
            });

            const html = await response.text();
            grid.innerHTML = html;
        } finally {
            grid.setAttribute("aria-busy", "false");
        }
    };

    catButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const slug = btn.getAttribute("data-cat") || "";
            if (catInput) catInput.value = slug;
            setActiveCat(slug);
            fetchCases();
        });
    });

    if (select) {
        select.addEventListener("change", () => {
            fetchCases();
        });
    }

    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            fetchCases();
        });
    }
}

export default function initCasesLayouts() {
    initArchiveHeroSlider();
    initFeaturedCasesSlider();
    initArchiveFilters();
}
