class Game {
    #$floatingDisc;
    #$board;
    #game;
    #gameValues;
    #gameMode;
    #gameModes;
    #players;
    #sounds;
    #block;  // Defines wheter the game is blocked

    #verifyEnd(move) {
        const [column, slot] = move;
        const color = this.#game.board[column][slot];

        const validations = {
            down: [0, -1],  // Same column, bottom slot
            left: [-1, 0],  // Previous column, same slot
            right: [1, 0],  // Next column, same slot
            // Diagonals
            upLeft: [-1, 1],  // Previous column, top slot
            upRight: [1, 1],  // Next column, top slot
            downLeft: [-1, -1],  // Previous column, bottom slot
            downRight: [1, -1],  // Next column, bottom slot
        }
        let end = false;

        // Check all the validations
        for (const key in validations) {
            const validation = validations[key];
            const [valCol, valSlot] = validation;

            // Get and save the current column and slot
            let curCol = column;
            let curSlot = slot;
            const sameDiscs = [[curCol, curSlot]];

            // Validate until get another color or reach the end of the board
            let validate = true;
            while (validate) {
                // Get the new column and slot
                curCol += valCol;
                curSlot += valSlot;

                // Check that they are valid values
                if ((curCol < 0 || curCol >= this.#game.cols) ||
                    (curSlot < 0 || curSlot >= this.#game.discsPerCol)) {
                    validate = false;
                    continue;
                }

                // Check that it is the same color
                if (this.#game.board[curCol][curSlot] != color) {
                    validate = false;
                    continue;
                }

                sameDiscs.push([curCol, curSlot]);  // Add the current position
            }

            // Verify if there are at least four of the same color
            if (sameDiscs.length >= 4) {
                end = true;
                break;
            }
        }

        return end;
    }

    #setColor(color) {
        // Define the color classes
        const colorVar = `var(--disc-${color})`;
        const colorLightVar = `var(--disc-${color}-light)`;
        const colorDarkVar = `var(--disc-${color}-dark)`;

        // Update the color classes on the board
        this.#$board.style.setProperty('--color', colorVar);
        this.#$board.style.setProperty('--color-light', colorLightVar);
        this.#$board.style.setProperty('--color-dark', colorDarkVar);
    }

