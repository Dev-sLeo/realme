"use strict";

import { gsap } from "gsap";
import main from "../css/sass/main.scss";
import inline from "../css/sass/inline.scss";
import React from "react";
import { createRoot } from "react-dom/client";
import ThemeWidget from "./react/ThemeWidget";

import headerScrolling from "./scripts/headerScrolling";
import mobileMenu from "./scripts/mobileMenu";
import megaMenu from "./scripts/megaMenu";
import inputSelect from "./scripts/inputSelect";
import accordion from "./scripts/accordion";
import gsapAnimations from "./scripts/gsapAnimations";

import initTestimonialsAjax from "./scripts/slider/testimonails";
import carrouselInfinite from "./scripts/slider/carrouselInfinite";
import logoInfinite from "./scripts/slider/logoInfinite";
import casesHomeSlider from "./scripts/slider/casesHomeSlider";
import initCasesLayouts from "./scripts/casesLayouts";
import productBenefitsSlider from "./scripts/slider/productBenefitsSlider";
import casesInfinite from "./scripts/slider/casesInfinite";
import highlightsCases from "./scripts/slider/highlightsCases";
import animationBar from "./scripts/animationBar";

window.gsap = gsap;

headerScrolling();
mobileMenu();
megaMenu();
inputSelect();
accordion();
gsapAnimations();

initTestimonialsAjax();
carrouselInfinite();
casesHomeSlider();
productBenefitsSlider();
initCasesLayouts();
logoInfinite();
casesInfinite();
highlightsCases();
animationBar();

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
