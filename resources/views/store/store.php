<?php
$title = 'Points store';
$styles = ['store', 'mini_profile', 'modal'];
$scripts = ['store', 'modal'];
require_once BASE . 'resources/components/header_menus.php';
?>

<h1 class="invisible">Store</h1>

<main class="main">
    <!-- Profile -->
    <?php
    $access_message = 'Access to unlock the store';
    require_once BASE . 'resources/components/mini_profile.php';
    ?>

    <?php if(isset($_SESSION['auth'])): ?>
        <main class="store">
            <!-- Avatars -->
            <section class="store__section">
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                    <div class="avatar__option"></div>
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
                <div class="avatar">
                    <img class="avatar__image" src="" alt="Image of">
                </div>
            </section>
            <!-- Banners -->
            <section class="store__section">
            </section>
            <!-- Buy Me A Coffee -->
            <section class="store__section">
            </section>
        </main>
    <?php else: ?>
        <!-- Lock icon -->
        <svg class="icon icon--lg text-primary-blue" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3" />
        </svg>
    <?php endif; ?>
</main>

<?php
require_once BASE . "resources/components/footer.php";
?>