    #moveFloatingDisc() {
        this.#$board.addEventListener('mousemove', (event) => {
            if (this.#block) return;

            // Check if the clicked target is a column
            let target = event.target;
            if (!target.classList.contains('board__column')) {
                // Check now the parent element
                target = target.parentElement;
                if (!target.classList.contains('board__column')) return;
            }

            // Change the 'left' property based on the column
            const col = target.dataset.column;
            const left = `calc(1rem + (0.5rem * ${col}) + (((100% - 5rem) / 7) * ${col}))`;

            this.#$floatingDisc.style.left = left;
        });
    }

    #showVersus() {

    }

    #unlock() {
        this.#block = false;
    }

    #lock() {
        this.#block = true;
    }

    #play() {
        /* Initialize the game. The board is defined according to the game
        values. The discs only hold the current number of discs per column. */
        const v = this.#gameValues.empty;
        this.#game = {
            board: [
                [v, v, v, v, v, v],
                [v, v, v, v, v, v],
                [v, v, v, v, v, v],
                [v, v, v, v, v, v],
                [v, v, v, v, v, v],
                [v, v, v, v, v, v],
                [v, v, v, v, v, v]
            ],
            discs: [0, 0, 0, 0, 0, 0, 0],
            cols: 7,
            discsPerCol: 6,
        };

        // Define game variables
        const animationDelay = (parseInt(
            getComputedStyle(document.documentElement)
                .getPropertyValue('--animation-duration')
        ) / 2) - 5;  // The animation delay for each slot minus 5 ms.
        let end = false;  // Define whether the game is over
        let color = 'red';  // Define the current disc color

        // Initialices the color
        this.#setColor(color);

        // Show the versus animation
        this.#showVersus();

        this.#$board.addEventListener('click', (event) => {
            // Check if a new move is allowed
            if (this.#block || end) return;

            // Get the clicked element
            let target = event.target;

            // Check if the clicked target is a column
            if (!target.classList.contains('board__column')) {
                // Check now the parent element
                target = target.parentElement;
                if (!target.classList.contains('board__column')) return;
            }

            // Get and check the current slot
            const column = Number(target.dataset.column);
            const slotNum = this.#game.discs[column]++;  // Increase the value
            if (slotNum >= this.#game.discsPerCol) return;

            // Get and update the current slot
            const $slot = target.querySelector(`[data-slot="${slotNum}"]`);
            $slot.classList.add('board__slot--active');

            // Update the board game
            this.#game.board[column][slotNum] = this.#gameValues[color];

            // Play the 'drop' sound after the corresponding animation delay
            setTimeout(() => {
                this.#sounds.drop.currentTime = 0;
                this.#sounds.drop.play();
            }, animationDelay * (this.#game.discsPerCol - 1 - slotNum));

            // Block the game until all animations are finished
            this.#lock();
            $slot.addEventListener('animationend', () => {
                // Remove the active class in the slot and add the corresponding
                $slot.classList.remove('board__slot--active');
                $slot.classList.add(`board__slot--${color}`);

                // Check if the game is over
                end = this.#verifyEnd([column, slotNum]);

                // Change an set the new color if the game is not over
                if (!end) {
                    color = (color == 'red') ? 'blue' : 'red';
                    this.#setColor(color);
                }

                this.#unlock();  // Unlock
            }, { once: true });
        });
    }

    #getPlayers() {
        const names = document.querySelectorAll('.player__name');
        const isAnonymous = names[0].innerText == '_Anonymous';
        if (isAnonymous) names[0].innerText = 'Anonymous';
        const images = document.querySelectorAll('.player__image');
        const players = [{
            number: 1,
            name: null,
            image: null,
        }, {
            number: 2,
            name: null,
            image: null,
        }];

        // Check if is one offline mode
        switch (this.#gameMode) {
            case this.#gameModes.robot:
                // Randomly select whether the robot will be player 1 or 2
                const number = Math.floor(Math.random() * 2) + 1;

                // Check if is anonymous and put the corresponding disc image
                if (isAnonymous) {
                    if (number == 1) {
                        images[0].src = 'img/profile/blue-disc.webp';
                    } else if (number == 2) {
                        images[0].src = 'img/profile/red-disc.webp';
                    }
                }

                // Check if the robot is player 1 and change the names and images
                if (number == 1) {
                    names[1].innerText = names[0].innerText;
                    names[0].innerText = 'robot';
                    images[1].src = images[0].src;
                    images[0].src = 'img/profile/anonymous.webp';
                }
                break;
            case this.#gameModes.local:
                break;
            case this.#gameModes.friend:
                break;
            case this.#gameModes.quick:
                break;
        }

        // Get the names and images
        for (let i = 0; i < 2; i++) {
            const name = names[i];
            const image = images[i];

            players[i].name = name.innerText;
            players[i].image = image.src;
        }

        this.#players = players;
    }

    constructor() {
        // Get DOM elements
        this.#$floatingDisc = document.querySelector('.board__floating-disc');
        this.#$board = document.querySelector('#board');

        // Define the game attributes
        this.#sounds = {
            drop: new Audio('../sounds/drop.wav'),
        };
        this.#gameValues = {
            empty: 0,
            red: 1,
            blue: 2
        };
        this.#gameModes = {
            robot: 0,
            local: 1,
            friend: 2,
            quick: 3,
        };

        // Get the current game mode
        const path = window.location.pathname.replaceAll('/', '');
        switch (path) {
            case 'playofflinerobot':
                this.#gameMode = this.#gameModes.robot;
                break;
            case 'playofflinelocal':
                this.#gameMode = this.#gameModes.local;
        }

        // Block the game
        this.#lock();

        // Get the current players
        this.#getPlayers();

        // Define game events
        this.#moveFloatingDisc();
        this.#play();
    }
}

new Game();