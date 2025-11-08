<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TomTroc' ?> - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css?v=<?= time() ?>">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="nav-brand">
                    <a href="<?= APP_URL ?>" class="logo">
                        <img src="<?= APP_URL ?>/images/logo.svg" alt="Tom Troc Logo" class="logo-img">
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="<?= APP_URL ?>">Accueil</a></li>
                    <li><a href="<?= APP_URL ?>/books">Nos livres à l'échange</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= APP_URL ?>/messages">Messagerie</a></li>
                        <li><a href="<?= APP_URL ?>/account">Mon compte</a></li>
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
