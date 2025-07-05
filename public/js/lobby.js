class Lobby {
    #oldMenu;
    #playModes;
    #block;

    #playGameEvent() {
        const $playModes = document.querySelector('#play-modes');

        $playModes.addEventListener('pointerdown', (event) => {
            if (this.#block) return;

            // Check if the target is an option
            const target = event.target
            if (!target.classList.contains('play__option')) return;

            // Get the current clicked mode
            const mode = Number(target.dataset.mode);

            switch (mode) {
                case this.#playModes.robot:
                    window.dispatchEvent(new CustomEvent(
                        'showModal', { detail: {id: 'modal-robot'} }
                    ));
                    break;
                case this.#playModes.local:
                    console.log("Current mode: local");
                    break;
                case this.#playModes.friend:
                    // Block the game selection
                    console.log("Current mode: friend");
                    break;
                case this.#playModes.quick:
                    // this.#block = true;
                    console.log("Current mode: quick");
                    break;
            }
        });
    }

    #showDropdownEvent() {
        const menus = document.querySelector('#menus');

        const toggleMenuVisibility = (menu, dropdown) => {
            menu.classList.toggle('header__menu--active');
            dropdown.classList.toggle('header__menu-dropdown--hidden');
        }

        menus.addEventListener('pointerdown', (event) => {
            const target = event.target;

            // Get the closest menu
            const menu = target.closest('.header__menu');
            if (!menu) return;

            // Get the target dropdown menu
            const dropdown = menu.querySelector('.header__menu-dropdown');

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

    #showActiveSection() {
        // Get the current section on the path
        const path = window.location.pathname.replaceAll('/', '');
        const sectionName = (path == 'play') ? 'game' : 'store';

        // Mark the current section as active
        const $sectionA = document.querySelector(`#section-${sectionName}-anchor`);
        $sectionA.classList.add('header__section--active');
    }

    constructor() {
        // Initializes the attributes
        this.#oldMenu = null;
        this.#playModes = {
            robot: 0,
            local: 1,
            friend: 2,
            quick: 3,
        };
        this.#block = false;

        // Add the events on the lobby
        this.#showActiveSection();
        this.#showDropdownEvent();
        this.#playGameEvent();
    }
}

new Lobby();