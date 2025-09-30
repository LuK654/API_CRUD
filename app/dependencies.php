<?php
// Adicionamos o "use" para a classe que vamos registar.
use App\Controllers\UsuariosController;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Config\Database;
use DI\Container;

return function (Container $container) {
    // Regra para criar a ligação à base de dados (PDO)
    $container->set(PDO::class, function () {
        $dbConfig = new Database();
        return $dbConfig->connect();
    });

    // Regra para criar o UsuarioModel (injeta o PDO)
    $container->set(Usuario::class, fn(Container $c) => new Usuario($c->get(PDO::class)));

    // Regra para criar o UsuarioService (injeta o UsuarioModel)
    $container->set(UsuarioService::class, fn(Container $c) => new UsuarioService($c->get(Usuario::class)));

    // AQUI ESTÁ A CORREÇÃO:
    // Regra para criar o UsuariosController (injeta o UsuarioService).
    // Agora o contentor de DI sabe como construir a cadeia completa de dependências.
  //  $container->set(UsuariosController::class, fn(Container $c) => new UsuariosController($c->get(UsuarioService::class)));
};

