<?php
// Shared bootstrap for autoloading, sessions, and helpers.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(static function (string $class): void {
    $baseDir = __DIR__;
           $paths = [
            $baseDir . '/controllers/' . $class . '.php',
            $baseDir . '/models/' . $class . '.php',
            $baseDir . '/services/' . $class . '.php',
        ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

require_once __DIR__ . '/helpers.php';
