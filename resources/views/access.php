<?php
$title = 'Access page';
$styles = ['access'];
$scripts = ['access'];
$main_classes = 'main';
require_once BASE . 'resources/components/header.php';
?>

<h1 class="main__title">Access page</h1>

<!-- Login form -->
<form id="form-signin" class="form form--hidden form--red" action="/signin" method="post" novalidate>
    <div class="form__content">
        <h2 class="form__title">Login</h2>
        <!-- <p class="form__description">
            Connect4 strives to be a quick entertainment site, we'll never ask for your
            email address to enjoy our content. Log in or create an account with just a
            username and password.
        </p> -->

        <div class="fieldset">
            <div class="field">
                <div class="field__element">
                    <input id="signin_user" class="field__input" name="user" type="text" minlength="5" maxlength="50" value="<?= old('signin_user') ?>" placeholder="" required>
                    <label for="signin_user" class="field__label">Username</label>
                </div>
            </div>

            <div class="field">
                <div class="field__element">
                    <input id="signin_password" class="field__input" name="password" type="password" minlength="8" value="<?= old('signin_password') ?>" placeholder="" required>
                    <label for="signin_password" class="field__label">Password</label>
                </div>
                <p class="field__error"><?= error('signin') ?></p>
            </div>
        </div>

        <div class="form__options">
            <button class="form__button" type="submit">Sign in</button>
            <a href="/access?section=signup&animation=true" class="anchor text-center" data-action="change-section" type="submit">Don't have an account yet?</a>
        </div>
    </div>
</form>

<!-- Sign up form -->
<form id="form-signup" class="form form--hidden form--blue" action="/signup" method="post" novalidate>
    <div class="form__content">
        <h2 class="form__title">Sign up</h2>
        <!-- <p class="form__description">
            Connect4 strives to be a quick entertainment site, we'll never ask for your
            email address to enjoy our content. Log in or create an account with just a
            username and password.
        </p> -->

        <div class="fieldset">
            <div class="field">
                <div class="field__element">
                    <input id="signup_user" class="field__input" name="user" type="text" minlength="5" maxlength="50" value="<?= old('signup_user') ?>" placeholder="" required>
                    <label for="signup_user" class="field__label">Username</label>
                </div>
                <p class="field__error"><?= error('signup_user') ?></p>
            </div>

            <div class="field">
                <div class="field__element">
                    <input id="signup_password" class="field__input" name="password" type="password" minlength="8" value="<?= old('signup_password') ?>" placeholder="" required>
                    <label for="signup_password" class="field__label">Password</label>
                </div>
                <p class="field__error"><?= error('signup_password') ?></p>
            </div>

            <div class="field">
                <div class="field__element">
                    <input id="signup_password_confirmation" class="field__input" name="password_confirmation" type="password" minlength="8" value="<?= old('signup_password_confirmation') ?>" placeholder="" required>
                    <label for="signup_password_confirmation" class="field__label">Confirm your password</label>
                </div>
                <p class="field__error"><?= error('signup') ?></p>
            </div>
        </div>


        <div class="form__options">
            <button class="form__button" type="submit">Continue</button>
            <a href="/access?section=signin&animation=true" class="anchor text-center" data-action="change-section" type="submit">Already have an account?</a>
        </div>
    </div>
</form>

<!-- End of the document -->
</main>

</body>

</html>