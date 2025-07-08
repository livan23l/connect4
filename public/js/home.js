class Home {
    /**
     * Handles the language selection UI and logic.
     * 
     * - Highlights and repositions the currently selected language option.
     * - Listens for user interaction to change the language.
     * - Dispatches a 'changeLanguage' custom event with the selected language.
     * - Updates the UI to reflect the new selection.
     * 
     * @private
     * @returns { void }
     */
    #changeLanguageEvent() {
        // Get the DOM elements
        const $languageSection = document.querySelector('.language');
        const selectedClass = 'language__option--selected';

        // Select and reposition the current language
        const lang = document.documentElement.getAttribute('lang');
        const reposition = (selected) => {
            selected.classList.add(selectedClass);  // Add the selected class
            $languageSection.prepend(selected);  // Move the element
        };
        reposition($languageSection.querySelector(`[data-language="${lang}"]`));

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
            if (target.classList.contains(selectedClass)) {
                // Place the scroll bar at the end
                $languageSection.scrollTop = $languageSection.scrollHeight;
                // Hides the language section
                $languageSection.classList.toggle('language--visible');
                return;
            }

            // Change the language
            const language = target.dataset.language;
            window.dispatchEvent(
                new CustomEvent('changeLanguage', { detail: { language } })
            );

            // Change the selected language
            //--Remove the last selected option class
            const $selected = $languageSection.querySelector('.' + selectedClass);
            $selected.classList.remove(selectedClass);

            //--Reposition the new selected option
            reposition(target);
        });
    }

    /**
     * Toggles the visibility of the arrow element based on the window's
     * scroll position.
     * 
     * @private
     * @returns { void }
     */
    #toggleArrowVissibility() {
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

    /**
     * Initializes the home with all the events
     * 
     * @constructor
     */
    constructor() {
        this.#toggleArrowVissibility();
        this.#changeLanguageEvent();
    }
}

new Home();