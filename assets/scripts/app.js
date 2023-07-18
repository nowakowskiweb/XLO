import { Navbar } from "@layouts-scripts/navbar";
document.addEventListener("DOMContentLoaded", () => {
    try {
        new Navbar();
    } catch (error) {
        console.error(error);
    }
});
