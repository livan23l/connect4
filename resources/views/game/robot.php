<?php
$title = 'Play vs robot';
require_once BASE . 'resources/components/header_game.php';
?>

<main class="game">
    <aside class="game__aside">
        <h1 class="game__title" data-translate="Playing vs robot">Playing vs robot</h1>
        <p id="difficulty" class="game__difficulty game__difficulty--hidden">
            <span data-translate="Difficulty:">Difficulty:</span>
            <span id="difficulty-span"></span>
        </p>

        <div class="game__players">
            <div data-player="1" class="player player--red">
                <img class="player__image" src="img/profile/red-disc.webp" alt="Player image">
                <p class="player__name">_Anonymous</p>
            </div>
            <p class="game__vs">vs</p>
            <div data-player="2" class="player player--blue">
                <img class="player__image" src="img/profile/blue-robot.webp" alt="Player image">
                <p class="player__name">Robot</p>
            </div>
        </div>

        <div class="game__options">
            <a class="game__option" href="/play">Go back to the lobby</a>
        </div>
    </aside>

    <main class="game__main">
        <?php require_once BASE . 'resources/components/board.php'; ?>
    </main>
</main>

<dialog id="modal-versus" class="versus modal" closedby="none">
    <div class="versus__content">
        <img class="versus__logo" src="img/logo.webp" alt="Image of the app logo">
        <div class="versus__players">
            <p class="game__vs">vs</p>
        </div>
    </div>
</dialog>