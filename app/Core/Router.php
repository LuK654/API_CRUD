<?php

class Router {
    public function run() {
        $url = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : [];
        
        // Controlador padrão: UsuariosController (corrigido o nome para plural)
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'sController' : 'UsuariosController';
        $controllerFile = '../app/Controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            // --- Injeção de Dependência ---
            // 1. Cria a conexão com o banco de dados
            $database = new Database();
            $db = $database->connect();

            // 2. Cria a instância do Model, passando a conexão
            $usuarioModel = new Usuario($db);

            // 3. Cria a instância do Service, passando o Model
            $usuarioService = new UsuarioService($usuarioModel);
            
            // 4. Cria a instância do Controller, passando o Service
            $controller = new $controllerName($usuarioService);
            // --- Fim da Injeção de Dependência ---


            // Método padrão: index
            $methodName = isset($url[1]) && method_exists($controller, $url[1]) ? $url[1] : 'index';
            
            // Parâmetros
            $params = array_slice($url, 2);

            // Chama o método, passando os parâmetros
            call_user_func_array([$controller, $methodName], $params);

        } else {
            JsonResponse::send(['message' => 'Endpoint não encontrado'], 404);
        }
    }
}
