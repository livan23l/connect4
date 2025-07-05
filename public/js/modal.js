class Modal {
    #showModalEvent() {
        window.addEventListener('showModal', (event) => {
            // Get the modal id
            const id = event.detail.id;

            // Get the current modal
            const modal = document.querySelector(`#${id}`);
            if (!modal) return;

            // Shows the modal
            modal.showModal();

            const handleVisibility = (event) => {
                // Check if the target is the backdrop or a close button
                const target = event.target;
                console.log(target);
                if (target != modal && !target.hasAttribute('data-close-btn')) return;

                // Close the modal and remove the listener
                modal.close();
                event.target.removeEventListener('click', handleVisibility);
            }

            // Add the click event to the modal
            modal.addEventListener('click', handleVisibility);
        });
    }

    constructor() {
        // Add the events
        this.#showModalEvent();
    }
}

new Modal();