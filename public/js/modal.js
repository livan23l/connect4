class Modal {
    /**
     * Executes an action based on the element's `data-action` attribute.
     *
     * @private
     * @param {HTMLElement} element - The element with the action to execute.
     * @returns {void}
     */
    #makeAction(element) {
        if (!element) return;

        const action = element.dataset.action;

        switch (action) {
            case 'reload':
                location.reload();
                break;
        }
    }

    /**
     * Sets up an event listener for the custom 'showModal' event.
     * - Retrieves the modal element by its ID from the event details.
     * - Disables page vertical scrolling while the modal is open.
     * - Shows the modal dialog.
     * - Adds a click event listener to close the modal when clicking on the
     * backdrop or any element with the 'data-close-modal' attribute.
     * - Restores page scrolling and removes the event listener when the modal
     * is closed.
     *
     * @private
     * @returns {void}
     */
    #showModalEvent() {
        window.addEventListener('showModal', (event) => {
            // Get the modal id
            const id = event.detail.id;
            const escClose = event.detail.escClose ?? true;
            const backdropClose = event.detail.backdropClose ?? true;

            // Get the current modal
            const $modal = document.querySelector(`#${id}`);
            if (!$modal) return;

            // Hides the page scroll
            document.documentElement.style.overflowY = 'hidden';

            // Shows the modal
            $modal.showModal();

            // Check if the close by pressing 'esc' is canceled
            if (!escClose) {
                const cancelFunction = (event) => {
                    event.preventDefault();
                }
                $modal.addEventListener('cancel', cancelFunction);
            }

            const handleVisibility = (event) => {
                // Get the clicked target
                const target = event.target;

                // Make the action of the target
                this.#makeAction(target.closest('[data-action]'));

                // Close modal validations
                //--Check if the target is the backdrop or a close modal element
                if (target != $modal && !target.hasAttribute('data-close-modal')) return;
                //--Check the backdrop validation
                if (target == $modal && !backdropClose) return;

                // Close the modal and show the page scroll
                $modal.close();
                document.documentElement.style.overflowY = 'auto';

                // Remove the listeners
                $modal.removeEventListener('pointerdown', handleVisibility);
                if (!escClose) {
                    $modal.removeEventListener('cancel', cancelFunction);
                }
            }

            // Add the pointerdown event to the modal
            $modal.addEventListener('pointerdown', handleVisibility);
        });
    }

    /**
     * Initializes the modal class
     * 
     * @constructor
     */
    constructor() {
        // Add the events
        this.#showModalEvent();
    }
}

new Modal();