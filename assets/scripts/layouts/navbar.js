export class Navbar {
    buttonEventToggle;
    bodyHtml;
    navbarMenu;
    backgroundImageSection;
    navbarMenuActive = 'navbar--active';
    scrollDisabled = 'scroll-disabled';
    windowLastScroll = window.scrollY
    navbarMenuHeight;

    constructor() {
        const initialElements = this.setInitialElements();

        if (!initialElements) return;
        this.registerListeners();
    }

    setInitialElements() {
        this.buttonEventToggle = document.querySelector("[data-navbar-button]")
        this.navbarMenu = document.querySelector("[data-navbar-menu]")
        this.navbarMenuHeight = this.navbarMenu?.getBoundingClientRect()?.height;
        this.backgroundImageSection = document.querySelector("[data-background-image]")

        return this.buttonEventToggle && this.navbarMenu;
    }

    registerListeners() {
        this.buttonEventToggle.addEventListener('click', (event) => this.toggleMobileMenu(event.target));
        window.addEventListener('scroll', () => this.handleScrollMenu());

        if(this.backgroundImageSection) window.addEventListener('scroll',() => this.handleMenuStyles())
    }

    toggleMobileMenu(target) {
        if(!target.closest('[data-navbar-button]')) return;
        if (this.navbarMenu.classList.contains(this.navbarMenuActive)) {
            this.navbarMenu.classList.remove(this.navbarMenuActive);
            document.body.classList.remove(this.scrollDisabled);
        } else {
            this.navbarMenu.classList.add(this.navbarMenuActive);
            document.body.classList.add(this.scrollDisabled)
        }
    }

    handleMenuStyles() {
        const scrollPosition = document.documentElement.scrollTop;
        const backgroundImageHeight = this.backgroundImageSection.getBoundingClientRect().height;

        if(scrollPosition + this.navbarMenuHeight > backgroundImageHeight) {
            const backgroundHeight = Math.min(scrollPosition + this.navbarMenuHeight - backgroundImageHeight, 74);

            this.navbarMenu.style.background = `linear-gradient(to top, #EAC6B7 ${backgroundHeight}px, transparent ${backgroundHeight}px)`;
        } else {
            this.navbarMenu.style.background = ''
        }
    }

    handleScrollMenu = () => {
        const headerStyle = getComputedStyle(this.navbarMenu)
        const top = parseInt(headerStyle.top.replace('px', ''))
        const newScroll = window.scrollY

        const delta = newScroll - this.windowLastScroll

        if (delta > 0 ) {
            let newTop = Math.max(top - delta / 5, -this.navbarMenuHeight)
            this.navbarMenu.style.top = `${newTop}px`

        } else {
            let newTop = Math.min(top - delta / 1.5, 0);
            this.navbarMenu.style.top = `${newTop}px`
        }

        this.windowLastScroll = newScroll
    }

}
