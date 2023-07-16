import '../../styles/homepage/index.scss';

import { BackgroundImageTitle } from "../homepage/scripts/background-images";
import { Benefits } from "../homepage/scripts/benefits";

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
