<?php

use App\Models\Usuario;
use App\Services\UsuarioService;
use Config\Database;
use DI\Container;
use PDO;

return function (Container $container) {
    // Regra para criar a conexão com o banco de dados (PDO)
    $container->set(PDO::class, function () {
        $dbConfig = new Database();
        return $dbConfig->connect();
    });
    // Regra para criar o UsuarioModel (injeta o PDO)
    $container->set(Usuario::class, fn(Container $c) => new Usuario($c->get(PDO::class)));
    // Regra para criar o UsuarioService (injeta o UsuarioModel)
    $container->set(UsuarioService::class, fn(Container $c) => new UsuarioService($c->get(Usuario::class)));
};