"use strict";

import Swiper from "swiper";
import { Navigation } from "swiper/modules";

function onReady(fn) {
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", fn, { once: true });
        return;
    }
    fn();
}

function renderSkeleton(count = 3) {
    let html = "";
    for (let i = 0; i < count; i++) {
        html += `
      <div class="swiper-slide o-testimonials__slide is-skeleton">
        <article class="c-testimonial o-testimonials__card o-module__sk-item">
          <div class="o-module__sk-line"></div>
          <div class="o-module__sk-line"></div>
          <div class="o-module__sk-line"></div>
        </article>
      </div>
    `;
    }
    return html;
}

class TestimonialsAjax {
    constructor(root) {
        this.root = root;
        this.listEl = root.querySelector(".js-list");
        this.emptyEl = root.querySelector(".js-empty");
        this.sliderEl = root.querySelector(".o-testimonials__slider");
        this.swiperEl = root.querySelector(".o-testimonials__swiper.swiper");

        this.action = root.dataset.action || "";
        this.nonce = root.dataset.nonce || "";

        this.controller = null;
        this.state = { filter: "" };

        this.swiper = null;
    }

    init() {
        if (!this.listEl || !this.swiperEl || !this.action || !this.nonce)
            return;

        if (this.root.dataset.init === "1") return;
        this.root.dataset.init = "1";

        const prevEl = this.root.querySelector("[data-testimonials-prev]");
        const nextEl = this.root.querySelector("[data-testimonials-next]");

        this.swiper = new Swiper(this.swiperEl, {
            modules: [Navigation],
            slidesPerView: 1,
            spaceBetween: 26,
            autoHeight: true,
            loop: false,
            watchOverflow: true,
            navigation: prevEl && nextEl ? { prevEl, nextEl } : undefined,
        });

        this.root.addEventListener("click", (e) => {
            const btn = e.target.closest(".js-filter-btn[data-filter]");
            if (!btn || !this.root.contains(btn)) return;

            const slug = (btn.getAttribute("data-filter") || "").trim();
            const isActive = btn.classList.contains("is-active");
            const nextFilter = isActive ? "" : slug;

            this.setActiveButton(nextFilter);
            this.fetchAndReplace(nextFilter);
        });

        this.fetchAndReplace("");
    }

    setBusy(isBusy) {
        if (this.sliderEl)
            this.sliderEl.setAttribute("aria-busy", isBusy ? "true" : "false");
    }

    setActiveButton(filter) {
        const buttons = Array.from(
            this.root.querySelectorAll(".js-filter-btn[data-filter]"),
        );
        for (const b of buttons) {
            b.classList.remove("is-active");
            b.setAttribute("aria-selected", "false");
        }
        if (!filter) return;

        const active = this.root.querySelector(
            `.js-filter-btn[data-filter="${CSS.escape(filter)}"]`,
        );
        if (active) {
            active.classList.add("is-active");
            active.setAttribute("aria-selected", "true");
        }
    }

    abort() {
        if (this.controller) this.controller.abort();
        this.controller = null;
    }

    async fetchAndReplace(filter) {
        this.abort();

        this.state.filter = filter;

        this.setBusy(true);
        this.listEl.innerHTML = renderSkeleton(3);
        if (this.emptyEl) this.emptyEl.hidden = true;

        this.controller = new AbortController();

        const fd = new FormData();
        fd.append("action", this.action);
        fd.append("nonce", this.nonce);
        fd.append("filter", filter);

        try {
            const res = await fetch(
                window.ajaxurl || "/wp-admin/admin-ajax.php",
                {
                    method: "POST",
                    body: fd,
                    signal: this.controller.signal,
                    credentials: "same-origin",
                },
            );

            const json = await res.json();

            if (!json || !json.success) {
                this.listEl.innerHTML = "";
                if (this.emptyEl) this.emptyEl.hidden = false;
                this.setBusy(false);
                return;
            }

            const html = (json.data && json.data.html) || "";
            const meta = (json.data && json.data.meta) || {};
            const found = Number(meta.found_posts || 0);

            this.listEl.innerHTML = html;

            if (this.emptyEl) this.emptyEl.hidden = found !== 0;

            if (this.swiper) {
                this.swiper.update();
                this.swiper.slideTo(0, 0);
            }

            this.setBusy(false);
        } catch (err) {
            if (err && err.name === "AbortError") return;

            this.listEl.innerHTML = "";
            if (this.emptyEl) this.emptyEl.hidden = false;
            this.setBusy(false);
        }
    }
}

export default function initTestimonialsAjax() {
    onReady(() => {
        document
            .querySelectorAll(".js-module.o-testimonials")
            .forEach((root) => {
                const instance = new TestimonialsAjax(root);
                instance.init();
            });
    });
}
