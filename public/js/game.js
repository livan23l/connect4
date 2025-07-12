class Board {
    #$board;
    #animationDelay;
    #game;
    #boardValues;
    #sounds;
    #currentColor;
    #gameOver;
    #boardLock;

    /**
     * Locks the game to prevent the user from performing any actions. Also,
     * removes all columns with the "hover" class to hide the floating disc.
     * 
     * @returns {void}
     */
    lock() {
        this.#boardLock = true;

        // Get all the hover columns and remove their hover class
        const hoverColumns = this.#$board.querySelectorAll('.board__column--hover');

        hoverColumns.forEach((column) => {
            column.classList.remove('board__column--hover');
        });
    }

    /**
     * Unlocks the game to allow the user to make a move or view the floating
     * disc by hovering over the board.
     * 
     * @returns {void}
     */
    unlock() {
        this.#boardLock = false;
    }

    /**
     * Calculates the maximum number of connected discs of the same color from a
     * given move in all possible directions (vertical, horizontal, and both
     * diagonals) on the board.
     *
     * @param {[number, number]} move - The [column, slot] position of the
     * current move.
     * @param {string} colorValue - The value representing the player's
     * disc color to check for connections.
     * @returns {{number: number, direction: string|null, positions: array}} An
     * object containing the maximum number of connected discs (`number`), the
     * direction (`direction`) where this maximum occurs and all the discs
     * positions (positions).
     */
    #getMaxNumberOfConnections(move, colorValue) {
        // Get the column and slot of the current move
        const [column, slot] = move;

        const validations = {
            down: [[0, -1]],  // Same column, bottom slot
            horizontal: [[-1, 0], [1, 0]],  // Previous and nex column, same slot
            diagonalPositive: [[-1, -1], [1, 1]],  // The positive diagonal
            diagonalNegative: [[-1, 1], [1, -1]],  // The negative diagonal
        }

        // Initialize the object that will contain the maximum connection
        const connection = {
            number: 0,
            direction: null,
            positions: []
        };

        // Check if the move has the color value to increase maximum number
        const moveHasColor = this.#game.board[column][slot] == colorValue;
        if (moveHasColor) connection.number++;

        // Check all the validations
        for (const direction in validations) {
            const validation = validations[direction];

            // Initialize the array that will have the discs with the same value
            const sameDiscs = [];
            if (moveHasColor) sameDiscs.push(move);

            // Check all the discs in the current direction
            for (const movement of validation) {
                const [movCol, movSlot] = movement;
                let [curCol, curSlot] = [column, slot];

                // Validate until get another color or reach the end of the board
                let validate = true;
                while (validate) {
                    // Set the new column and slot
                    curCol += movCol;
                    curSlot += movSlot;

                    // Check that they are valid values
                    if ((curCol < 0 || curCol >= this.#game.cols) ||
                        (curSlot < 0 || curSlot >= this.#game.discsPerCol)) {
                        validate = false;  // Stop the validation
                        continue;
                    }

                    // Check if the disc has not the same color value
                    if (this.#game.board[curCol][curSlot] != colorValue) {
                        validate = false;  // Stop the validation
                        continue;
                    }

                    // Add the current position to the same disc array
                    sameDiscs.push([curCol, curSlot]);
                }
            }

            // Check if the `sameDiscs` has more elements than the max number
            if (sameDiscs.length > connection.number) {
                connection.number = sameDiscs.length;
                connection.direction = direction;
                connection.positions = sameDiscs;
            }
        }

        return connection;
    }

    /**
     * Verifies if the game has ended after a move, either by a win or a draw.
     * Sets the game state to over if a player has connected four or if the
     * board is full (draw).
     *
     * @private
     * @param {[number, number]} move - The move to verify, represented as
     * [column, slot].
     * @returns {void}
     */
    #verifyEnd(move) {
        // Get the current move value
        const [column, slot] = move;
        const value = this.#game.board[column][slot];

        // Check the number of connections of the current movement
        const connections = this.#getMaxNumberOfConnections(move, value).number;

        // Four or more means the game is over
        if (connections >= 4) {
            this.#gameOver.isOver = true;
            this.#gameOver.winner = this.#currentColor;
            return;
        }

        // Check if is a draw
        for (const discs of this.#game.discs) {
            // If there are empty slots ends the verification and returns
            if (discs < this.#game.discsPerCol) return;
        }

        this.#gameOver.isOver = true;
    }

    /**
     * Updates the board's CSS custom properties to reflect the current color.
     * 
     * @private
     * @returns {void}
     */
    #setColor() {
        // Define the color classes
        const colorVar = `var(--disc-${this.#currentColor})`;
        const colorLightVar = `var(--disc-${this.#currentColor}-light)`;
        const colorDarkVar = `var(--disc-${this.#currentColor}-dark)`;

        // Update the color classes on the board
        this.#$board.style.setProperty('--color', colorVar);
        this.#$board.style.setProperty('--color-light', colorLightVar);
        this.#$board.style.setProperty('--color-dark', colorDarkVar);
    }

    /**
     * Handles the current color between 'red' and 'blue', and updates the color
     * using the `#setColor` method.
     * 
     * @private
     * @returns {void}
     */
    #changeColor() {
        // Change the color value and set the new color.
        this.#currentColor = (this.#currentColor == 'red') ? 'blue' : 'red';
        this.#setColor();
    }

    /**
     * Dispatches a custom 'playEnd' event to the window object after a move is
     * made.
     *
     * @private
     * @param {[number, number]} move - The last move made in the game.
     * @returns {void}
     */
    #dispatchMoveEvent(move) {
        // Get the previous color that made the move
        const prevColor = (this.#currentColor == 'red') ? 'blue' : 'red';

        // Dispatch a custom event to indicate that the movement has ended
        window.dispatchEvent(new CustomEvent(
            'playEnd',
            {
                detail: {
                    color: prevColor,
                    gameOver: this.#gameOver,
                    board: this.#game.board,
                    move,
                }
            }
        ));
    }

    /**
     * Handles the visual and logical process of playing a disc in the specified
     * column.
     * - Checks if the column has available slots.
     * - Animates the disc drop and plays a sound effect.
     * - Updates the internal game state and board representation.
     * - Handles end-of-move logic, including checking for game over, switching
     * player color, and dispatching events.
     * - Locks the board.
     *
     * @private
     * @param {number} column - The index of the column where the disc is to be
     * played.
     * @returns {boolean} Returns true if the move was successful, false if the
     * column is full.
     */
    #showPlay(column) {
        // Check if there are empty slots in the current column
        const disc = this.#game.discs[column];
        if (disc >= this.#game.discsPerCol) return false;

        // Increase the discs number in the current column
        this.#game.discs[column]++;

        // Get and update the current slot in the current column
        const $column = this.#$board.querySelector(`[data-column="${column}"]`);
        const $slot = $column.querySelector(`[data-slot="${disc}"]`);
        $slot.classList.add('board__slot--active');

        // Update the board game with the value of the current color
        this.#game.board[column][disc] = this.#boardValues[this.#currentColor];

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
            void $slot.offsetWidth;  // Force the element reflow

            // Check if the game is over
            const move = [column, disc];
            this.#verifyEnd(move);

            // Change the color and dispatch the move event
            this.#changeColor();
            this.#dispatchMoveEvent(move);
        }, { once: true });

        // The defined behavior is to lock the board after each move
        this.lock();

        return true;
    }

    /**
     * Handles click events on the game board.
     * Listens for clicks on board columns and, if the game is not locked or
     * over,triggers the display of the current play.
     * 
     * @private
     * @returns {void}
     */
    #boardClickEvent() {
        this.#$board.addEventListener('click', (event) => {
            // Check if a new move is allowed
            if (this.#boardLock || this.#gameOver.isOver) return;

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

    /**
     * Handles the mousemove event on the game board to update the position of
     * the floating disc.
     * 
     * When the mouse moves over a valid column, highlights the column and
     * repositions the floating disc to visually indicate where the next disc
     * will be dropped.
     * 
     * Ignores the event if the board is locked or the game is over.
     *
     * @private
     * @returns {void}
     */
    #moveFloatingDiscEvent() {
        // Get the floating disc element
        const floatingDisc = document.querySelector('.board__floating-disc');

        // Set the 'mousemove' event on the board
        this.#$board.addEventListener('mousemove', (event) => {
            // Check if the board is locked or the game is over
            if (this.#boardLock || this.#gameOver.isOver) return;

            // Check if the clicked target is a column
            let target = event.target;
            if (!target.classList.contains('board__column')) {
                // Check now the parent element
                target = target.parentElement;
                if (!target.classList.contains('board__column')) return;
            }

            // Add the hover class to the column
            target.classList.add('board__column--hover');

            // Change the 'left' value of the disc based on the column number
            const col = target.dataset.column;
            const left = `calc(1rem + (0.5rem * ${col}) + (((100% - 5rem) / 7) * ${col}))`;

            floatingDisc.style.left = left;
        });
    }

    /**
     * Highlight all discs adjacent to the disc corresponding to the winning
     * move. This method adds the 'board__slot--highlight' class to the winning
     * discs.
     *
     * @param {[number, number]} move - The winning move.
     * @returns {void}
     */
    highlightWiningMove(move) {
        // Get all the positions of the maximum number of conections
        const [col, slot] = move;
        const value = this.#game.board[col][slot];
        const { positions } = this.#getMaxNumberOfConnections(move, value);

        // Highlight all the wining discs
        positions.forEach((position) => {
            // Get the column and slot in the position
            const [posCol, posSlot] = position;

            // Get the current slot according to the position
            const $column = this.#$board.querySelector(`[data-column="${posCol}"]`);
            const $slot = $column.querySelector(`[data-slot="${posSlot}"]`);

            // Add the highlight class on the current slot
            $slot.classList.add('board__slot--highlight');
        });
    }

    /**
     * Calculates and returns the best move for the given color.
     * The method evaluates all possible moves, considering both the current
     * player and the opponent, to determine the optimal column and row to place
     * a disc. It prioritizes winning moves, blocks opponent's winning moves,
     * and prefers moves closer to the center of the board.
     *
     * @param {string} color - The color of the player making the move ('red' or
     * 'blue').
     * @returns {number[]} The best move as an array [column, row], representing
     * the position to place the disc.
     */
    getBestMove(color) {
        const oppositeColor = {
            red: this.#boardValues.blue,
            blue: this.#boardValues.red
        };

        // Get theh current color value and the opposite value
        const colorValue = this.#boardValues[color];
        const oppositeColorValue = oppositeColor[color];

        // Define a function to get the bigger connection with one color
        const getBestPlay = (opponent = false) => {
            // Define the color values depending on the 'opponent' value
            const colorVal = opponent ? oppositeColorValue : colorValue;
            const oppositeColorVal = opponent ? colorValue : oppositeColorValue;

            // Set the variables to select the best move
            const bestMoves = [];  // An array with the best moves
            let biggerValue = 0;  // The bigger value after checking all discs

            // Function to set the best move when the bigger value increase
            const setBestMove = (value, move) => {
                biggerValue = value;

                bestMoves.length = 0;
                bestMoves.push({
                    value: value,
                    position: move
                });
            };

            // Check all the columns to get the best moves
            for (let col = 0; col < this.#game.cols; col++) {
                // Check if the current column has no empty slots
                const disc = this.#game.discs[col];
                if (disc >= this.#game.discsPerCol) continue;

                // Get the number of connections in the current column
                const move = [col, disc];
                let value = this.#getMaxNumberOfConnections(
                    move, colorVal
                ).number + 1;  // Add the current disc

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
                    ? this.#getMaxNumberOfConnections(nextMove, nextColor).number + 1
                    : 1;

                // Check if the opponent wins with the current move
                if (nextValue >= 4) value = 0;  // The current play is bad

                // Now check if the resulting value is bigger or equal
                if (value > biggerValue) {
                    // Reset the best move
                    setBestMove(value, move);
                } else if (value == biggerValue) {
                    // Add the move to the best moves array
                    bestMoves.push({
                        value: value,
                        position: move
                    });
                }
            }

            // Return one of the best moves selected randomly
            return bestMoves[Math.floor(Math.random() * bestMoves.length)];
        };

        // Check if there is a winning play for the current color
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

    /**
     * Returns a random valid move for the current game state.
     * If the game is over, returns [-1, -1].
     *
     * @returns {number[]} An array containing the column and row indices for a
     * valid move, or [-1, -1] if no moves are possible.
     */
    getRandomMove() {
        if (this.#gameOver.isOver) return [-1, -1];

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

    /**
     * Attempts to play a move in the game.
     * 
     * @param {[number, number]} move - An array containing the column index
     * and slot index for the move.
     * @returns {boolean} Returns false if the game is over or the move is
     * invalid, otherwise returns the result of the move.
     */
    play(move) {
        if (this.#gameOver.isOver) return false;

        // Check if is a valid move
        const [column, slot] = move;
        if (this.#game.discs[column] != slot) return false;

        // Make the move
        return this.#showPlay(column);
    }

    /**
     * Initializes the board with all the events
     * 
     * @constructor
     */
    constructor() {
        // Get DOM elements
        this.#$board = document.querySelector('#board');

        // Define the game attributes
        this.#sounds = {
            drop: new Audio('../sounds/drop.wav'),
        };
        this.#boardValues = {
            empty: 0,
            red: 1,
            blue: 2
        };
        this.#animationDelay = (parseInt(
            getComputedStyle(document.documentElement)
                .getPropertyValue('--animation-duration')
        ) / 2) - 5;  // The animation delay for each slot minus 5 ms.
        this.#gameOver = {
            isOver: false,
            winner: null,
        };
        this.lock(); // Lock the board

        /* Initialize the game. The board is defined according to the game
        values. The discs only hold the current number of discs per column. */
        const e = this.#boardValues.empty;
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
            discs: [0, 0, 0, 0, 0, 0, 0],  // The current number of discs in each column
            cols: 7,  // The total number of columns
            discsPerCol: 6,  // The maximum number of disc per column
        };

        // Set the initial color
        this.#currentColor = 'red';
        this.#setColor();

        // Set the game events
        this.#moveFloatingDiscEvent();
        this.#boardClickEvent();
    }
}

