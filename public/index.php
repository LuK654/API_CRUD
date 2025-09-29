<?php

/*
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
 --- FIM DO CÓDIGO CORS --- */

 // Define o fuso horário para evitar avisos
date_default_timezone_set('America/Sao_Paulo');

// Usa o autoloader do Composer (muito mais poderoso que o nosso antigo)
require __DIR__ . '/../vendor/autoload.php';


use DI\Container;
use Slim\Factory\AppFactory;

// --- Injeção de Dependência ---
$container = new Container();

// Define as dependências da aplicação a partir de um ficheiro externo
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($container);

// Informa ao Slim para usar o nosso contentor de DI
AppFactory::setContainer($container);

// --- Criação da Aplicação Slim ---
$app = AppFactory::create();

// Adiciona middlewares essenciais do Slim
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true); // (true, true, true) para ambiente de desenvolvimento


// --- Definição das Rotas ---
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// --- Executa a Aplicação ---
$app->run();


/*
---------------- Antigo Código -----------------------------------------------------------

// Autoload para as nossas classes
spl_autoload_register(function ($className) {
    $file = str_replace('\\', '/', $className) . '.php';
    
    // Procura nas pastas
    $paths = [
        '../app/Core/',
        '../app/Controllers/',
        '../app/Models/',
        '../app/Services/', // <-- ADICIONADO
        '../app/DTO/', //Adicionado 26/09/2025.
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
*/