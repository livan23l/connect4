<!DOCTYPE html>
<html lang="en" data-lang="<?= $_SESSION['language'] ?? '' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="<?= $title ?? "Connect4" ?>"><?= $title ?? "Connect4" ?></title>
    <base href="<?= HTML_BASE ?>">
    <link rel="stylesheet" href="css/app.css">
    <script type="module" src="js/app.js"></script>
    <link rel="shortcut icon" href="img/favicon.webp">
    <meta name="description" content="This is classic connect four game made with HTML, CSS, and JS, available in Spanish and English. Play alone or with friends and unlock achievements on the site.">
    <meta name="keywords" content="connect 4, connect four, game, online game, connect 4 online, connect 4 offline, connect four online, connect four offline">
    <!-- Include aditional css files -->
    <?php if (isset($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="css/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- Include aditional js files -->
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script defer src="js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="page">
    <?php if (isset($_SESSION['alert'])): ?>
        <div class="server-alert server-alert--<?= $_SESSION['alert']['type'] ?>">
            <p class="server-alert__message" data-translate="<?= $_SESSION['alert']['message'] ?>"><?= $_SESSION['alert']['message'] ?></p>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>