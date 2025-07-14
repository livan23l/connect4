class Translator {
    #$translatables;
    #$document;
    #validLanguages;
    #translations;

    /**
     * Translates the text content of a given DOM element based on its
     * `data-translate` attribute.
     * If a translation exists in the internal translations map, it updates the
     * element's text content.
     * If the translation doesn’t exist but starts with '#', it’s processed as
     * a server error message.
     *
     * @private
     * @param {HTMLElement} element - The DOM element whose text should be
     * translated.
     * @returns {void}
     */
    #translateElement(element) {
        // Get the text to be translated
        const text = element.dataset.translate;

        // Verify the text is in the translations
        if (text in this.#translations) {
            // Get and set the translation
            const translation = this.#translations[text];
            element.textContent = translation;
            return;
        }

        // Check if the translation needs to be processed
        if (!text.startsWith('#')) return;


        // Define the function to get the text in an array
        const getTextInArray = (array, fieldStarts, newField, newRestriction) => {
            let field = '';
            let restriction = '';
            let text = '';

            for (let i = 0; i < array.length; i++) {
                // Get the current subtext
                const subtext = array[i];

                // Check if the subtext is a regular text
                if (!subtext.startsWith(':')) {
                    text += `${subtext} `;
                    continue;
                }

                // Check if the subtext is a field or a restriction
                if (subtext.startsWith(fieldStarts)) {  // Field
                    field = ':' + subtext.substring(fieldStarts.length);
                    text += `${newField} `;
                } else {  // Restriction
                    restriction = subtext.substring(1);  // Remove the leading ':'
                    text += `${newRestriction} `;
                }
            }

            // Remove the final ' ' in the text
            text = text.trimEnd();

            return {text, field, restriction};
        }

        // Generate the expresion to translate
        const translationArray = text.split(' ');
        const translationData = getTextInArray(
            translationArray, '::', ':$field', ':restriction'
        );

        // Verify if the expression exists in the translations
        if (!translationData.text in this.#translations) {
            element.textContent = translationData.text;
            return;
        }

        // Get the field name translated
        const fieldTranslated = (translationData.field in this.#translations)
            ? this.#translations[translationData.field]
            : translationData.field;

        // Get the final translation
        const translation = getTextInArray(
             this.#translations[translationData.text].split(' '),
             ':$',
             fieldTranslated,
             translationData.restriction
        );

        // Add the translation
        element.textContent = translation.text;
    }

    /**
     * Gets the translation file and then executes the callback function.
     *
     * @private
     * @param {string} language - The language code ('en', 'es', etc) to search
     * for the translation file.
     * @param {Function} callback - The function to execute when the file is
     * loaded.
     * @returns {void}
     */
    #loadTranslations(language, callback = () => {}) {
        // Get the current translations file path
        const translationPath = `/translations/${language}.json`;

        // Fetch the translation file
        fetch(translationPath)
            .then(response => response.json())
            .then(data => {
                // Set the translations
                this.#translations = data;
                // Execute the callback
                callback();
            })
            .catch(() => {
                return;
            });
    }

    /**
     * Translate to the param language all translatable elements on the page.
     *
     * Before performing the translation, it checks that the language being
     * translated is different from the current one or if it's a valid language.
     * 
     * @private
     * @param {string} language - The new language code ('en', 'es', etc).
     * @returns {void}
     */
    #translate(language) {
        // Check the language is different from the current one
        const currentLang = this.#$document.getAttribute('lang');
        if (currentLang == language) return;

        // Check if the language is valid
        if (!this.#validLanguages.includes(language)) return;

        // Change the document lang attribute
        this.#$document.setAttribute('lang', language);

        // Get the current translation file
        this.#loadTranslations(language, () => {
            // Translate all the translatable element
            this.#$translatables.forEach(el => { this.#translateElement(el) });
        });
    }

    /**
     * Listens to the 'translateElement' event and translates the element based
     * on the id passed in the event detail.
     * This method works when dynamically adding text with JS that wasn't
     * originally translated when this class was instantiated.
     * 
     * @private
     * @returns {void}
     */
    #translateElementEvent() {
        window.addEventListener('translateElement', (event) => {
            // Get the element from the detail and translate
            const element = event.detail.element;

            // Check if the current element has a translation text
            if (element.dataset.translate == '') return;

            // Check if the translation file is loaded
            if (!this.#translations) {
                // Load the translations of the current language
                const language = this.#$document.getAttribute('lang');
                this.#loadTranslations(language, () => {
                    this.#translateElement(element);
                });
                return;
            }

            // Attempt to translate the element a maximum of five times
            const retryDelay = 100;
            let attemptsNumber = 5;
            const tryAgain = () => {
                if (attemptsNumber == 0) return;

                // Check the translations map
                if (this.#translations) {
                    this.#translateElement(element);
                    return;
                }

                attemptsNumber--;
                setTimeout(tryAgain, retryDelay);
            };

            // Start the first attempt
            tryAgain();
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
     */
    #changeLanguageEvent() {
        window.addEventListener('changeLanguage', (event) => {
            // Get the language
            const language = event.detail.language;

            // Change the language in the current session
            window.dispatchEvent(
                new CustomEvent('sendRequest', {
                    detail: {
                        url: '/api/change-language',
                        body: {
                            language
                        }
                    }
                })
            );

            // Set the language
            this.#translate(language);
        });
    }

    /**
     * Initializes the Translator instance and translates the page.
     */
    constructor() {
        // Get the DOM elements
        this.#$translatables = document.querySelectorAll(`[data-translate]`);
        this.#$document = document.documentElement;  // The html document

        // Initialize the translations attributes
        this.#validLanguages = ['en', 'es', 'fr', 'pt'];
        this.#translations = null;

        // Get the initial language
        //--If there is no language defined then take the browser's language
        const language = (this.#$document.dataset.lang != '')
            ? this.#$document.dataset.lang
            // Only get the language (`en` in `en-US` || `es` in `es-ES`)
            : navigator.language.split('-')[0];

        // Prepare the global events
        this.#changeLanguageEvent();
        this.#translateElementEvent();

        // Make the initial translation
        this.#translate(language);
    }
}

new Translator();