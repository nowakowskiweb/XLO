export class Benefits {
    benefitWrapperActive = 'benefits__benefit-wrapper--active';
    benefitImageActive = 'benefits__image--active';

    constructor() {
        const initialElements = this.setInitialElements();
        if (!initialElements) return;
        this.registerListeners();
    }

    setInitialElements() {
        this.benefits = document.querySelectorAll("[data-benefits-benefit-wrapper]")
        this.benefitImage = document.querySelector("[data-benefits-image]")

        return this.benefits.length !== 0 && this.benefitImage;
    }

    registerListeners() {
        window.addEventListener('scroll', () => {
            this.benefits.forEach(this.handleShowBenefit.bind(this))
            this.handleScaleImage();
        });
    }

    handleShowBenefit(el) {
        // jesli top + height elementu jest mniejszy niż height okna
        if (el.getBoundingClientRect().top + el.getBoundingClientRect().height < window.innerHeight) {
            el.classList.add(this.benefitWrapperActive)
        } else {
            el.classList.remove(this.benefitWrapperActive)
        }
    }

    handleScaleImage() {
        console.log(this.benefitImage)
        if (this.benefitImage.getBoundingClientRect().top + this.benefitImage.getBoundingClientRect().height / 2 < window.innerHeight) {
            this.benefitImage.classList.add(this.benefitImageActive)
        } else {
            this.benefitImage.classList.remove(this.benefitImageActive)
        }
    }
}
