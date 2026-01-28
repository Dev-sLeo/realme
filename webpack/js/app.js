"use strict";

import { gsap } from "gsap";
import main from "../css/sass/main.scss";
import inline from "../css/sass/inline.scss";
import React from "react";
import { createRoot } from "react-dom/client";
import ThemeWidget from "./react/ThemeWidget";

import headerScrolling from "./scripts/headerScrolling";
import mobileMenu from "./scripts/mobileMenu";
import inputSelect from "./scripts/inputSelect";
import parallax from "./scripts/parallax";
import accordion from "./scripts/accordion";
import gsapAnimations from "./scripts/gsapAnimations";

window.gsap = gsap;

headerScrolling();
mobileMenu();
inputSelect();
parallax();
accordion();
gsapAnimations();

/*
const reactRoot = document.getElementById("theme-react-root");

if (reactRoot) {
    const { title, description } = reactRoot.dataset;
    const root = createRoot(reactRoot);

    root.render(
        <ThemeWidget
            title={title || "Integração com React"}
            description={
                description ||
                "Este bloco é renderizado pelo React e pode ser evoluído com componentes."
            }
        />
    );
}*/
