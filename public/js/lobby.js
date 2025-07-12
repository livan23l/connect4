class Lobby {
    #oldMenu;
    #playModes;
    #block;

    /**
     * Attaches an event listener to the play mode options in the lobby.
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
     * Activates an event listener on the menu container to manage the
     * visibility of the drop-down menu.
     * 
     * @private
     * @returns {void}
     */
    #showDropdownEvent() {
        const menus = document.querySelector('#menus');

        const toggleMenuVisibility = (menu, dropdown) => {
            menu.classList.toggle('header__menu--active');
            dropdown.classList.toggle('header__dropdown--hidden');
        }

        menus.addEventListener('click', (event) => {
            const target = event.target;

            // Get the closest menu
            const menu = target.closest('.header__menu');
            if (!menu) return;

            // Get the corresponding dropdown menu
            const dropdownId = menu.dataset.dropdown;
            const dropdown = menus.querySelector(`#dropdown-${dropdownId}`);

            // Check if the current menu is active
            if (menu.classList.contains('header__menu--active')) {
                // Hides the current menu
                toggleMenuVisibility(menu, dropdown);
                this.#oldMenu = null;
                return;
            }

            // Shows the current menu
            toggleMenuVisibility(menu, dropdown);

            // Hides the old active menu if exists
            if (this.#oldMenu) {
                toggleMenuVisibility(this.#oldMenu[0], this.#oldMenu[1]);
            }

            this.#oldMenu = [menu, dropdown];
        });
    }

    /**
     * Highlights the active section in the header based on the current URL
     * path.
     * 
     * @private
     * @returns {void}
     */
    #showActiveSection() {
        // Get the current section on the path
        const path = window.location.pathname.replaceAll('/', '');
        const sectionName = (path == 'play') ? 'game' : 'store';

        // Mark the current section as active
        const $sectionA = document.querySelector(`#section-${sectionName}-anchor`);
        $sectionA.classList.add('header__section--active');
    }

    /**
     * Initializes the lobby with all the events
     * 
     * @constructor
     */
    constructor() {
        // Initializes the attributes
        this.#oldMenu = null;
        this.#playModes = {
            robot: 0,
            local: 1,
            quick: 2,
            friend: 3,
        };
        this.#block = false;

        // Add the events on the lobby
        this.#showActiveSection();
        this.#showDropdownEvent();
        this.#playGameEvent();
    }
}

new Lobby();