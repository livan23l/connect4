class Home {
    #changeLanguage() {
        // Get the DOM elements
        const $languageSection = document.querySelector('.language');

        // Click event on the section
        $languageSection.addEventListener('pointerdown', (event) => {
            let target = event.target;

            // Check if the clicked target is an option
            if (!target.classList.contains('language__option')) {
                // Check now the parent element
                target = target.parentElement;
                if (!target.classList.contains('language__option')) return;
            }

            // Check if is the selected option
            if (target.classList.contains('language__option--selected')) {
                $languageSection.classList.toggle('language--visible');
                return;
            }
        });
    }

    #arrowAnimation() {
        const $arrow = document.querySelector('#arrow');

        if (window.scrollY == 0) {
            $arrow.classList.remove('arrow--hidden');
        }

        window.addEventListener('scroll', () => {
            if (window.scrollY == 0) {
                $arrow.classList.remove('arrow--hidden');
            } else {
                $arrow.classList.add('arrow--hidden');
            }
        });
    }

    constructor() {
        this.#arrowAnimation();
        this.#changeLanguage();
    }
}

new Home();