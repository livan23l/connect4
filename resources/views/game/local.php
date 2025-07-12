<?php
$title = 'Play vs robot';
$styles = ['modal', 'effects'];
$scripts = ['modal', 'effects'];
require_once BASE . 'resources/components/header_game.php';
?>

<main id="game" class="game" data-host-number="1">
    <aside class="game__aside">
        <header class="aside__header">
            <h1 class="aside__title" data-translate="Playing locally">Playing locally</h1>
        </header>

        <main class="aside__main">
            <div class="aside__players aside__players--hidden">
                <!-- Player 1 -->
                <div data-player="1" class="player player--red">
                    <img class="player__image" src="img/profile/red-disc.webp" alt="Red disc image">
                    <p class="player__name" data-translate="Red player">Red player</p>
                </div>

                <!-- VS -->
                <p class="aside__vs">vs</p>

                <!-- Player 2 -->
                <div data-player="2" class="player player--blue">
                    <img class="player__image" src="img/profile/blue-disc.webp" alt="Blue disc image">
                    <p class="player__name" data-translate="Blue player">Blue player</p>
                </div>
            </div>

            <div class="aside__options aside__options--hidden">
                <a class="aside__option" href="/play" data-translate="Go back to the lobby">Go back to the lobby</a>
            </div>
        </main>
    </aside>

    <main class="game__main">
        <?php require_once BASE . 'resources/components/board.php'; ?>
    </main>
</main>

<dialog id="modal-versus" class="versus" closedby="none">
    <div class="versus__content">
        <img class="versus__logo" src="img/logo.webp" alt="Image of the app logo">
        <div class="versus__players">
            <p class="aside__vs">vs</p>
        </div>
    </div>
</dialog>

<dialog id="modal-game-end" class="modal modal--backdrop-useless" closedby="none">
    <div class="modal__content modal__content--separate">
        <h3 class="modal__title" data-translate="Game over">Game over</h3>

        <div id="winner" class="winner winner--hidden">
            <p class="winner__title" data-translate="Winner">Winner</p>
            <img class="winner__image" src="" alt="Winner image">
            <p class="winner__name"></p>
        </div>

        <div id="draw" class="draw draw--hidden" data-translate="Draw">
            Draw
        </div>

        <div class="modal__options">
            <button class="modal__tooltip" data-action="reload">
                <svg class="icon icon--2xs text-red" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                </svg>
                <p class="modal__tooltip-text" data-translate="Restart">Restart</p>
            </button>
            <a class="modal__tooltip" href="/play">
                <svg class="icon icon--2xs text-red" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
                <p class="modal__tooltip-text" data-translate="Exit">Exit</p>
            </a>
        </div>
    </div>
</dialog>

<?php
require_once BASE . "resources/components/footer_game.php";
?>