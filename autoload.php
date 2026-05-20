<?php

spl_autoload_register(function ($class) {
    $directories = [
        'app/',
        'app/controller/',
        'app/model/',
        'app/middleware/',
        'app/services/',
        'app/migration/',
        'app/router/',
    ];

    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
