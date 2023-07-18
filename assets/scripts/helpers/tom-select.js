import TomSelect from "tom-select";

export class CustomSelector {
    selector;
    select;

    constructor(selector, config = {}) {
        this.selector = selector;
        this.select = this.createTomSelect(config)
    }

    baseConfig = {
        plugins: ['remove_button'],
    }

    createTomSelect(instanceConfig) {
        return new TomSelect(this.selector, Object.assign(this.baseConfig, instanceConfig))
    }
}
