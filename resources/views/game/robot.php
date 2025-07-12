<?php
$title = 'Play vs robot';
$styles = ['modal', 'effects'];
$scripts = ['modal', 'effects'];
require_once BASE . 'resources/components/header_game.php';
?>

<main id="game" class="game" data-host-number="1">
    <aside class="game__aside">
        <?php
        $aside_title = 'Playing vs robot';
        $aside_difficulty = true;
        $players = [
            [
                'image' => (false) ? '' : 'red-disc',
                'name' => (false) ? '' : '_Anonymous',
                'translate' => (false) ? '' : 'Anonymous'
            ],
            [
                'image' => 'blue-robot',
                'name' => 'Robot',
                'translate' => 'Robot'
            ]
        ];
        require_once BASE . 'resources/components/game_aside.php';
        ?>
    </aside>

    <main class="game__main">
        <?php require_once BASE . 'resources/components/board.php'; ?>
    </main>
</main>

<?php
require_once BASE . 'resources/components/footer_game.php';
?>