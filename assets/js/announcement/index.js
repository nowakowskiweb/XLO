import { Filters } from "./sections/filters";
import { CustomSelector } from "../helpers/tom-select";


document.addEventListener("DOMContentLoaded", () => {
    try {
        new Filters();
    } catch (error) {
        console.error(error);
    }
    try {
        new CustomSelector('#select-categories');
    } catch (error) {
        console.error(error);
    }
    try {
        new CustomSelector('#select-sorting',
            { plugins: [],}
        );
    } catch (error) {
        console.error(error);
    }
    try {
        new CustomSelector('#select-conditions',
            { plugins: [],}
        );
    } catch (error) {
        console.error(error);
    }
});
