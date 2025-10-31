<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TomTroc' ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="nav-brand">
                    <a href="<?= APP_URL ?>">
                        <h1><?= APP_NAME ?></h1>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="<?= APP_URL ?>">Accueil</a></li>
                    <li><a href="<?= APP_URL ?>/books">Nos livres à l'échange</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= APP_URL ?>/messages">Messagerie</a></li>
                        <li><a href="<?= APP_URL ?>/profile">Mon compte</a></li>
                        <li><a href="<?= APP_URL ?>/logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="<?= APP_URL ?>/login">Connexion</a></li>
                        <li><a href="<?= APP_URL ?>/register">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
