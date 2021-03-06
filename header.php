<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
    <meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/3810438a15.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <header class="site-header">
        <h1 class="site-title">Scriptor</h1>
        <nav>
            <ul class="site-nav">
                <li <?php if ('home' === $currentPage ) echo 'class="active"'?>><a href="index.php">Home</a></li>
                <li <?php if ('about' === $currentPage ) echo 'class="active"'?>><a href="about.php">About</a></li>
                <li <?php if ('contacts' === $currentPage ) echo 'class="active"'?>><a href="contacts.php">Contacts</a></li>
            </ul>
        </nav>
        <div class="div_line"></div>
        <div class="Foreword">
            Scriptor is a minimal, clean,
            modern & responsive Ghost theme for writers
        </div>
        <div class="div_line"></div>
    </header>

