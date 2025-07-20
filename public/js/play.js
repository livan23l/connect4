class Play {
    #playModes;
    #block;

    /**
     * Attaches an event listener to the play mode options in the play lobby.
     * Handles user selection of different play modes (robot, local, friend,
     * quick) and dispatches corresponding actions or events based on the
     * selected mode.
     * 
     * Prevents interaction if the selection is currently blocked.
     *
     * @private
     * @returns {void}
     */
    #playGameEvent() {
        const $playModes = document.querySelector('#play-modes');

        $playModes.addEventListener('click', (event) => {
            if (this.#block) return;

            // Check if the target is an option
            const target = event.target
            if (!target.classList.contains('play__option')) return;

            // Get the current clicked mode
            const mode = Number(target.dataset.mode);

            switch (mode) {
                case this.#playModes.robot:
                    window.dispatchEvent(new CustomEvent(
                        'showModal', { detail: { id: 'modal-robot' } }
                    ));
                    break;
                case this.#playModes.quick:
                    // this.#block = true;
                    console.log("Current mode: quick");
                    break;
                case this.#playModes.friend:
                    // Block the game selection
                    console.log("Current mode: friend");
                    break;
            }
        });
    }

    /**
     * Initializes the play lobby with all the events
     * 
     * @constructor
     */
    constructor() {
        // Initializes the attributes
        this.#playModes = {
            robot: 0,
            local: 1,
            quick: 2,
            friend: 3,
        };
        this.#block = false;

        // Add the events on the play lobby
        this.#playGameEvent();
    }
}

new Play();