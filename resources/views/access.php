<?php
$title = 'Access page';
$styles = ['access'];
$scripts = ['access'];
$main_classes = 'main';
require_once BASE . 'resources/components/header.php';
?>

<h1 class="main__title" data-translate="Access page">Access page</h1>

<!-- Login form -->
<form id="form-signin" class="form form--hidden form--red" action="/signin" method="post" novalidate>
    <div class="form__content">
        <h2 class="form__title" data-translate="Login">Login</h2>
        <!-- <p class="form__description" data-translate="Connect4 wants to be a fast-paced entertainment site. We'll never ask for your email address to enjoy our content. Sign in or create an account with just your username and password.">
            Connect4 wants to be a fast-paced entertainment site. We'll never
            ask for your email address to enjoy our content. Sign in or create
            an account with just your username and password.
        </p> -->

        <div class="fieldset">
            <div class="field">
                <div class="field__element">
                    <input id="signin_username" class="field__input" name="username" type="text" minlength="5" maxlength="50" value="<?= old('signin_username') ?>" placeholder="" required>
                    <label for="signin_username" class="field__label" data-translate="Username">Username</label>
                </div>
            </div>

            <div class="field">
                <div class="field__element">
                    <input id="signin_password" class="field__input" name="password" type="password" minlength="8" value="<?= old('signin_password') ?>" placeholder="" required>
                    <label for="signin_password" class="field__label" data-translate="Password">Password</label>
                </div>
                <p class="field__error" data-translate="<?= error('signin') ?>"><?= error('signin') ?></p>
            </div>
        </div>

        <div class="form__options">
            <button class="form__button" type="submit" data-translate="Sign in">Sign in</button>
            <a href="/access?section=signup&animation=true" class="anchor text-center" data-action="change-section" type="submit" data-translate="Don't have an account yet?">Don't have an account yet?</a>
        </div>
    </div>
</form>

<!-- Sign up form -->
<form id="form-signup" class="form form--hidden form--blue" action="/signup" method="post" novalidate>
    <div class="form__content">
        <h2 class="form__title" data-translate="Sign up">Sign up</h2>
        <!-- <p class="form__description" data-translate="Connect4 wants to be a fast-paced entertainment site. We'll never ask for your email address to enjoy our content. Sign in or create an account with just your username and password.">
            Connect4 wants to be a fast-paced entertainment site. We'll never
            ask for your email address to enjoy our content. Sign in or create
            an account with just your username and password.
        </p> -->

        <div class="fieldset">
            <div class="field">
                <div class="field__element">
                    <input id="signup_username" class="field__input" name="username" type="text" minlength="5" maxlength="50" value="<?= old('signup_username') ?>" placeholder="" required>
                    <label for="signup_username" class="field__label" data-translate="Username">Username</label>
                </div>
                <p class="field__error" data-translate="<?= error('signup_username') ?>"><?= error('signup_username') ?></p>
            </div>

            <div class="field">
                <div class="field__element">
                    <input id="signup_password" class="field__input" name="password" type="password" minlength="8" value="<?= old('signup_password') ?>" placeholder="" required>
                    <label for="signup_password" class="field__label" data-translate="Password">Password</label>
                </div>
                <p class="field__error" data-translate="<?= error('signup_password') ?>"><?= error('signup_password') ?></p>
            </div>

            <div class="field">
                <div class="field__element">
                    <input id="signup_password_confirmation" class="field__input" name="password_confirmation" type="password" minlength="8" value="<?= old('signup_password_confirmation') ?>" placeholder="" required>
                    <label for="signup_password_confirmation" class="field__label" data-translate="Confirm your password">Confirm your password</label>
                </div>
                <p class="field__error" data-translate="<?= error('signup') ?>"><?= error('signup') ?></p>
            </div>
        </div>


        <div class="form__options">
            <button class="form__button" type="submit" data-translate="Continue">Continue</button>
            <a href="/access?section=signin&animation=true" class="anchor text-center" data-action="change-section" type="submit" data-translate="Already have an account?">Already have an account?</a>
        </div>
    </div>
</form>

<!-- End of the document -->
</main>

</body>

</html>