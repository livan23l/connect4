<?php if (isset($_SESSION['auth'])): ?>
    <a href="/profile/<?= $_SESSION['auth']['profile']['id'] ?>" class="mini-profile">
        <!-- Image -->
        <div class="mini-profile__column">
            <figure class="mini-profile__figure">
                <img class="mini-profile__image" src="img/profile/<?= $_SESSION['auth']['profile']['avatar'] ?>.webp" alt="Profile avatar image">
            </figure>
        </div>

        <!-- Name and points -->
        <div class="mini-profile__column">
            <!-- Name -->
            <p class="mini-profile__name">
                <?= $_SESSION['auth']['profile']['name'] ?>
            </p>

            <!-- Points -->
            <div class="mini-profile__points">
                <span id="points"><?= $_SESSION['auth']['profile']['points'] ?></span>
                <svg class="icon icon--xs icon--min-xs" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.45 7.4 7.2 1.067a1 1 0 0 1 1.6 0L13.55 7.4a1 1 0 0 1 0 1.2L8.8 14.933a1 1 0 0 1-1.6 0L2.45 8.6a1 1 0 0 1 0-1.2" />
                </svg>
            </div>
        </div>
    </a>
<?php else: ?>
    <a href="/access" class="mini-profile mini-profile--access" data-translate="<?= $access_message ?>">
        <?= $access_message ?? '$access_message is not defined' ?>
    </a>
<?php endif; ?>