<?php
$title = "Settings";
$styles = ['modal', 'settings'];
$scripts = ['modal', 'settings'];
$without_main = true;
require_once BASE . "resources/components/header.php";
?>

<header class="header">
    <h1 class="header__title" data-translate="Settings">Settings</h1>
</header>

<main class="main">
    <!-- General setings -->
    <section id="general" class="section">
        <h2 class="section__title" data-translate="General settings">General settings</h2>

        <div class="section__content">
            <div class="setting">
                <h3 class="setting__title" data-translate="Change the language">Change the language</h3>

                <div class="setting__divider"></div>

                <form action="#" method="post">
                    <select id="select-languages" class="select">
                        <option class="select__option" value="en">English</option>
                        <option class="select__option" value="es">Español</option>
                        <option class="select__option" value="pt">Português</option>
                        <option class="select__option" value="fr">Français</option>
                    </select>
                </form>
            </div>
        </div>
    </section>

    <!-- Account settings -->
    <section id="account" class="section">
        <h2 class="section__title" data-translate="Account">Account</h2>
        <div class="section__content">
            <?php if (isset($_SESSION['auth'])): ?>
                <!-- Change password -->
                <div class="setting setting--column">
                    <h3 class="setting__title" data-translate="Change password">Change password</h3>

                    <form class="form" action="/change-password" method="post">
                        <div class="form__element">
                            <label class="form__label" for="current_password" data-translate="Current password">Current password</label>
                            <input class="form__input" id="current_password" name="current_password" type="password" minlength="8" value="<?= old('current_password') ?>" placeholder="" required>
                        </div>
                        <p class="form__error" data-translate="<?= error('current_password') ?>"><?= error('current_password') ?></p>
                        <div class="form__element">
                            <label class="form__label" for="new_password" data-translate="New password">New password</label>
                            <input class="form__input" id="new_password" name="new_password" type="password" minlength="8" value="<?= old('new_password') ?>" placeholder="" required>
                        </div>
                        <p class="form__error" data-translate="<?= error('new_password') ?>"><?= error('new_password') ?></p>
                        <div class="form__element">
                            <label class="form__label" for="new_password_confirmation" data-translate="Confirm the password">Confirm the password</label>
                            <input class="form__input" id="new_password_confirmation" name="new_password_confirmation" type="password" minlength="8" value="<?= old('new_password_confirmation') ?>" placeholder="" required>
                        </div>
                        <button class="setting__button" type="submit" data-translate="Update">Update</button>
                    </form>
                </div>

                <!-- Other options -->
                <div class="setting setting--column">
                    <h3 class="setting__title" data-translate="Other options">Other options</h3>

                    <div class="setting__options">
                        <a class="setting__button" href="/play" data-translate="Go back to the lobby">Go back to the lobby</a>

                        <form action="/close-session" method="post">
                            <button class="setting__button" type="submit" data-translate="Close session">Close session</button>
                        </form>

                        <form id="form-delete" action="/delete-account" method="post">
                            <button class="setting__button setting__button--danger" type="submit" data-translate="Delete account">Delete account</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="setting setting--column">
                    <a class="w-fit text-center anchor" href="/access" data-translate="Access to unlock your account settings">
                        Access to unlock your account settings
                    </a>

                    <a class="w-fit text-center anchor" href="/play" data-translate="Go back to the lobby">
                        Go back to the lobby
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<dialog id="modal-confirmation" class="modal">
    <div class="modal__content">
        <h2 class="modal__title" data-translate="Delete confirmation">Delete confirmation</h2>

        <form id="form-delete-confirmation" action="/delete-account" method="post">
            <div class="form__element form__element--column">
                <label class="form__label" for="confirmation_password" data-translate="Enter your password">Enter your password</label>
                <input class="form__input" id="confirmation_password" name="confirmation_password" type="password" minlength="8" value="<?= old('confirmation_password') ?>" placeholder="" required>
            </div>
            <p class="form__error form__error--separated" data-translate="<?= error('confirmation_password') ?>"><?= error('confirmation_password') ?></p>
        </form>

        <div class="modal__options">
            <button class="setting__button" data-translate="Cancel" data-close-modal>Cancel</button>
            <button class="setting__button setting__button--danger" type="submit" form="form-delete-confirmation" data-translate="Delete">
                Delete
            </button>
        </div>
    </div>
</dialog>

<?php
require_once BASE . "resources/components/footer.php";
?>