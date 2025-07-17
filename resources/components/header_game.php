<?php
$styles = $styles ?? [];
$scripts = $scripts ?? [];
array_unshift($styles, 'game');
array_unshift($scripts, 'game');
require_once BASE . 'resources/components/header.php';
?>
