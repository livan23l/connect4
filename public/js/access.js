class Access {
    #section;
    #animation;

    #replaceURI(newURI = `access?section=${this.#section}&animation=${this.#animation}`) {
        history.replaceState({}, '', newURI);
    }

    #showFormWithAnimation($form) {
        // Define the animations
        const animation1 = 'show-form-1 0.75s ease-out 0.25s';
        const animation2 = 'show-form-2 0.25s ease-in';

        // Start the first animation
        $form.style.animation = animation1;

        // Set an event listener for when the animation ends
        $form.addEventListener('animationend', () => {
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
                }, { once: true });
            }, { once: true });
        }, { once: true });
    }

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
        this.#replaceURI();
    }

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