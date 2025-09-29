<?php

use App\Controllers\UsuariosController;
use Slim\App;

return function (App $app) {
    echo $app->getBasePath();
    $app->setBasePath('/api_crud');
    // Note como as URLs agora são limpas e RESTful!
    $app->get('/usuarios', [UsuariosController::class, 'buscarGeral']);
    $app->get('/usuarios/{id}', [UsuariosController::class, 'buscar']);
    $app->post('/usuarios', [UsuariosController::class, 'criar']);
    $app->put('/usuarios/{id}', [UsuariosController::class, 'atualizar']);
    $app->delete('/usuarios/{id}', [UsuariosController::class, 'deletar']);
};
