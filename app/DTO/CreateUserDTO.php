<?php 
namespace App\DTO;

use Exception;

class CreateUserDTO {
        public readonly string $nome;
        public readonly string $email;
        public readonly string $senha;
        public function __construct(object $data) {
        // A lógica de validação foi movida para cá.
        // O DTO agora é responsável por garantir sua própria integridade.
        if (!isset($data->nome) || !isset($data->email) || !isset($data->senha)) {
            throw new Exception('Dados incompletos. Nome, email e senha são obrigatórios.', 400); // Bad Request
        }
            $this->nome = $data->nome;
            $this->email = $data->email;
            $this->senha = $data->senha;
        }
    }

?>