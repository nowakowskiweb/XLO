import { Filters } from "@sections-scripts/filters";
import { CustomSelector } from "@helpers-scripts/tom-select";

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
