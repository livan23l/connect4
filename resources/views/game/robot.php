<?php
$title = 'Play vs robot';
$styles = ['modal', 'effects'];
$scripts = ['modal', 'effects'];
require_once BASE . 'resources/components/header_game.php';
?>

<!-- Templates -->
<template id="template-icon-easy">
    <svg class="icon icon--md icon--min-xs text-red" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
    </svg>
</template>
<template id="template-icon-normal">
    <svg class="icon icon--md icon--min-xs text-red" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
        <path d="M11.473 11a4.5 4.5 0 0 0-8.72-.99A3 3 0 0 0 3 16h8.5a2.5 2.5 0 0 0 0-5z" />
        <path d="M10.5 1.5a.5.5 0 0 0-1 0v1a.5.5 0 0 0 1 0zm3.743 1.964a.5.5 0 1 0-.707-.707l-.708.707a.5.5 0 0 0 .708.708zm-7.779-.707a.5.5 0 0 0-.707.707l.707.708a.5.5 0 1 0 .708-.708zm1.734 3.374a2 2 0 1 1 3.296 2.198q.3.423.516.898a3 3 0 1 0-4.84-3.225q.529.017 1.028.129m4.484 4.074c.6.215 1.125.59 1.522 1.072a.5.5 0 0 0 .039-.742l-.707-.707a.5.5 0 0 0-.854.377M14.5 6.5a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z" />
    </svg>
</template>
<template id="template-icon-hard">
    <svg class="icon icon--md icon--min-xs text-red" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
        <path d="M2.658 11.026a.5.5 0 0 1 .316.632l-.5 1.5a.5.5 0 1 1-.948-.316l.5-1.5a.5.5 0 0 1 .632-.316m9.5 0a.5.5 0 0 1 .316.632l-.5 1.5a.5.5 0 0 1-.948-.316l.5-1.5a.5.5 0 0 1 .632-.316m-7.5 1.5a.5.5 0 0 1 .316.632l-.5 1.5a.5.5 0 1 1-.948-.316l.5-1.5a.5.5 0 0 1 .632-.316m9.5 0a.5.5 0 0 1 .316.632l-.5 1.5a.5.5 0 0 1-.948-.316l.5-1.5a.5.5 0 0 1 .632-.316m-7.105-1.25A.5.5 0 0 1 7.5 11h1a.5.5 0 0 1 .474.658l-.28.842H9.5a.5.5 0 0 1 .39.812l-2 2.5a.5.5 0 0 1-.875-.433L7.36 14H6.5a.5.5 0 0 1-.447-.724zm6.352-7.249a5.001 5.001 0 0 0-9.499-1.004A3.5 3.5 0 1 0 3.5 10H13a3 3 0 0 0 .405-5.973" />
    </svg>
</template>

<main id="game" class="game" data-host-number="1">
    <aside class="game__aside">
        <header class="aside__header">
            <h1 class="aside__title" data-translate="Playing vs robot">Playing vs robot</h1>
            <div id="difficulty" class="difficulty">
                <p id="difficulty-name" class="difficulty__name"></p>
                <div id="difficulty-icon"></div>
            </div>
        </header>

        <main class="aside__main">
            <div class="aside__players aside__players--hidden">
                <!-- Player 1 -->
                <div data-player="1" class="player player--red">
                    <img class="player__image" src="img/profile/red-disc.webp" alt="Player image">
                    <p class="player__name" data-translate="Anonymous">_Anonymous</p>
                </div>

                <!-- VS -->
                <p class="aside__vs">vs</p>

                <!-- Player 2 -->
                <div data-player="2" class="player player--blue">
                    <img class="player__image" src="img/profile/blue-robot.webp" alt="Player image">
                    <p class="player__name" data-translate="Robot">Robot</p>
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