<?php

class UsuariosController {
    
    private $usuarioService;

    // O serviço é injetado pelo construtor
    public function __construct(UsuarioService $usuarioService) {
        $this->usuarioService = $usuarioService;
    }

    // GET /usuarios
    public function buscarGeral() {
        try {
            $usuarios = $this->usuarioService->getTodos();
            if (empty($usuarios)) {
                JsonResponse::send(['message' => 'Nenhum usuário encontrado.'], 404);
            } else {
                JsonResponse::send($usuarios);
            }
        } catch (Exception $e) {
            JsonResponse::send(['message' => $e->getMessage()], 500);
        }
    }

    // GET /usuarios/buscar/{id}
    public function buscar($id) {
        try {
            $usuario = $this->usuarioService->getPorId($id);
            JsonResponse::send($usuario);
        } catch (Exception $e) {
            // Usa o código da exceção que definimos no serviço (ex: 404)
            JsonResponse::send(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
    
    // POST /usuarios/criar
    public function criar() {
        $data = json_decode(file_get_contents("php://input"));
        try {
            // 1. Criamos o DTO usando os dados brutos. A validação acontece aqui dentro.
            $createUserDTO = new CreateUserDTO($data);
            
            // 2. Passamos o objeto DTO (e não mais o $data genérico) para o serviço.
            $resultado = $this->usuarioService->criarUsuario($createUserDTO);

            JsonResponse::send($resultado, 201);
        } catch (Exception $e) {
            JsonResponse::send(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
    
    // PUT /usuarios/atualizar/{id}
    public function atualizar($id) {
        $data = json_decode(file_get_contents("php://input"));
        try {
            $resultado = $this->usuarioService->atualizarUsuario($id, $data);
            JsonResponse::send($resultado);
        } catch (Exception $e) {
            JsonResponse::send(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    // DELETE /usuarios/deletar/{id}
    public function deletar($id) {
        try {
            $resultado = $this->usuarioService->deletarUsuario($id);
            JsonResponse::send($resultado);
        } catch (Exception $e) {
            JsonResponse::send(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
