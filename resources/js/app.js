import "./bootstrap";

import Alpine from "alpinejs";


document.addEventListener("DOMContentLoaded", () => {
    initializeImageSlider();
});

window.addEventListener("load", () => {
    console.log("Window fully loaded");
    initializeImageSlider();
});

window.Alpine = Alpine;

Alpine.start();
