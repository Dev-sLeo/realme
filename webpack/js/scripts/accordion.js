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
}
