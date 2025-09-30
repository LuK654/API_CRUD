<?php

use App\Controllers\UsuariosController;
use Slim\App;

return function (App $app) {
    $app->get('/hello', function ($request, $response) {
        $response->getBody()->write('Hello, Slim is working!');
        return $response;
    });
    // Note como as URLs agora são limpas e RESTful!
    $app->get('/usuarios', [UsuariosController::class, 'buscarGeral']);
    $app->get('/usuarios/{id}', [UsuariosController::class, 'buscar']);
    $app->post('/usuarios', [UsuariosController::class, 'criar']);
    $app->put('/usuarios/{id}', [UsuariosController::class, 'atualizar']);
    $app->delete('/usuarios/{id}', [UsuariosController::class, 'deletar']);
};
