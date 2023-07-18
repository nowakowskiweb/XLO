export class Filters {
    constructor() {
        const initialElements = this.setInitialElements();
        console.log('to jest filters')
        if (!initialElements) return;
        this.registerListeners();
    }

    setInitialElements() {
        return null;
    }

    registerListeners() {}
}
