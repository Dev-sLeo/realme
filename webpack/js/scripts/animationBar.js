import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

export default function animationBar() {
    gsap.registerPlugin(ScrollTrigger);

    (function () {
        const bars = document.querySelectorAll(".js-stat-bar");
        if (bars.length === 0) return;
        bars.forEach((bar) => {
            if (bar.dataset.initialized === "true") return;
            bar.dataset.initialized = "true";

            const value = (bar.dataset.value || "0").trim();
            const fill = bar.querySelector(".o-product-stat__bar-value");
            if (!fill) return;

            gsap.set(fill, { width: "0%" });

            gsap.to(fill, {
                width: value,
                duration: 1.2,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: bar,
                    start: "top 85%",
                    toggleActions: "play none none none",
                },
            });
        });
    })();
}
