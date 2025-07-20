class Hmenus {
    #oldDropdown;

    /**
     * Activates an event listener on the menu container to manage the
     * visibility of the drop-down menu.
     * 
     * @private
     * @returns {void}
     */
    #showDropdownEvent() {
        const dropdowns = document.querySelector('#dropdowns');

        const toggleDropdownVisibility = (dropdown, dropcontent) => {
            dropdown.classList.toggle('hmenus__dropdown--active');
            dropcontent.classList.toggle('hmenus__dropcontent--hidden');
        }

        dropdowns.addEventListener('click', (event) => {
            const target = event.target;

            // Get the closest dropdown
            const dropdown = target.closest('.hmenus__dropdown');
            if (!dropdown) return;

            // Get the corresponding dropcontent
            const dropcontentId = dropdown.dataset.dropcontent;
            const dropcontent = dropdowns.querySelector(`#dropcontent-${dropcontentId}`);

            // Check if the current dropdown is active
            if (dropdown.classList.contains('hmenus__dropdown--active')) {
                // Hides the current dropdown
                toggleDropdownVisibility(dropdown, dropcontent);
                this.#oldDropdown = null;
                return;
            }

            // Shows the current dropdown
            toggleDropdownVisibility(dropdown, dropcontent);

            // Hides the old active menu if exists
            if (this.#oldDropdown) {
                toggleDropdownVisibility(this.#oldDropdown[0], this.#oldDropdown[1]);
            }

            this.#oldDropdown = [dropdown, dropcontent];
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
        if (path != 'play' && path != 'store') return;

        // Mark the current section as active
        const $sectionA = document.querySelector(`#section-${path}-anchor`);
        $sectionA.classList.add('hmenus__section--active');
    }

    constructor() {
        // Initializes the attributes
        this.#oldDropdown = null;

        // Start the events
        this.#showActiveSection();
        this.#showDropdownEvent();
    }
}

new Hmenus();