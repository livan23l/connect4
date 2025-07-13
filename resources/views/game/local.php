<?php
$title = 'Playing locally';
$styles = ['modal', 'effects'];
$scripts = ['modal', 'effects'];
require_once BASE . 'resources/components/header_game.php';
?>

<main id="game" class="game" data-host-number="1">
    <aside class="game__aside">
        <?php
        $aside_title = 'Playing locally';
        $players = [
            [
                'image' => 'red-disc',
                'name' => 'Red',
                'translate' => 'Red'
            ],
            [
                'image' => 'blue-disc',
                'name' => 'Blue',
                'translate' => 'Blue'
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