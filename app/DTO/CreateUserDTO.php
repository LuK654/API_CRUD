<?php
namespace App\DTO;

// Importamos o validador (v) e as exceções
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use InvalidArgumentException;

class CreateUserDTO {
    public readonly string $nome;
    public readonly string $email;
    public readonly string $senha;

    public function __construct(object $data) {
        // Usamos v::attribute(). Ele verifica um campo de cada vez e ignora os outros,
        // o que é perfeito para o que precisamos.

        try {
            // Valida apenas o nome. Se falhar, lança um erro.
            v::attribute('nome', v::notEmpty()->length(3, 255)->alpha(' '))->assert($data);
        } catch (NestedValidationException $e) {
            throw new InvalidArgumentException('O nome é inválido (deve ter pelo menos 3 caracteres e conter apenas letras e espaços).', 400);
        }

        try {
            // Valida apenas o email.
            v::attribute('email', v::notEmpty()->email())->assert($data);
        } catch (NestedValidationException $e) {
            throw new InvalidArgumentException('O formato do email é inválido.', 400);
        }

        // AGORA, FAZEMOS A VALIDAÇÃO 100% MANUAL DA SENHA, SEM NENHUMA BIBLIOTECA
        $senha = $data->senha ?? null;

        if (empty($senha)) {
            throw new InvalidArgumentException('A senha é obrigatória.', 400);
        }
        if (strlen($senha) < 8) {
            throw new InvalidArgumentException('A senha deve ter no mínimo 8 caracteres.', 400);
        }
        if (!preg_match('/[a-z]/', $senha)) {
            throw new InvalidArgumentException('A senha deve conter pelo menos uma letra minúscula.', 400);
        }
        if (!preg_match('/[A-Z]/', $senha)) {
            throw new InvalidArgumentException('A senha deve conter pelo menos uma letra maiúscula.', 400);
        }
        if (!preg_match('/[0-9]/', $senha)) {
            throw new InvalidArgumentException('A senha deve conter pelo menos um número.', 400);
        }
        if (!preg_match('/[\W_]/', $senha)) {
            throw new InvalidArgumentException('A senha deve conter pelo menos um símbolo.', 400);
        }
        // --- FIM DA SOLUÇÃO FINAL ---

        // Se tudo passou, atribuímos os valores.
        $this->nome = $data->nome;
        $this->email = $data->email;
        $this->senha = $data->senha;
    }
}

