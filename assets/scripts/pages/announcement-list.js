import { Filters } from "@sections-scripts/filters";
import { CustomSelector } from "@helpers-scripts/tom-select";

document.addEventListener("DOMContentLoaded", () => {
    try {
        new Filters();
    } catch (error) {
        console.error(error);
    }
    try {
        new CustomSelector('#category',
            { plugins: [],}
            );
    } catch (error) {
        console.error(error);
    }
    try {
        new CustomSelector('#sorting',
            { plugins: [],}
        );
    } catch (error) {
        console.error(error);
    }
    try {
        new CustomSelector('#conditionType');
    } catch (error) {
        console.error(error);
    }
});
