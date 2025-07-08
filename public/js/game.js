class Board {
    #$floatingDisc;
    #$board;
    #animationDelay;
    #game;
    #gameValues;
    #sounds;
    #currentColor;
    #isOver;
    #boardLock;

    lock() {
        this.#boardLock = true;

        // Get the hover column and delete the hover class
        const hoverColumn = this.#$board.querySelector('.board__column--hover');

        if (hoverColumn) {
            hoverColumn.classList.remove('board__column--hover');
        }
    }

    unlock() {
        this.#boardLock = false;
    }

    #getMaxNumberOfConnections(move, colorValue) {
        const [column, slot] = move;

        const validations = {
            down: [[0, -1]],  // Same column, bottom slot
            horizontal: [[-1, 0], [1, 0]],  // Previous and nex column, same slot
            diagonalPositive: [[-1, -1], [1, 1]],  // The positive diagonal
            diagonalNegative: [[-1, 1], [1, -1]],  // The negative diagonal
        }

        // Initialize the maximum number and if the move has the color value
        const moveHasColor = this.#game.board[column][slot] == colorValue;
        let maxNumber = 0;

        // Check if the current move has the color value
        if (moveHasColor) maxNumber++;

        // Check all the validations
        for (const key in validations) {
            const validation = validations[key];
            const sameDiscs = [];
            if (moveHasColor) sameDiscs.push(move);

            // Check the rest of the discs in the directions of the validation
            for (const direction of validation) {
                const [valCol, valSlot] = direction;

                // Get and save the current column and slot
                let curCol = column;
                let curSlot = slot;

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

                    // Check if the disc has not the same color value
                    if (this.#game.board[curCol][curSlot] != colorValue) {
                        validate = false;
                        continue;
                    }

                    // Add the current position to the same disc array
                    sameDiscs.push([curCol, curSlot]);
                }
            }

            // Check if the sameDiscs array has more elements than the max number
            maxNumber = Math.max(maxNumber, sameDiscs.length);
        }

        return maxNumber;
    }

    #verifyEnd(move) {
        const [column, slot] = move;
        const value = this.#game.board[column][slot];

        const connections = this.#getMaxNumberOfConnections(move, value);

        if (connections >= 4) {
            this.#isOver = true;
            return;
        }

        // Check if is a draw
        for (const discs of this.#game.discs) {
            if (discs < this.#game.discsPerCol) return;
        }

        this.#isOver = true;
    }

    #changeColor() {
        // Change the current color
        if (this.#currentColor == null) {
            this.#currentColor = 'red';
        }
        else {
            this.#currentColor = (this.#currentColor == 'red') ? 'blue' : 'red';
        }

        // Define the color classes
        const colorVar = `var(--disc-${this.#currentColor})`;
        const colorLightVar = `var(--disc-${this.#currentColor}-light)`;
        const colorDarkVar = `var(--disc-${this.#currentColor}-dark)`;

        // Update the color classes on the board
        this.#$board.style.setProperty('--color', colorVar);
        this.#$board.style.setProperty('--color-light', colorLightVar);
        this.#$board.style.setProperty('--color-dark', colorDarkVar);
    }

    #dispatchMoveEvent(move) {
        const oldColor = (this.#currentColor == 'red') ? 'blue' : 'red';

        window.dispatchEvent(new CustomEvent(
            'playEnd',
            {
                detail: {
                    color: oldColor,
                    end: this.#isOver,
                    board: this.#game.board,
                    move,
                }
            }
        ));
    }

    #showPlay(column) {
        // Get the amount of discs and check there are empty slots in the column
        const disc = this.#game.discs[column];
        if (disc >= this.#game.discsPerCol) return false;

        // Increase the discs number in the current column
        this.#game.discs[column]++;

        // Get and update the current slot in the current column
        const $column = this.#$board.querySelector(`[data-column="${column}"]`);
        const $slot = $column.querySelector(`[data-slot="${disc}"]`);
        $slot.classList.add('board__slot--active');

        // Update the board game with the value of the current color
        this.#game.board[column][disc] = this.#gameValues[this.#currentColor];

        // Play the 'drop' sound after the corresponding animation delay
        setTimeout(() => {
            this.#sounds.drop.currentTime = 0;
            this.#sounds.drop.play();
        }, this.#animationDelay * (this.#game.discsPerCol - 1 - disc));

        // Define the behavior for when the animation ends
        $slot.addEventListener('animationend', () => {
            // Remove the active class in the slot and add the corresponding
            $slot.classList.remove('board__slot--active');
            $slot.classList.add(`board__slot--${this.#currentColor}`);

            // Check if the game is over
            const move = [column, disc];
            this.#verifyEnd(move);

            // Change the color and dispatch the play event
            this.#changeColor();
            this.#dispatchMoveEvent(move);
        }, { once: true });

        // The defined behavior is to lock the board after each move
        this.lock();

        return true;
    }

    #moveFloatingDiscEvent() {
        this.#$board.addEventListener('mousemove', (event) => {
            if (this.#boardLock || this.#isOver) {
                // Remove all the hover class in the column elements
                const hoverElements = this.#$board.querySelectorAll('.board__column--hover');
                hoverElements.forEach((element) => {
                    element.classList.remove('board__column--hover');
                });
                return;
            };

            // Check if the clicked target is a column
            let target = event.target;
            if (!target.classList.contains('board__column')) {
                // Check now the parent element
                target = target.parentElement;
                if (!target.classList.contains('board__column')) return;
            }

            // Add the hover class to the column
            target.classList.add('board__column--hover');

            // Change the 'left' property based on the column
            const col = target.dataset.column;
            const left = `calc(1rem + (0.5rem * ${col}) + (((100% - 5rem) / 7) * ${col}))`;

            this.#$floatingDisc.style.left = left;
        });
    }

    #boardClickEvent() {
        this.#$board.addEventListener('click', (event) => {
            // Check if a new move is allowed
            if (this.#boardLock || this.#isOver) return;

            // Get the clicked element
            let target = event.target;

            // Check if the clicked target is a column
            if (!target.classList.contains('board__column')) {
                // Check now the parent element
                target = target.parentElement;
                if (!target.classList.contains('board__column')) return;
            }

            // Show the current play
            const column = Number(target.dataset.column);
            this.#showPlay(column);
        });
    }

    getRandomMove() {
        if (this.#isOver) return [-1, -1];

        const move = [];
        while (move.length == 0) {
            const column = Math.floor(Math.random() * this.#game.cols);
            const slot = this.#game.discs[column];

            if (slot < this.#game.discsPerCol) {
                move.push(column, slot);
            }
        }

        return move;
    }

    getBestMove(color) {
        const oppositeColor = {
            red: this.#gameValues.blue,
            blue: this.#gameValues.red
        };

        const colorValue = this.#gameValues[color];
        const oppositeColorValue = oppositeColor[color];

        // Define a function to get the bigger connection with one color
        const getBestPlay = (opponent = false) => {
            const colorVal = opponent ? oppositeColorValue : colorValue;
            const oppositeColorVal = opponent ? colorValue : oppositeColorValue;
            let biggerValue = 0;
            const connections = [];
            const setBestMove = (value, move) => {
                biggerValue = value;

                connections.length = 0;
                connections.push({
                    value: value,
                    position: move
                });
            };

            for (let col = 0; col < this.#game.cols; col++) {
                // Check if the current column has no empty slots
                const disc = this.#game.discs[col];
                if (disc >= this.#game.discsPerCol) continue;

                // Get the current number of connections
                const move = [col, disc];
                let value = this.#getMaxNumberOfConnections(
                    move, colorVal
                ) + 1;  // Add the current disc

                // Finish if the current value is lower
                if (value < Math.floor(biggerValue)) continue;

                // Check if the player wins with this move
                if (value >= 4) {
                    setBestMove(value, move);
                    break;
                }

                // Slightly increase the value if it is closer to the center
                //--Check the column
                if (col == 1 || col == 5) value += 0.1;
                else if (col == 2 || col == 4) value += 0.2;
                else if (col == 3) value += 0.3;
                //--Check the disc
                if (disc == 1 || disc == 4) value += 0.1;
                else if (disc == 2 || disc == 3) value += 0.2;

                // Round to only one decimal in the value
                value = Math.floor(value * 10) / 10;

                // Get the next connections depending on who the opponent is
                const nextMove = [col, disc + 1];
                const nextIsValid = (disc + 1 < this.#game.discsPerCol);
                const nextColor = opponent ? colorVal : oppositeColorVal;

                const nextValue = nextIsValid
                        ? this.#getMaxNumberOfConnections(nextMove, nextColor) + 1
                        : 1;

                // Check if the opponent wins with the current move
                if (nextValue >= 4) value = 0;  // The current play is bad

                // Now check if the resulting value is bigger or equal
                if (value > biggerValue) {
                    setBestMove(value, move);
                } else if (value == biggerValue) {
                    connections.push({
                        value: value,
                        position: move
                    });
                }
            }

            // Return one of the bigger connections selected randomly
            return connections[Math.floor(Math.random() * connections.length)];
        };

        // Check all the columns with the color to search a win
        const bestPlay = getBestPlay();
        if (bestPlay.value >= 4) return bestPlay.position;

        // Check if there is a winning play for the opponent
        const opBestPlay = getBestPlay(true);
        if (opBestPlay.value >= 4) return opBestPlay.position;

        // Return the position with the bigger value
        if (bestPlay.value > opBestPlay.value) {
            return bestPlay.position;
        } else if (bestPlay.value < opBestPlay.value) {
            return opBestPlay.position;
        } else {  // If both are equal choose randomly one
            if (Math.random() > 0.5) return bestPlay.position;
            else return opBestPlay.position;
        }
    }

    play(move) {
        if (this.#isOver) return false;

        // Check if is a valid move
        const [column, slot] = move;
        if (this.#game.discs[column] != slot) return false;

        // Make the move
        return this.#showPlay(column);
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
        this.#animationDelay = (parseInt(
            getComputedStyle(document.documentElement)
                .getPropertyValue('--animation-duration')
        ) / 2) - 5;  // The animation delay for each slot minus 5 ms.
        this.#isOver = false;
        this.lock(); // The board is locked until execute 'play()'

        /* Initialize the game. The board is defined according to the game
        values. The discs only hold the current number of discs per column. */
        const e = this.#gameValues.empty;
        this.#game = {
            board: [
                [e, e, e, e, e, e],
                [e, e, e, e, e, e],
                [e, e, e, e, e, e],
                [e, e, e, e, e, e],
                [e, e, e, e, e, e],
                [e, e, e, e, e, e],
                [e, e, e, e, e, e]
            ],
            discs: [0, 0, 0, 0, 0, 0, 0],
            cols: 7,
            discsPerCol: 6,
        };
        this.#currentColor = null;
        this.#changeColor();

        // Set the game events
        this.#moveFloatingDiscEvent();
        this.#boardClickEvent();
    }
}

class Game {
    #gameMode;
    #gameModes;
    #players;
    #hostNumber;
    #hostColor;
    #sounds;
    #board;

    #robotGame(detail, difficulty) {
        const { color, end } = detail;
        const oppositeColor = {
            red: 'blue',
            blue: 'red'
        };
        const precisionByDifficulty = {
            easy: 0.2,
            normal: 0.7,
            hard: 1
        };

        if (end) {
            console.log(detail.board);
            return
        };

        // Check if the play was made by the host or the robot
        if (this.#hostColor == color) {  // Host
            // Get the move according to the difficulty
            if (Math.random() <= precisionByDifficulty[difficulty]) {
                // Make the best move
                this.#board.play(this.#board.getBestMove(oppositeColor[color]));
            } else {
                // Make a random move
                this.#board.play(this.#board.getRandomMove());
            }
        } else {  // Robot
            this.#board.unlock();
        }
    }

    #manageGame() {
        // Game preparation
        if (this.#hostNumber == 1) this.#board.unlock();
        let difficulty = null;

        switch (this.#gameMode) {
            case this.#gameModes.robot:
                // Check if the robot is the first player and make a random move
                if (this.#hostNumber == 2) {
                    this.#board.play(this.#board.getRandomMove());
                }

                // Get the difficulty in the params
                const params = new URLSearchParams(window.location.search);
                const difficulties = ['easy', 'normal', 'hard'];
                const paramDifficulty = params.get('difficulty');

                // Set the current difficulty
                difficulty = difficulties.includes(paramDifficulty)
                    ? paramDifficulty
                    : 'normal';

                // Set the difficulty in the DOM
                const $difficulty = document.querySelector('#difficulty');
                const $difficultyIcon = $difficulty.querySelector('#difficulty-icon');

                // Get the corresponding icon
                const $templateIconClone = document.querySelector(
                    `#template-icon-${difficulty}`
                ).content.cloneNode(true);

                // Append the icon clone to the DOM
                $difficultyIcon.appendChild($templateIconClone);

                // Show the difficulty
                $difficulty.classList.remove('game__difficulty--hidden');
                $difficulty.classList.add(`game__difficulty--${difficulty}`);
                $difficulty.setAttribute('data-difficulty', difficulty);
                break;
            case this.#gameModes.robot:
                break;
            case this.#gameModes.robot:
                break;
            case this.#gameModes.robot:
                break;
        }

        // The event when each player ends his play
        window.addEventListener('playEnd', (event) => {
            const detail = event.detail;

            switch (this.#gameMode) {
                case this.#gameModes.robot:
                    this.#robotGame(detail, difficulty);
                    break;
                case this.#gameModes.robot:
                    break;
                case this.#gameModes.robot:
                    break;
                case this.#gameModes.robot:
                    break;
            }
        });
    }

    #getAndSetPlayers() {
        // Get both players with their names and images
        //--Player 1
        const $player1 = document.querySelector('[data-player="1"]');
        const $player1Name = $player1.querySelector('.player__name');
        const $player1Image = $player1.querySelector('.player__image');
        //--Player 2
        const $player2 = document.querySelector('[data-player="2"]');
        const $player2Name = $player2.querySelector('.player__name');
        const $player2Image = $player2.querySelector('.player__image');

        this.#hostNumber = 1;

        // Check if the current user is '_Anonymous'
        const isAnonymous = $player1Name.innerText == '_Anonymous';
        if (isAnonymous) $player1Name.innerText = 'Anonymous';

        // Check if it's an offline mode to change or correct the names
        switch (this.#gameMode) {
            case this.#gameModes.robot:
                // Randomly select whether the robot will be player 1 or 2
                const robot = Math.floor(Math.random() * 2) + 1;

                // Check if the robot is player 1 and change the names and images
                if (robot == 1) {
                    // Names
                    $player2Name.innerText = $player1Name.innerText;
                    $player1Name.innerText = 'Robot';

                    // Images
                    $player2Image.src = $player1Image.src;
                    if (isAnonymous) {
                        $player2Image.src = 'img/profile/blue-disc.webp';
                    }
                    $player1Image.src = 'img/profile/red-robot.webp';

                    // Change the current user
                    this.#hostNumber = 2;
                }
                break;

            case this.#gameModes.local:
                break;
        }

        // Create an array for both players
        this.#players = [{
            number: 1,
            element: $player1.cloneNode(true),
            name: $player1Name,
            image: $player1Image,
        }, {
            number: 2,
            element: $player2.cloneNode(true),
            name: $player2Name,
            image: $player2Image,
        }];

        // Define the host player color
        this.#hostColor = (this.#hostNumber == 1) ? 'red' : 'blue';
    }

    #showVersus() {
        // Get and set the players
        this.#getAndSetPlayers();

        // Get the versus modal
        const $versusModal = document.querySelector('#modal-versus');
        const $versusPlayers = $versusModal.querySelector('.versus__players');

        // Put both players in the versus modal
        $versusPlayers.prepend(this.#players[0].element);
        $versusPlayers.append(this.#players[1].element);

        // Show the modal
        $versusModal.showModal();

        // Prevent that the user close the modal
        const preventCloseByEsc = (event) => {
            event.preventDefault();
        }

        // Add the event to the emodal
        $versusModal.addEventListener('cancel', preventCloseByEsc);

        setTimeout(() => {
            $versusModal.classList.add('versus--close');
            this.#sounds.versus.play();

            $versusModal.addEventListener('animationend', () => {
                // Remove the listener from the modal
                $versusModal.removeEventListener('cancel', preventCloseByEsc);
                // Close the modal and manage the game
                $versusModal.close();
                this.#manageGame();
            }, { once: true });
        }, 1200);
    }

    constructor() {
        // Set the game attributes
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

        // Get the board
        this.#board = new Board();

        // Get the sounds
        this.#sounds = {
            versus: new Audio('../sounds/versus.wav'),
        };

        // Show the versus
        this.#showVersus();
    }
}

new Game();