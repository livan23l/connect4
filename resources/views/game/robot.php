<?php
$title = 'Play vs robot';
require_once BASE . 'resources/components/header_game.php';
?>

<h1>Playing vs robot</h1>

<div>
    <div class="player">
        <p class="player__name">_Anonymous</p>
        <img class="player__image" src="" alt="Player image">
    </div>
    <div class="player">
        <p class="player__name">Robot</p>
        <img class="player__image" src="/img/profile/anonymous.webp" alt="Player image">
    </div>
</div>

<?php require_once BASE . 'resources/components/board.php'; ?>