class Modal {
    /**
     * Sets up an event listener for the custom 'showModal' event.
     * - Retrieves the modal element by its ID from the event details.
     * - Disables page vertical scrolling while the modal is open.
     * - Shows the modal dialog.
     * - Adds a click event listener to close the modal when clicking on the backdrop or any element with the 'data-close-modal' attribute.
     * - Restores page scrolling and removes the event listener when the modal is closed.
     *
     * @private
     * @returns { void }
     */
    #showModalEvent() {
        window.addEventListener('showModal', (event) => {
            // Get the modal id
            const id = event.detail.id;

            // Get the current modal
            const modal = document.querySelector(`#${id}`);
            if (!modal) return;

            // Hides the page scroll
            document.documentElement.style.overflowY = 'hidden';

            // Shows the modal
            modal.showModal();

            const handleVisibility = (event) => {
                // Check if the target is the backdrop or a close modal element
                const target = event.target;
                if (target != modal && !target.hasAttribute('data-close-modal')) return;

                // Show the page scroll
                document.documentElement.style.overflowY = 'auto';

                // Close the modal and remove the listener
                modal.close();
                event.target.removeEventListener('click', handleVisibility);
            }

            // Add the click event to the modal
            modal.addEventListener('click', handleVisibility);
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