<?php

/**
 * Autoloader personnalisé
 * Charge automatiquement les classes nécessaires
 */

spl_autoload_register(function ($className) {
    $directories = [
        ROOT_PATH . '/models/',
        ROOT_PATH . '/controllers/',
        ROOT_PATH . '/config/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
