import { BackgroundImageTitle } from "@sections-scripts/background-image";
import { Benefits } from "@sections-scripts/benefits";

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
console.log('AHA?!?!?!')