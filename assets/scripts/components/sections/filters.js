export class Filters {
    constructor() {
        const initialElements = this.setInitialElements();
        if (!initialElements) return;
        this.registerListeners();
    }

    setInitialElements() {
        return null;
    }

    registerListeners() {}
}
