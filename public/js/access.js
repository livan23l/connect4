class Access {
    #section;
    #animation;

    /**
     * Replaces the current browser history entry's URI with a new one.
     *
     * @private
     * @param {string} newUri - The new URI to set in the history.
     * @returns {void}
     */
    #replaceURI(newURI) {
        history.replaceState({}, '', newURI);
    }

    /**
     * Animates the display of a form element using two sequential animations,
     * then applies a transition effect to reveal the form content smoothly.
     *
     * @private
     * @param {HTMLElement} $form - The form element to animate and show.
     * @returns {void}
     */
    #showFormWithAnimation($form) {
        // Define the animations
        const animation1 = 'show-form-1 0.75s ease-out 0.25s';
        const animation2 = 'show-form-2 0.25s ease-in';

        // Start the first animation
        $form.style.animation = animation1;

        // Set an event listener for when the animation ends
        $form.addEventListener('animationend', () => {
            // Block the vertical scroll
            document.documentElement.style.overflowY = 'hidden';

            // Start the second animation
            $form.style.animation = animation2;

            // Set an event listener for when the animation ends
            $form.addEventListener('animationend', () => {
                // Show the content with the transition
                $form.classList.add('form--transition');
                $form.classList.remove('form--hidden');

                // Remove the transitional properties
                $form.addEventListener('transitionend', () => {
                    $form.style.animation = '';
                    $form.classList.remove('form--transition');

                    // Unock the vertical scroll
                    document.documentElement.style.overflowY = 'auto';
                }, { once: true });
            }, { once: true });
        }, { once: true });
    }

    /**
     * Dispatches a custom event to translate all elements with the
     * 'field__error' class.
     *
     * @private
     * @returns {void}
     */
    #translateErrors() {
        // Get all the errors
        const $errors = document.querySelectorAll('.field__error');

        // Translate all the errors
        $errors.forEach(($error) => {
            // Dispatch the translation event
            window.dispatchEvent(new CustomEvent(
                'translateElement', { detail: { element: $error} }
            ));
        });
    }

    /**
     * Initializes internal URI-related properties based on URL parameters.
     *
     * @private
     * @returns {void}
     */
    #setInitialURI() {
        // Get the URI params
        const params = new URLSearchParams(window.location.search);

        // Create the function to get the correct value in the params
        const getParam = (params, name, values) => {
            // Get the param value
            const paramValue = params.get(name);

            // Set the correct value
            const value = values.includes(paramValue)
                ? paramValue
                : values[0];

            return value;
        }

        // Set the GET properties
        this.#section = getParam(params, 'section', ['signin', 'signup']);
        this.#animation = getParam(params, 'animation', ['true', 'false']);

        // Replace and set the new URI
        this.#replaceURI(
            `access?section=${this.#section}&animation=${this.#animation}`
        );
    }

    /**
     * Displays the initial form based on the current section.
     * If animation is disabled, shows the form immediately; otherwise, shows
     * it with animation.
     *
     * @private
     * @returns {void}
     */
    #showInitialForm() {
        // Get the corresponding form
        const $form = document.querySelector(`#form-${this.#section}`);

        // Check if the form will be animated or not
        if (this.#animation === 'false') {
            $form.classList.remove('form--hidden');
            return;
        }

        // Show the form with the animation
        this.#showFormWithAnimation($form);
    }

    /**
     * Adds click event listeners to elements that trigger section changes.
     * On click, prevents default navigation, animates the current form exit,
     * shows the next form with animation, and updates the browser URI.
     *
     * @private
     * @returns {void}
     */
    #changeSectionEvent() {
        const $changeElements = document.querySelectorAll('[data-action="change-section"]');

        $changeElements.forEach($element => {
            const $parentForm = $element.closest('form');
            const $nextForm = ($parentForm.id == 'form-signin')
                ? document.querySelector('#form-signup')
                : document.querySelector('#form-signin');
            const elementURI = $element.href;

            $element.addEventListener('click', (event) => {
                // Prevent the navigation
                event.preventDefault();

                // Add to the parent form the exit animation
                $parentForm.classList.add('form--exit');
                $parentForm.addEventListener('animationend', () => {
                    // Remove the form exit class and hide the form
                    $parentForm.classList.remove('form--exit');
                    $parentForm.classList.add('form--hidden');
                }, { once: true });

                // Show the next form with an animation
                this.#showFormWithAnimation($nextForm);

                // Change the URI
                this.#replaceURI(elementURI);
            });
        });
    }

    /**
     * Initializes the Access instance and set all the events
     */
    constructor() {
        // Make all the initial actions
        this.#translateErrors();
        this.#setInitialURI();
        this.#showInitialForm();

        // Set all the events
        this.#changeSectionEvent();
    }
}

new Access();