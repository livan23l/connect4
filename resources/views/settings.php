<?php
$title = "Settings";
$styles = ['settings'];
$scripts = ['settings'];
$without_main = true;
require_once BASE . "resources/components/header.php";
?>

<header class="header">
    <h1 class="header__title">Settings</h1>
</header>

<main class="main">
    <!-- General setings -->
    <section id="general" class="section">
        <h2 class="section__title">General settings</h2>

        <div class="section__content">
            <div class="setting">
                <h3 class="setting__title">Change the language</h3>

                <div class="setting__divider"></div>

                <form action="#" method="post">
                    <select name="language" id="language">
                        <option value="">English</option>
                        <option value="">Español</option>
                        <option value="">Português</option>
                        <option value="">Français</option>
                    </select>
                </form>
            </div>
        </div>
    </section>

    <!-- Account settings -->
    <section id="account" class="section">
        <h2 class="section__title">Account</h2>
        <div class="section__content">
            <?php if (isset($_SESSION['auth'])): ?>
                <div class="setting setting--column">
                    <h3 class="setting__title">Change password</h3>

                    <form class="form" action="/change-password" method="post">
                        <div class="form__element">
                            <label class="form__label" for="current_password">Current password</label>
                            <input class="form__input" id="current_password" name="current_password" type="password" minlength="8" value="<?= old('current_password') ?>" placeholder="" required>
                        </div>
                        <p class="form__error" data-translate="<?= error('current_password') ?>"><?= error('current_password') ?></p>
                        <div class="form__element">
                            <label class="form__label" for="new_password">New password</label>
                            <input class="form__input" id="new_password" name="new_password" type="password" minlength="8" value="<?= old('new_password') ?>" placeholder="" required>
                        </div>
                        <p class="form__error" data-translate="<?= error('new_password') ?>"><?= error('new_password') ?></p>
                        <div class="form__element">
                            <label class="form__label" for="new_password_confirmation">Confirm the password</label>
                            <input class="form__input" id="new_password_confirmation" name="new_password_confirmation" type="password" minlength="8" value="<?= old('new_password_confirmation') ?>" placeholder="" required>
                        </div>
                        <button class="setting__button" type="submit">Update</button>
                    </form>
                </div>

                <div class="setting setting--column">
                    <h3 class="setting__title">Other options</h3>

                    <div class="setting__options">
                        <form action="/close-session" method="post">
                            <button class="setting__button" type="submit">Close session</button>
                        </form>

                        <form action="/delete-account" method="post">
                            <button class="setting__button setting__button--danger" type="submit">Delete account</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a class="w-fit mx-auto anchor" href="/access">Access to unlock your account settings</a>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php
require_once BASE . "resources/components/footer.php";
?>