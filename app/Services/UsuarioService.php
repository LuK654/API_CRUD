<?php
namespace App\Services;

use App\DTO\CreateUserDTO;
use App\DTO\UserResponseDTO;
use App\Models\Usuario;
use Exception;

class UsuarioService {
    private $usuarioModel;

    public function __construct(Usuario $usuarioModel) {
        $this->usuarioModel = $usuarioModel;
    }

    public function getTodos(): array {
        $usuariosArray = $this->usuarioModel->buscarTodos();
        return array_map(function($usuario) {
            return new UserResponseDTO($usuario);
        }, $usuariosArray);
    }

    public function getPorId($id): UserResponseDTO {
        $usuarioArray = $this->usuarioModel->buscarPorId($id);
        if (!$usuarioArray) {
            throw new Exception('Utilizador não encontrado.', 404);
        }
        return new UserResponseDTO($usuarioArray);
    }

    /**
     * MÉTODO CORRIGIDO
     * A assinatura agora exige um objeto CreateUserDTO. Isto é o "contrato".
     * @param CreateUserDTO $dto O objeto de transferência de dados já validado.
     */
    public function criarUsuario(CreateUserDTO $dto): array {
        // A validação de dados incompletos foi REMOVIDA daqui.
        // O DTO já garantiu que os dados são válidos.

        // Regra de Negócio: Verificar se o email já existe.
        // Acedemos aos dados através do DTO.
        if ($this->usuarioModel->buscarPorEmail($dto->email)) {
            throw new Exception('Este email já está em uso.', 409); // Conflict
        }
        
        // Sanitização e Preparação dos dados.
        $dadosParaCriar = [
            'nome' => htmlspecialchars(strip_tags($dto->nome)),
            'email' => htmlspecialchars(strip_tags($dto->email)),
            // Regra de Negócio: Criptografia da senha.
            'senha' => password_hash($dto->senha, PASSWORD_DEFAULT)
        ];

        // Chamada ao Model para persistir os dados.
        $novoUsuarioId = $this->usuarioModel->criar($dadosParaCriar);

        if (!$novoUsuarioId) {
            throw new Exception('Não foi possível criar o utilizador.', 500);
        }
        
        return ['id' => $novoUsuarioId, 'message' => 'Utilizador criado com sucesso.'];
    }

    public function atualizarUsuario($id, $data): array {
        if (!isset($data->nome) || !isset($data->email)) {
            throw new Exception('Dados incompletos para atualização.', 400);
        }
        $this->getPorId($id); // Reutiliza o método que já lança exceção 404 se não encontrar
        $dadosParaAtualizar = [
            'nome' => htmlspecialchars(strip_tags($data->nome)),
            'email' => htmlspecialchars(strip_tags($data->email))
        ];
        $this->usuarioModel->atualizar($id, $dadosParaAtualizar);
        return ['message' => 'Utilizador atualizado com sucesso.'];
    }

    public function deletarUsuario($id): array {
        $this->getPorId($id);
        if (!$this->usuarioModel->deletar($id)) {
            throw new Exception('Não foi possível apagar o utilizador.', 500);
        }
        return ['message' => 'Utilizador apagado com sucesso.'];
    }
}

