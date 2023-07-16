import { BackgroundImageTitle } from "./sections/background-images";
import { Benefits } from "./sections/benefits";

document.addEventListener("DOMContentLoaded", () => {
    try {
        new BackgroundImageTitle();
    } catch (error) {
        console.error(error);
    }
    try {
        new Benefits();
    } catch (error) {
        console.error(error);
    }
});
