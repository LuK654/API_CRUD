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

public function getTodos() {
    // 1. Buscamos a lista de usuários.
    $usuariosArray = $this->usuarioModel->buscarTodos();

    // 2. Usamos array_map para aplicar uma função a cada item da lista.
    // A função que estamos aplicando é a criação de um novo UserResponseDTO.
    return array_map(function($usuario) {
        return new UserResponseDTO($usuario);
    }, $usuariosArray);
}

    public function getPorId($id) {
    // 1. Buscamos os dados crus do Model, como antes.
    $usuarioArray = $this->usuarioModel->buscarPorId($id);

    // 2. Verificamos se o usuário foi encontrado.
    if (!$usuarioArray) {
        throw new Exception('Usuário não encontrado.', 404);
    }
        // Em vez de retornar o array, criamos uma nova instância do DTO,
        // passando o array de dados do usuário para o seu construtor.
        return new UserResponseDTO($usuarioArray);
    }

    public function criarUsuario($data) {
        // 1. Validação dos dados de entrada
        if (!isset($data->nome) || !isset($data->email) || !isset($data->senha)) {
            throw new Exception('Dados incompletos. Nome, email e senha são obrigatórios.', 400); // Bad Request
        }

        // 2. Regra de Negócio: Verificar se o email já existe
        if ($this->usuarioModel->buscarPorEmail($data->email)) {
            throw new Exception('Este email já está em uso.', 409); // Conflict
        }
        
        // 3. Sanitização e Preparação dos dados
        $dadosParaCriar = [
            'nome' => htmlspecialchars(strip_tags($data->nome)),
            'email' => htmlspecialchars(strip_tags($data->email)),
            // 4. Regra de Negócio: Criptografia da senha
            'senha' => password_hash($data->senha, PASSWORD_DEFAULT)
        ];

        // 5. Chamada ao Model para persistir os dados
        $novoUsuarioId = $this->usuarioModel->criar($dadosParaCriar);

        if (!$novoUsuarioId) {
            throw new Exception('Não foi possível criar o usuário.', 500);
        }
        
        return ['id' => $novoUsuarioId, 'message' => 'Usuário criado com sucesso.'];
    }

    public function atualizarUsuario($id, $data) {
        // 1. Validação
        if (!isset($data->nome) || !isset($data->email)) {
            throw new Exception('Dados incompletos para atualização.', 400);
        }

        // 2. Regra de Negócio: Garantir que o usuário a ser atualizado existe
        $this->getPorId($id); // Reutiliza o método que já lança exceção 404 se não encontrar

        // 3. Sanitização
        $dadosParaAtualizar = [
            'nome' => htmlspecialchars(strip_tags($data->nome)),
            'email' => htmlspecialchars(strip_tags($data->email))
        ];

        if (!$this->usuarioModel->atualizar($id, $dadosParaAtualizar)) {
            // Pode significar que não houve erro, mas nenhum dado foi alterado.
            // Dependendo da regra, você pode tratar isso de forma diferente.
            // Aqui, vamos considerar um sucesso se não houver erro.
        }
        
        return ['message' => 'Usuário atualizado com sucesso.'];
    }

    public function deletarUsuario($id) {
        // Regra de Negócio: Garantir que o usuário existe antes de tentar deletar
        $this->getPorId($id);

        if (!$this->usuarioModel->deletar($id)) {
            throw new Exception('Não foi possível deletar o usuário.', 500);
        }

        return ['message' => 'Usuário deletado com sucesso.'];
    }
}
