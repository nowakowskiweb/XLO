import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';
export class SwiperService {

    baseConfig = {
        modules: [Pagination],
        direction: 'horizontal',
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
            dynamicBullets: true,
        },
    }

    constructor(selector, config = {}) {
        this.selector = selector;
        this.config = Object.assign(this.baseConfig, config)
        this.swiper = this.createSwiper()

    }

    createSwiper() {
        return new Swiper(this.selector, this.config)
    }
}
