<?php
$title = 'Profile page';
$styles = ['modal', 'profile'];
$scripts = ['modal', 'profile'];
require_once BASE . 'resources/components/header_menus.php';
?>

<main class="main">
    <div class="profile">
        <!-- Image, name, points and options -->
        <div class="avatar">
            <img class="avatar__image" src="/img/profile/<?= $profile['avatar'] ?>.webp" alt="Profile image">
            <h1 class="avatar__name"><?= $profile['name'] ?></h1>
            <div class="avatar__points">
                <span id="points"><?= $profile['points'] ?></span>
                <svg class="icon icon--sm icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.45 7.4 7.2 1.067a1 1 0 0 1 1.6 0L13.55 7.4a1 1 0 0 1 0 1.2L8.8 14.933a1 1 0 0 1-1.6 0L2.45 8.6a1 1 0 0 1 0-1.2" />
                </svg>
            </div>
            <?php if ($option != 0): ?>
                <!-- Profile option -->
                <div id="options" class="avatar__options" data-current-option="<?= $option ?>">
                    <?php if ($option == 1): ?>
                        <button class="avatar__option avatar__option--hidden" data-option="1">
                            <svg class="icon icon--2xs icon--min-2xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                            </svg>

                            <p data-translate="Edit profile">Edit profile</p>
                        </button>
                    <?php else: ?>
                        <button class="avatar__option avatar__option--hidden" data-option="2">
                            <svg class="icon icon--xs icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                                <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                            </svg>

                            <p data-translate="Add friend">Add friend</p>
                        </button>

                        <button class="avatar__option avatar__option--hidden" data-option="3">
                            <svg class="icon icon--xs icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708"/>
                            </svg>

                            <p data-translate="Cancel request">Cancel request</p>
                        </button>

                        <button class="avatar__option avatar__option--hidden" data-option="4">
                            <svg class="icon icon--xs icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M11 12h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                                <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                            </svg>

                            <p data-translate="Delete friend">Delete friend</p>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Profile information -->
        <div class="information">
            <div class="information__profile">
                <!-- Joined information -->
                <p class="information__joined">
                    <span class="information__joined-word" data-translate="Joined:">Joined:</span>
                    <span><?= explode(' ', $profile['created_at'])[0] ?></span>
                </p>

                <!-- Games won, lost and drawn -->
                <div class="information__games">
                    <p class="information__game information__game--win">
                        <span class="information__game-tooltip" data-translate="Games won">Games won</span>
                        <span class="information__game-number"><?= $profile['games_won'] ?></span>
                    </p>
                    <span>/</span>
                    <p class="information__game information__game--lose">
                        <span class="information__game-tooltip" data-translate="Lost games">Lost games</span>
                        <span class="information__game-number"><?= $profile['games_lost'] ?></span>
                    </p>
                    <span>/</span>
                    <p class="information__game information__game--draw">
                        <span class="information__game-tooltip" data-translate="Tied games">Tied games</span>
                        <span class="information__game-number"><?= $profile['games_drawn'] ?></span>
                    </p>
                </div>
            </div>

            <!-- Account description -->
            <div class="information__description">
                <?php if ($profile['description']): ?>
                    <p><?= $profile['description'] ?></p>
                <?php else: ?>
                    <p style="font-style: italic;" data-translate="No description provided">No description provided</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Achievements -->
    <div class="achievements">
        <h2 class="achievements__title" data-translate="Achievements">Achievements</h2>

        <div class="achievements__icons">
            <?php foreach ($achievements as $acheivement): ?>
                <div class="achievement <?= in_array($acheivement['id'], $unlockedAchievements) ? 'achievement__unlock' : '' ?>">
                    <img class="achievement__image" src="/img/achievements/<?= $acheivement['image'] ?>.webp" alt="<?= $acheivement['image'] ?> acheivement image">
                    <p class="achievement__tooltip" data-translate="<?= $acheivement['description'] ?>"><?= $acheivement['description'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php if ($option == 1): ?>
    <dialog id="modal-edit-profile" class="modal modal--wide">
        <div class="modal__content">
            <h3 class="modal__title">Edit profile</h3>

            <form action="">
                    
            </form>
        </div>
    </dialog>
<?php endif; ?>

<?php
require_once BASE . 'resources/components/footer.php';
?>