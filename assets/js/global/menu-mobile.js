export class MenuMobile {
    buttonEventToggle;
    bodyHtml;
    navbarMenu;
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
        return this.buttonEventToggle && this.navbarMenu;
    }

    registerListeners() {
        this.buttonEventToggle.addEventListener('click', () => this.toggleMobileMenu())
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
}
