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
            <section class="section">
                <h2 class="section__title" data-translate="Avatars">Avatars</h2>

                <div class="section__content">
                    <?php foreach ($avatars as $avatar): ?>
                        <div class="avatar">
                            <img class="avatar__image" src="img/profile/<?= $avatar->name ?>.webp" alt="Image of '<?= $avatar->name ?>'">
                            <?php if ($current_banner): ?>
                                <img class="avatar__background" src="img/banners/<?= $current_banner->name ?>.webp" alt="Image of '<?= $current_banner->name ?>'"  <?= ($current_banner->position) ? 'style="object-position: ' . $current_banner->position . '"' : '' ?>>
                            <?php endif; ?>
                            <button class="section__option">
                                <span><?= $avatar->price ?></span>
                                <svg class="icon icon--xs icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2.45 7.4 7.2 1.067a1 1 0 0 1 1.6 0L13.55 7.4a1 1 0 0 1 0 1.2L8.8 14.933a1 1 0 0 1-1.6 0L2.45 8.6a1 1 0 0 1 0-1.2" />
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Banners -->
            <section class="section">
                <h2 class="section__title" data-translate="Banners">Banners</h2>

                <div class="section__content">
                    <?php foreach ($banners as $banner): ?>
                        <div class="banner">
                            <img class="banner__image" src="img/profile/<?= $current_avatar_name ?>.webp" alt="Profile avatar image">
                            <img class="banner__background" src="img/banners/<?= $banner->name ?>.webp" alt="Image of '<?= $banner->name ?>'" <?= ($banner->position) ? 'style="object-position: ' . $banner->position . '"' : '' ?>>
                            <button class="section__option">
                                <span><?= $banner->price ?></span>
                                <svg class="icon icon--xs icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2.45 7.4 7.2 1.067a1 1 0 0 1 1.6 0L13.55 7.4a1 1 0 0 1 0 1.2L8.8 14.933a1 1 0 0 1-1.6 0L2.45 8.6a1 1 0 0 1 0-1.2" />
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Buy Me A Coffee -->
            <section class="section">
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