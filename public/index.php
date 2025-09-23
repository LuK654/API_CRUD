<?php

// --- INÍCIO DO CÓDIGO PARA HABILITAR O CORS ---
// Permite requisições de qualquer origem. Para produção, você pode restringir a um domínio específico.
header("Access-Control-Allow-Origin: *");
// Permite os métodos HTTP que a sua API utiliza.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permite cabeçalhos específicos na requisição, como o Content-Type para JSON.
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// O navegador envia uma requisição "preflight" com o método OPTIONS para verificar as permissões.
// Se for uma requisição OPTIONS, apenas retornamos os cabeçalhos acima com status 200 OK e encerramos o script.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// --- FIM DO CÓDIGO CORS ---

// Autoload para as nossas classes
spl_autoload_register(function ($className) {
    $file = str_replace('\\', '/', $className) . '.php';
    
    // Procura nas pastas
    $paths = [
        '../app/Core/',
        '../app/Controllers/',
        '../app/Models/',
        '../app/Services/', // <-- ADICIONADO
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

