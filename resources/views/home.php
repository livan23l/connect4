<?php
$title = "Home page";
$styles = ['home'];
$scripts = ['home'];
require_once BASE . "resources/components/header.php";
?>

<!-- Discs flying on the page -->
<img class="disc disc--1" src="img/disc_01.png" alt="Disc 1">
<img class="disc disc--2" src="img/disc_02.png" alt="Disc 2">
<img class="disc disc--3" src="img/disc_03.png" alt="Disc 3">
<img class="disc disc--4" src="img/disc_04.png" alt="Disc 4">
<img class="disc disc--5" src="img/disc_05.png" alt="Disc 5">
<img class="disc disc--6" src="img/disc_06.png" alt="Disc 6">
<img class="disc disc--7" src="img/disc_07.png" alt="Disc 7">
<img class="disc disc--8" src="img/disc_08.png" alt="Disc 8">

<!-- Principal hero -->
<section class="hero">
    <a href="/play" class="btn btn--blue btn--backdrop text-2xl font-bold uppercase fade-in" data-translate="Play">Play</a>
</section>

<!-- Content arrow -->
<a href="#about-us" id="arrow" class="arrow arrow--hidden">↓</a>

<!-- About us -->
<main class="about">
    <h1 id="about-us" class="about__title" data-translate="About us">About us</h1>

    <!-- Sections -->
    <div class="about__sections">
        <!-- About the game -->
        <div class="about__section">
            <article class="about__content">
                <h2 class="about__subtitle" data-translate="About the game">About the game</h2>
                <p class="about__text">
                    <span id="test" data-translate="Connect 4 is a simple, fast-paced, and very popular game that combines strategy and fun. The goal is to connect four same-colored discs in a row on a vertical board.">Connect 4 is a simple, fast-paced, and very popular game that combines strategy and fun. The goal is to connect four same-colored discs in a row on a vertical board.</span>
                    <a class="anchor" href="/play" data-translate="Try it for free.">Try it for free.</a>
                </p>
            </article>
            <figure class="about__figure">
                <img class="about__image" src="img/game.webp" alt="Image of hands playing connect 4">
            </figure>
        </div>

        <!-- About the site -->
        <div class="about__section about__section--reverse">
            <article class="about__content">
                <h2 class="about__subtitle" data-translate="About the site">About the site</h2>
                <p class="about__text">
                    <span data-translate="We're a free site where you can play Connect 4 with friends or on your own. We offer a variety of challenges to make your experience as fun and enjoyable as possible. Just">We're a free site where you can play Connect 4 with friends or on your own. We offer a variety of challenges to make your experience as fun and enjoyable as possible. Just</span>
                    <a class="anchor" href="/access" data-translate="create your account">create your account</a>
                    <span data-translate="and enjoy!">and enjoy!</span>
                </p>
            </article>
            <figure class="about__figure">
                <img class="about__image" src="img/page.webp" alt="Representative image of the page">
            </figure>
        </div>

        <!-- About the Connect4 project -->
        <div class="about__section">
            <article class="about__content">
                <h2 class="about__subtitle" data-translate="About the Connect4 project">About the Connect4 project</h2>
                <p class="about__text">
                    <span data-translate="Connect4 is an open-source project on">Connect4 is an open-source project on</span>
                    <a class="anchor" href="https://github.com/livan23l/connect4" target="_blank" data-translate="GitHub">GitHub</a>
                    <span data-translate="designed to be fast and fun. We don't ask for an email address to log in because we'll never spam you. Let us know if you have any issues or ideas via our">designed to be fast and fun. We don't ask for an email address to log in because we'll never spam you. Let us know if you have any issues or ideas via our</span>
                    <a class="anchor" href="/contact" data-translate="contact form.">contact form.</a>
                </p>
            </article>
            <figure class="about__figure">
                <img class="about__image" src="img/project.webp" alt="Representative image of the project">
            </figure>
        </div>
    </div>
</main>

<!-- Change language -->
<div class="language">
    <div data-language="en" class="language__option">
        <img class="language__image" src="img/langs/en.png" alt="Flag of EEUU">
        <p class="language__name">English</p>
    </div>
    <div data-language="es" class="language__option">
        <img class="language__image" src="img/langs/es.png" alt="Flag of Mexico">
        <p class="language__name">Español</p>
    </div>
    <div data-language="pt" class="language__option">
        <img class="language__image" src="img/langs/pt.png" alt="Flag of Brazil">
        <p class="language__name">Português</p>
    </div>
    <div data-language="fr" class="language__option">
        <img class="language__image" src="img/langs/fr.png" alt="Flag of France">
        <p class="language__name">Français</p>
    </div>
</div>

<?php
require_once BASE . "resources/components/footer.php";
?>