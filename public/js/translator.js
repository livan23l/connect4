class Translator {
    #$translatables;
    #$document;
    #language;
    #translations;

    /**
     * Translate to the current language all translatable elements on the page.
     * If translations have not been loaded, the method exits early.
     * 
     * @private
     * @returns {void}
     */
    #translate() {
        // Check that the translations have been loaded correctly
        if (typeof this.#translations == undefined) return;

        this.#$translatables.forEach((element) => {
            // Get the text to be translated
            const text = element.dataset.translate;

            // Verify the text can be translated
            if (text in this.#translations) {
                // Get and set the translation
                const translation = this.#translations[text];
                element.textContent = translation;
            }
        });
    }

    /**
     * Gets the current translation file and translates the corresponding
     * elements within the DOM.
     * 
     * Before performing the translation, it checks that the language being
     * translated is different from the current one.
     *
     * @private
     * @param {string} language - The language code.
     * @returns {void}
     */
    #loadAndTranslate(language) {
        // Check the language is different from the current one
        const currentLang = this.#$document.getAttribute('lang');
        if (currentLang == language) return;

        // Change the document lang attribute
        this.#$document.setAttribute('lang', language);

        // Get the current translations file path
        const translationPath = `/translations/${language}.json`;

        // Fetch the translation file
        fetch(translationPath)
            .then(response => response.json())
            .then(data => {
                this.#translations = data;
                this.#translate()
            })
            .catch(() => {
                return;
            });
    }

    /**
     * Registers an event listener for the custom 'changeLanguage' event.
     * When the event is triggered, retrieves the selected language from the
     * event details and calls the method to load and apply the translation.
     *
     * @private
     * @returns {void}
     * @listens window#changeLanguage
     * @param {CustomEvent} event - The custom event containing the language to switch to in event.detail.language.
     */
    #changeLanguageEvent() {
        window.addEventListener('changeLanguage', (event) => {
            // Get and set the language
            const language = event.detail.language;
            this.#loadAndTranslate(language);
        });
    }

    /**
     * Initializes the Translator instance and translate the page.
     */
    constructor() {
        // Get the DOM elements
        this.#$translatables = document.querySelectorAll(`[data-translate]`);
        this.#$document = document.documentElement;  // The <html></html>

        // Get the language defined in the document
        this.#language = this.#$document.dataset.lang;

        // If there was no language defined then take the browser's language
        if (this.#language == '') {
            // Only get the language (`en` in `en-US` || `es` in `es-ES`)
            this.#language = navigator.language.split('-')[0];
        }

        // Load the translation file and translate the translatables elements
        this.#loadAndTranslate(this.#language);

        // Prepare the global event to change the language
        this.#changeLanguageEvent();
    }
}

new Translator();