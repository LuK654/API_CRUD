<?php

class Router {
    public function run() {
        $url = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : [];
        
        // CORREÇÃO AQUI:
        // Agora ele pega a primeira parte da URL (ex: 'usuarios') e apenas anexa 'Controller'.
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'UsuariosController';
        $controllerFile = '../app/Controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            // --- Injeção de Dependência ---
            $database = new Database();
            $db = $database->connect();
            $usuarioModel = new Usuario($db);
            $usuarioService = new UsuarioService($usuarioModel);
            $controller = new $controllerName($usuarioService);
            // --- Fim da Injeção de Dependência ---

            $methodName = isset($url[1]) && method_exists($controller, $url[1]) ? $url[1] : 'buscarGeral';
            
            $params = array_slice($url, 2);

            call_user_func_array([$controller, $methodName], $params);

        } else {
            // Se o arquivo não existe, esta mensagem é enviada.
            JsonResponse::send(['message' => 'Endpoint não encontrado: ' . $controllerName], 404);
        }
    }
}

