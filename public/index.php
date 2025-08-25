<?php

// Autoload para as nossas classes
spl_autoload_register(function ($className) {
    $file = str_replace('\\', '/', $className) . '.php';
    
    // Procura nas pastas app/Core, app/Controllers, app/Models, config
    $paths = [
        '../app/Core/',
        '../app/Controllers/',
        '../app/Models/',
        '../config/'
    ];

    foreach ($paths as $path) {
        if (file_exists($path . $file)) {
            require_once $path . $file;
            return;
        }
    }
});

// Instancia e executa o roteador
$router = new Router();
$router->run();