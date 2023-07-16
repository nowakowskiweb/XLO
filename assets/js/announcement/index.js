import { Filters } from "./sections/filters";

document.addEventListener("DOMContentLoaded", () => {
    try {
        new Filters();
    } catch (error) {
        console.error(error);
    }
});
