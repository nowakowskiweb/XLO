import { SwiperService } from "@helpers-scripts/swiper-service";

document.addEventListener("DOMContentLoaded", () => {
    try {
        const a = new SwiperService('.mySwiper');
        console.log(a);
    } catch (error) {
        console.error(error);
    }
});
