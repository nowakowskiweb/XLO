export class MenuMobile {
    buttonEventToggle;
    bodyHtml;
    navbarMenu;
    backgroundImageSection;
    navbarMenuActive = 'header__navbar--active';
    scrollDisabled = 'scroll-disabled';

    constructor() {
        const initialElements = this.setInitialElements();
        if (!initialElements) return;
        this.registerListeners();
    }

    setInitialElements() {
        this.buttonEventToggle = document.querySelector("[data-navbar-button]")
        this.navbarMenu = document.querySelector("[data-navbar-menu]")
        this.backgroundImageSection = document.querySelector("[data-background-image]")

        return this.buttonEventToggle && this.navbarMenu && this.backgroundImageSection;
    }

    registerListeners() {
        this.buttonEventToggle.addEventListener('click', (event) => {
            const target = event.target;
            if (target.closest('[data-navbar-button]')) {
                this.toggleMobileMenu();
            }
        });
        window.addEventListener('scroll', (e) => this.changeMenuStyles(e))
    }

    toggleMobileMenu() {
        if (this.navbarMenu.classList.contains(this.navbarMenuActive)) {
            this.navbarMenu.classList.remove(this.navbarMenuActive);
            document.body.classList.remove(this.scrollDisabled);
        } else {
            this.navbarMenu.classList.add(this.navbarMenuActive);
            document.body.classList.add(this.scrollDisabled)
        }
    }

    changeMenuStyles(e) {
        const scrollPosition = document.documentElement.scrollTop;
        const navbarMenuHeight = this.navbarMenu.getBoundingClientRect().height;
        const backgroundImageHeight = this.backgroundImageSection.getBoundingClientRect().height;

        if(scrollPosition + navbarMenuHeight > backgroundImageHeight) {
            const backgroundHeight = Math.min(scrollPosition + navbarMenuHeight - backgroundImageHeight, 74);

            this.navbarMenu.style.background = `linear-gradient(to top, red ${backgroundHeight}px, transparent ${backgroundHeight}px)`;
        } else {
            this.navbarMenu.style.background = ''
        }
    }
}