class Game {
    #gameMode;
    #gameModes;
    #gameData;
    #sounds;
    #board;
    #players;
    #hostNumber;
    #hostColor;

    /**
     * Manages local game logic, simply unlocking the board after each move.
     *
     * @private
     * @returns {void}
     */
    #localGame() {
        this.#board.unlock();
    }

    /**
     * Handles the robot's turn in the game based on the current player's color
     * and game difficulty.
     * - If it's the robot's turn, decides whether to make the best move or a
     * random move according to the difficulty setting.
     * - If it's the host's turn, unlocks the board for the next move.
     *
     * @private
     * @param {Object} detail - The detail object containing information about
     * the old move.
     * @param {string} detail.color - The color of the player making the move.
     * @returns {void}
     */
    #robotGame(detail) {
        // Get the color of the last move
        const { color } = detail;

        // Check if the last play was made by the host or the robot
        if (this.#hostColor == color) {  // Host
            // Get the move according to the difficulty
            const isBestMove = Math.random() <= this.#gameData.
                precisionByDifficulty[this.#gameData.difficulty];

            if (isBestMove) {  // Make the best move
                const robotColor = this.#gameData.oppositeColor[color];
                this.#board.play(this.#board.getBestMove(robotColor));
            } else {  // Make a random move
                this.#board.play(this.#board.getRandomMove());
            }
        } else {  // Robot
            this.#board.unlock();
        }
    }

    /**
     * Manages the end of the game, showing the winner if there is one,
     * otherwise it only shows that the game ends in a draw.
     *
     * @private
     * @param {Object} detail - The detail object containing information about
     * the old move.
     * @param {[number, number]} detail.move - The last move made containing the
     * column index and the slot index.
     * @param {string} detail.color - The color of the player that made the last
     * move ('blue' or 'red').
     * @param {Object} detail.gameOver - One object with the endgame data.
     * @param {string|null} detail.gameOver.winner - The winner of the game, it
     * will be 'blue', 'red' or null if the game end with a draw.
     * @returns {void}
     */
    #manageGameEnd(detail) {
        // Check if there is a winner
        const winner = detail.gameOver.winner;
        if (winner) {
            // highlight the winning discs and play the discs sound
            this.#board.highlightWiningMove(detail.move);
            this.#sounds.discs.play();
        }

        // Get the current end element ('winner' or 'draw') and make it visible
        const endElementId = (winner) ? 'winner' : 'draw';
        const $endElement = document.querySelector(`#${endElementId}`);
        $endElement.classList.remove(`${endElementId}--hidden`);

        // Set a flag that will define if the host won
        let hostWon = false;

        // Set the winner's image and name if applicable
        if (endElementId == 'winner') {
            // Get the image and name elements
            const $image = $endElement.querySelector('.winner__image');
            const $name = $endElement.querySelector('.winner__name');

            // Get if the host won
            hostWon = (this.#hostColor == detail.color);

            // Get the winner's image and name
            const $winnerImage = (hostWon)
                ? this.#players[this.#hostNumber - 1].image
                : this.#players[
                    this.#gameData.oppositeNumber[this.#hostNumber] - 1
                ].image;
            const $winnerName = (hostWon)
                ? this.#players[this.#hostNumber - 1].name
                : this.#players[
                    this.#gameData.oppositeNumber[this.#hostNumber] - 1
                ].name;

            // Set the image and name
            $image.src = $winnerImage.src;
            $name.innerText = $winnerName.innerText;
        }

        // Show the game end
        const delay = (endElementId == 'winner') ? 1000 : 250;
        setTimeout(() => {
            // Show the rain effect if the host won or is playing the local mode
            if (winner && (hostWon || this.#gameMode == this.#gameModes.local)) {
                window.dispatchEvent(new CustomEvent('effectStart'));
            }

            // Show the end game modal
            window.dispatchEvent(new CustomEvent(
                'showModal', {
                detail: {
                    id: 'modal-game-end',
                    escClose: false,
                    backdropClose: false,
                }
            }));
        }, delay);

        console.log(detail.board);
        return;
    }

    /**
     * Manages the game setup depending on the `this.#hostNumber` attribute.
     * 
     * @private
     * @returns {void}
     */
    #manageGameStart() {
        // Game preparation
        if (this.#hostNumber == 1) this.#board.unlock();

        switch (this.#gameMode) {
            case this.#gameModes.robot:
                // Check if the robot is the first player and make a random move
                if (this.#hostNumber == 2) {
                    this.#board.play(this.#board.getRandomMove());
                }
                break;
            case this.#gameModes.local:
                // Unlock the board
                this.#board.unlock();
                break;
            case this.#gameModes.quick:
                break;
            case this.#gameModes.friend:
                break;
        }
    }

    /**
     * Manages the flow of the game, including setup, managing different game
     * modes, and responding to the end of each player's turn by detecting the
     * "playEnd" event to process game progress or completion.
     *
     * @private
     * @returns {void}
     */
    #manageGame() {
        // Prepare the game
        this.#manageGameStart();

        // The event when each player ends his play
        window.addEventListener('playEnd', (event) => {
            const detail = event.detail;
            const { gameOver } = detail;

            // Check if is the end
            if (gameOver.isOver) {
                this.#manageGameEnd(detail);
                return;
            };

            // Manage the game with the corresponding method
            switch (this.#gameMode) {
                case this.#gameModes.robot:
                    this.#robotGame(detail);
                    break;
                case this.#gameModes.local:
                    this.#localGame();
                    break;
                case this.#gameModes.quick:
                    break;
                case this.#gameModes.friend:
                    break;
            }
        });
    }

    /**
     * Initializes and sets up player information for the game.
     * 
     * @private
     * @returns {void}
     */
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

        // Get the host number
        const $game = document.querySelector('#game');
        this.#hostNumber = Number($game.dataset.hostNumber);

        // Check if it's the robot mode
        if (this.#gameMode == this.#gameModes.robot) {
            // Randomly select whether the robot will be player 1 or 2
            const robot = Math.floor(Math.random() * 2) + 1;

            // Check if the robot is player 1 and change the data
            if (robot == 1) {
                // Images
                $player2Image.src = $player1Image.src;
                if ($player1Name.innerText == '_Anonymous') {
                    $player2Image.src = 'img/profile/blue-disc.webp';
                }
                $player1Image.src = 'img/profile/red-robot.webp';

                // Names
                $player2Name.innerText = $player1Name.innerText;
                $player1Name.innerText = 'Robot';

                // Translations
                const translateTemporal = $player1Name.dataset.translate;
                $player1Name.dataset.translate = $player2Name.dataset.translate;
                $player2Name.dataset.translate = translateTemporal;

                // Change the host number
                this.#hostNumber = 2;
            }
        }

        // Check if the user names are '_Anonymous' and correct the names
        //--Player 1
        if ($player1Name.innerText == '_Anonymous') $player1Name.innerText = 'Anonymous';
        //--Player 2
        if ($player2Name.innerText == '_Anonymous') $player2Name.innerText = 'Anonymous';

        // Create an array for both players
        this.#players = [{
            number: 1,
            element: $player1,
            name: $player1Name,
            image: $player1Image,
        }, {
            number: 2,
            element: $player2,
            name: $player2Name,
            image: $player2Image,
        }];

        // Define the host player color
        this.#hostColor = (this.#hostNumber == 1) ? 'red' : 'blue';
    }

    /**
     * Displays the versus modal with both players and plays the versus sound.
     * After a short delay and animation, closes the modal and proceeds to
     * manage the game.
     *
     * @private
     * @returns {void}
     */
    #showVersus() {
        // Get and set the players
        this.#getAndSetPlayers();

        // Get the versus modal elements
        const $versusModal = document.querySelector('#modal-versus');
        const $versusPlayers = $versusModal.querySelector('.versus__players');

        // Put both players in the versus modal
        $versusPlayers.prepend(this.#players[0].element);
        $versusPlayers.append(this.#players[1].element);

        // Show the modal
        $versusModal.showModal();

        // Prevent the user from closing the modal by pressing 'esc'
        const preventCloseByEsc = (event) => {
            event.preventDefault();
        }
        $versusModal.addEventListener('cancel', preventCloseByEsc);

        // Wait 1.2 seconds before showing the closing animation
        setTimeout(() => {
            // Put the closing animation on the versus and play the sound
            $versusModal.classList.add('versus--close');
            this.#sounds.versus.play();

            // Wait until the animation ends to continue with the game
            $versusModal.addEventListener('animationend', () => {
                // Remove the listener from the modal
                $versusModal.removeEventListener('cancel', preventCloseByEsc);

                // Return both players to the game players aside
                const $asidePlayers = document.querySelector('.aside__players');
                $asidePlayers.prepend(this.#players[0].element);
                $asidePlayers.append(this.#players[1].element);
                $asidePlayers.classList.remove('aside__players--hidden');

                // Shows the options in the aside
                const $options = document.querySelector('.aside__options');
                $options.classList.remove('aside__options--hidden');

                // Close the modal and manage the game
                $versusModal.close();
                this.#manageGame();
            }, { once: true });
        }, 1200);
    }

    /**
     * Initializes and sets up game data based on the current game mode and URL
     * parameters.
     *
     * @private
     * @returns {void}
     */
    #setGameData() {
        // Get the current game mode
        const path = window.location.pathname.replaceAll('/', '');

        // Prepare general data
        this.#gameData.oppositeColor = {
            red: 'blue',
            blue: 'red'
        };
        this.#gameData.oppositeNumber = {
            1: 2,
            2: 1
        };
        this.#gameData.precisionByDifficulty = {
            easy: 0.2,
            normal: 0.7,
            hard: 1
        };

        // Set the current mode and get the corresponding preparation
        switch (path) {
            case 'playofflinerobot':
                this.#gameMode = this.#gameModes.robot;

                // Get and set the difficulty in the game preparation
                //--Get the difficulty in the params
                const params = new URLSearchParams(window.location.search);
                const difficulties = ['easy', 'normal', 'hard'];
                const paramDifficulty = params.get('difficulty');

                //--Set the difficulty
                this.#gameData.difficulty = difficulties.includes(paramDifficulty)
                    ? paramDifficulty
                    : 'normal';

                // Set the difficulty in the DOM
                //--Get the DOM difficulty elements
                const $difficulty = document.querySelector('#difficulty');
                const $difficultyName = $difficulty.querySelector('#difficulty-name');
                const $difficultyIcon = $difficulty.querySelector('#difficulty-icon');

                //--Get a copy of the template corresponding to the icon
                const $iconCopy = document.querySelector(
                    `#template-icon-${this.#gameData.difficulty}`
                ).content.cloneNode(true);

                //--Append the icon copy to the difficulty icon element
                $difficultyIcon.appendChild($iconCopy);

                //--Add the name and translate it
                $difficultyName.innerText = this.#gameData.difficulty;
                $difficultyName.setAttribute('data-translate', this.#gameData.difficulty);
                window.dispatchEvent(new CustomEvent(
                    'translateElement', { detail: { element: $difficultyName } }
                ));

                //--Add the corresponding class to the difficulty
                $difficulty.classList.add(`difficulty--${this.#gameData.difficulty}`);
                break;
            case 'playofflinelocal':
                this.#gameMode = this.#gameModes.local;
        }
    }

    /**
     * Initializes the game with all the events
     * 
     * @constructor
     */
    constructor() {
        // Set the game attributes
        this.#gameData = {};
        this.#gameMode = null;
        this.#gameModes = {
            robot: 0,
            local: 1,
            quick: 2,
            friend: 3,
        };
        this.#board = new Board();  // Initialize a new instance of the board
        this.#sounds = {  // Get the sounds
            versus: new Audio('../sounds/versus.wav'),
            discs: new Audio('../sounds/discs.wav'),
        };

        // Start the game events
        this.#setGameData();
        this.#showVersus();
    }
}

new Game();