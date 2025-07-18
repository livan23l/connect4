class Settings {
    /**
     * Dispatches a custom event to translate all elements with the
     * 'form__error' class.
     *
     * @private
     * @returns {void}
     */
    #translateErrors() {
        // Get all the errors
        const $errors = document.querySelectorAll('.form__error');

        // Translate all the errors
        $errors.forEach(($error) => {
            // Dispatch the translation event
            window.dispatchEvent(new CustomEvent(
                'translateElement', { detail: { element: $error} }
            ));
        });
    }

    /**
     * Opens the confirmation modal if the URI has ?modal=true
     * 
     * @private
     * @returns {void}
     */
    #openModalWithUri() {
        // Get the URI params
        const params = new URLSearchParams(window.location.search);

        // Set the GET property
        const isOpen = params.get('modal') ?? 'false';

        // Show the confirmation modal if the params has 'true'
        if (isOpen === 'true') {
            window.dispatchEvent(new CustomEvent(
                'showModal',
                { detail: { id: 'modal-confirmation' } }
            ));
        }
    }

    /**
     * Handles the language selection UI and logic.
     * 
     * - Mark as selected the current language
     * - Listens for a change interaction in the language select.
     * - Dispatches a 'changeLanguage' custom event with the selected language.
     * 
     * @private
     * @returns {void}
     */
    #languageEvent() {
        // Get the DOM variables
        const $select = document.querySelector('#select-languages');
        const curLang = document.documentElement.getAttribute('lang');

        // Set the current language as selected
        const $currentOption = $select.querySelector(`[value="${curLang}"]`);
        if ($currentOption) {
            $currentOption.selected = true;
        }

        // Add an event listener whent the language changes
        $select.addEventListener('change', (event) => {
            // Get the clicked target
            const $target = event.target;

            // Get the clicked value
            const language = $target.value;

            // Change the language
            window.dispatchEvent(
                new CustomEvent('changeLanguage', { detail: { language } })
            );
        });
    }

    /**
     * Prevents the user from accidentally deleting their account and displays
     * a confirmation modal
     * 
     * @private
     * @returns {void}
     */
    #preventDeletionEvent() {
        // Get the confirmation form if exists
        const $form = document.querySelector('#form-delete');
        if (!$form) return;

        // Get the modal
        const $modal = document.querySelector('#modal-confirmation');

        // Add the event when the user submit the form to prevent the deletion
        $form.addEventListener('submit', (event) => {
            // Prevent the submission
            event.preventDefault();

            // Replace the URI
            history.replaceState({}, '', 'settings?modal=true');

            // Show the confirmation modal
            window.dispatchEvent(new CustomEvent(
                'showModal',
                { detail: { id: 'modal-confirmation' } }
            ))
        });

        // Add the event when the modal is closed to update the URI
        $modal.addEventListener('close', () => {
             history.replaceState({}, '', 'settings?modal=false');
        });
    }

    /**
     * Initializes the Settings instance and set all the events
     */
    constructor() {
        // Translate the current errors
        this.#translateErrors();

        // Open the confirmation modal depending on the URI
        this.#openModalWithUri();

        // Set the settings events
        this.#preventDeletionEvent();
        this.#languageEvent();
    }
}

new Settings();