<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TomTroc' ?> - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
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
                
                <?php
                $requestUri = $_SERVER['REQUEST_URI'];
                $appPath = parse_url(APP_URL, PHP_URL_PATH) ?? '/';
                $isHome = $requestUri === $appPath || $requestUri === $appPath . '/' || $requestUri === $appPath . '/index.php';
                ?>

                <div class="nav-links">
                    <a href="<?= APP_URL ?>" class="nav-link <?= $isHome ? 'active' : '' ?>">Accueil</a>
                    <a href="<?= APP_URL ?>/books" class="nav-link <?= strpos($requestUri, '/books') !== false ? 'active' : '' ?>">Nos livres à l'échange</a>
                </div>

                <div class="nav-user">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        // Récupérer le nombre de messages non lus
                        $unreadCount = 0;
                        try {
                            require_once __DIR__ . '/../../models/Message.php';
                            $messageModel = new Message();
                            $unreadCount = $messageModel->countUnread($_SESSION['user_id']);
                        } catch (Exception $e) {
                            // Silently fail if model not available
                        }
                        ?>
                        <a href="<?= APP_URL ?>/messages" class="nav-link icon-link messages-link <?= strpos($requestUri, '/messages') !== false ? 'active' : '' ?>">
                            <img src="<?= APP_URL ?>/images/Icon messagerie.svg" alt="Messagerie" class="nav-icon-svg">
                            <span>Messagerie</span>
                            <?php if ($unreadCount > 0): ?>
                                <span class="unread-badge"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="user-dropdown">
                            <a href="<?= APP_URL ?>/account" class="nav-link user-link <?= strpos($requestUri, '/account') !== false ? 'active' : '' ?>">
                                <img src="<?= APP_URL ?>/images/Icon mon compte.svg" alt="Mon compte" class="nav-icon-svg">
                                <span>Mon compte</span>
                            </a>
                            <a href="<?= APP_URL ?>/logout" class="nav-link logout-link">Déconnexion</a>
                        </div>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/login" class="nav-link">Connexion</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="main-content">
