<?php

class Router {
    public function run() {
        $url = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : [];
        
        // Controlador padrão: UsuarioController
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'UsuarioController';
        $controllerFile = '../app/Controllers/' . $controllerName . '.php';
       
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName;

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