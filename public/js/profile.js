class Profile {
    #$options;

    #editProfile() {
        // Open the editing modal
        window.dispatchEvent(new CustomEvent(
            'showModal',
            { detail: { id: "modal-edit-profile" }}
        ));
    }

    #addFriend() {

    }

    #cancelRequest() {

    }

    #deleteFriend() {

    }

    #clickOptionEvent() {
        const optionsEnum = {
            editProfile: 1,
            addFriend: 2,
            cancelRequest: 3,
            deleteFriend: 4,
        };

        this.#$options.addEventListener('click', (event) => {
            // Get the clicked option if exists
            const $option = event.target.closest('.avatar__option');
            if (!$option) return;

            // Make the action according to the option number
            switch(Number($option.dataset.option)) {
                case optionsEnum.editProfile:
                    this.#editProfile();
                    break;
                case optionsEnum.addFriend:
                    this.#addFriend();
                    break;
                case optionsEnum.cancelRequest:
                    this.#cancelRequest();
                    break;
                case optionsEnum.deleteFriend:
                    this.#deleteFriend();
                    break;
            }
        });
    }

    constructor() {
        // Get the DOM elements
        this.#$options = document.querySelector('#options');

        // Unlock the current profile option
        const optionNum = this.#$options.dataset.currentOption;
        const $option = this.#$options.querySelector(`[data-option="${optionNum}"]`);
        $option.classList.remove('avatar__option--hidden');

        // Start the event when the user clicks one option
        this.#clickOptionEvent();
    }
}

new Profile();