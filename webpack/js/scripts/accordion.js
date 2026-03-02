"use strict";

export default function () {
    document.addEventListener("click", function (e) {
        const head = e.target.closest(".c-accordion__head");
        if (!head) return;

        const item = head.closest(".c-accordion__item");
        const body = item.querySelector(".c-accordion__body");

        item.classList.toggle("is-open");

        if (item.classList.contains("is-open")) {
            body.style.height = body.scrollHeight + "px";
        } else {
            body.style.height = 0;
        }
    });
    const modules = document.querySelectorAll(".js-faq");

    modules.forEach((module) => {
        if (module.dataset.initialized === "true") return;
        module.dataset.initialized = "true";

        const items = module.querySelectorAll(".js-faq-item");
        const button = module.querySelector(".js-faq-load-more");
        if (!button) return;

        const step = parseInt(button.dataset.step) || 5;
        let visible = module.querySelectorAll(
            ".js-faq-item:not([hidden])",
        ).length;

        button.addEventListener("click", () => {
            let count = 0;

            items.forEach((item) => {
                if (item.hasAttribute("hidden") && count < step) {
                    item.removeAttribute("hidden");
                    count++;
                }
            });

            visible += count;

            if (visible >= items.length) {
                button.remove();
            }
        });
    });
}
