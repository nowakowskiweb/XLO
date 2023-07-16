import {wordFlick} from '../../helpers/word-flick'


export class BackgroundImageTitle {

    backgroundImageTitle;
    titles = [
        'Hi i like HTML', 'I also like css', 'Lorem ipsum dolor sit amet', ' consectetur adipiscing elit', 'sed do eiusmod tempor incididunt'
    ]

    constructor() {
        const initialElements = this.setInitialElements();
        if (!initialElements) return;
        wordFlick(this.titles,70,this.backgroundImageTitle)
    }

    setInitialElements() {
        this.backgroundImageTitle = document.querySelector("[data-background-image-title]")

        return this.backgroundImageTitle
    }


}
