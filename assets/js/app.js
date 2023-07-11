import { MenuMobile } from "./global/menu-mobile";
document.addEventListener("DOMContentLoaded", () => {
    try {
        new MenuMobile();
    } catch (error) {
        console.error(error);
    }
});
