<?php

class UsuariosController {
    
    // Método para buscar todos os usuários (GET /usuarios)
    public function index() {
        $database = new Database();
        $db = $database->connect();

        $usuario = new Usuario($db);
        $result = $usuario->buscarTodos();
        $num = $result->rowCount();

        if($num > 0) {
            $usuarios_arr = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $usuario_item = array(
                    'id' => $id,
                    'nome' => $nome,
                    'email' => $email
                );
                array_push($usuarios_arr, $usuario_item);
            }
            JsonResponse::send($usuarios_arr);
        } else {
            JsonResponse::send(array('message' => 'Nenhum usuário encontrado.'), 404);
        }
    }

    // Método para buscar um usuário por ID (GET /usuarios/buscar/1)
    public function buscar($id) {
        $database = new Database();
        $db = $database->connect();
        $usuario = new Usuario($db);
        
        $usuario->buscarPorId($id);

        if($usuario->nome != null) {
            $usuario_arr = array(
                'id' => $usuario->id,
                'nome' => $usuario->nome,
                'email' => $usuario->email
            );
            JsonResponse::send($usuario_arr);
        } else {
            JsonResponse::send(array('message' => 'Usuário não encontrado.'), 404);
        }
    }
    
    // Método para criar um usuário (POST /usuarios/criar)
    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send(['message' => 'Método não permitido'], 405);
            return;
        }

        $database = new Database();
        $db = $database->connect();
        $usuario = new Usuario($db);

        $data = json_decode(file_get_contents("php://input"));
        
        if(!$data || !isset($data->nome) || !isset($data->email) || !isset($data->senha)) {
            JsonResponse::send(array('message' => 'Dados incompletos.'), 400);
            return;
        }

        $usuario->nome = $data->nome;
        $usuario->email = $data->email;
        $usuario->senha = $data->senha;

        if($usuario->criar()) {
            JsonResponse::send(array('message' => 'Usuário criado com sucesso.'), 201);
        } else {
            JsonResponse::send(array('message' => 'Não foi possível criar o usuário.'), 500);
        }
    }

    // Método para atualizar um usuário (PUT /usuarios/atualizar/1)
    public function atualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            JsonResponse::send(['message' => 'Método não permitido'], 405);
            return;
        }
        
        $database = new Database();
        $db = $database->connect();
        $usuario = new Usuario($db);
        
        $data = json_decode(file_get_contents("php://input"));
        
        if(!$data || !isset($data->nome) || !isset($data->email)) {
             JsonResponse::send(array('message' => 'Dados incompletos para atualização.'), 400);
            return;
        }

        $usuario->nome = $data->nome;
        $usuario->email = $data->email;

        if($usuario->atualizar($id)) {
            JsonResponse::send(array('message' => 'Usuário atualizado com sucesso.'));
        } else {
            JsonResponse::send(array('message' => 'Não foi possível atualizar o usuário.'), 500);
        }
    }

    // Método para deletar um usuário (DELETE /usuarios/deletar/1)
    public function deletar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            JsonResponse::send(['message' => 'Método não permitido'], 405);
            return;
        }

        $database = new Database();
        $db = $database->connect();
        $usuario = new Usuario($db);

        if($usuario->deletar($id)) {
            JsonResponse::send(array('message' => 'Usuário deletado com sucesso.'));
        } else {
            JsonResponse::send(array('message' => 'Não foi possível deletar o usuário.'), 500);
        }
    }
}