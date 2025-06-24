<?php
require_once BASE . "resources/components/header.php";
?>

<h1>Connect4</h1>

<p>
    Connect4 strives to be a quick entertainment site, we'll never ask for your
    email address to enjoy our content. Log in or create an account with just a
    username and password.
</p>

<!-- Login form -->
<form id="signin" action="/signin" method="post" novalidate>
    <h2>Login</h2>

    <label for="signin_user">Username</label>
    <input id="signin_user" name="user" type="text" minlength="5" maxlength="50" value="<?= old('signin_user') ?? '' ?>" required>
    <p><?= error('signin_user') ?></p>

    <label for="signin_password">Password</label>
    <input id="signin_password" name="password" type="password" minlength="8" value="<?= old('signin_password') ?? '' ?>" required>
    <p><?= error('signin_password') ?></p>

    <button type="submit">Sign in</button>
</form>

<!-- Sign up form -->
<form id="signup" action="/signup" method="post">
    <h2>Login</h2>

    <label for="signup_user">Username</label>
    <input id="signup_user" name="user" type="text" minlength="5" maxlength="50" required>

    <label for="signup_password">Password</label>
    <input id="signup_password" name="password" type="password" minlength="8" required>

    <label for="signup_password_confirmation">Confirm your password</label>
    <input id="signup_password_confirmation" name="password_confirmation" type="password" minlength="8" required>

    <button type="submit">Continue</button>
</form>

<?php
require_once BASE . "resources/components/footer.php";
?>