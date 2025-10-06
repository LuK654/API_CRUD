<?php
namespace App\Services;

use App\DTO\CreateUserDTO;
use App\DTO\UserResponseDTO;
use App\Models\Usuario;
use Exception;
use InvalidArgumentException; // Vamos usar uma exceção mais específica

class UsuarioService {
    private $usuarioModel;

    public function __construct(Usuario $usuarioModel) {
        $this->usuarioModel = $usuarioModel;
    }

    public function getTodos() {
        return array_map(fn($usuario) => new UserResponseDTO($usuario), $this->usuarioModel->buscarTodos());
    }

    public function getPorId($id) {
        $usuarioArray = $this->usuarioModel->buscarPorId($id);
        if (!$usuarioArray) {
            throw new Exception('Utilizador não encontrado.', 404);
        }
        return new UserResponseDTO($usuarioArray);
    }

    public function criarUsuario(CreateUserDTO $dto) {
        if ($this->usuarioModel->buscarPorEmail($dto->email)) {
            throw new Exception('Este email já está em uso.', 409); // Conflict
        }
        
        $dadosParaCriar = [
            'nome' => htmlspecialchars(strip_tags($dto->nome)),
            'email' => htmlspecialchars(strip_tags($dto->email)),
            'senha' => password_hash($dto->senha, PASSWORD_DEFAULT)
        ];

        $novoUsuarioId = $this->usuarioModel->criar($dadosParaCriar);

        if (!$novoUsuarioId) {
            throw new Exception('Não foi possível criar o utilizador.', 500);
        }
        
        return ['id' => $novoUsuarioId, 'message' => 'Utilizador criado com sucesso.'];
    }

    public function atualizarUsuario($id, $data) {
        // Validação de dados de entrada
        if (!isset($data->nome) || !isset($data->email)) {
            throw new InvalidArgumentException('Dados incompletos para atualização.', 400);
        }

        // Garante que o utilizador a ser atualizado existe
        $this->getPorId($id);

        // --- INÍCIO DA CORREÇÃO ---
        // NOVA REGRA DE NEGÓCIO: Verificar se o novo email já está em uso por OUTRO utilizador.
        $usuarioExistente = $this->usuarioModel->buscarPorEmail($data->email);

        if ($usuarioExistente && $usuarioExistente['id'] != $id) {
            // Se encontrámos um utilizador com este email, e o ID dele é DIFERENTE
            // do ID que estamos a atualizar, então é um conflito.
            throw new Exception('Este email já está a ser utilizado por outro utilizador.', 409); // Conflict
        }
        // --- FIM DA CORREÇÃO ---

        // Sanitização
        $dadosParaAtualizar = [
            'nome' => htmlspecialchars(strip_tags($data->nome)),
            'email' => htmlspecialchars(strip_tags($data->email))
        ];

        $this->usuarioModel->atualizar($id, $dadosParaAtualizar);
        
        return ['message' => 'Utilizador atualizado com sucesso.'];
    }

    public function deletarUsuario($id) {
        $this->getPorId($id);
        if (!$this->usuarioModel->deletar($id)) {
            throw new Exception('Não foi possível eliminar o utilizador.', 500);
        }
        return ['message' => 'Utilizador eliminado com sucesso.'];
    }
}