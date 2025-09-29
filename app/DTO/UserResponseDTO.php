<?php
    namespace App\DTO;
    class UserResponseDTO {
    /**
     * As propriedades públicas que a nossa API vai expor para o cliente.
     * Usar 'readonly' é uma boa prática para DTOs, pois garante que os dados não serão alterados depois que o objeto for criado.*/
        public readonly int $id;
        public readonly string $nome;
        public readonly string $email;

        /**
         * O construtor é o "montador".
         * Ele recebe o array associativo que vem diretamente do UsuarioModel.
         */
        public function __construct(array $dadosUsuario) {
            // Pegamos o valor da chave 'id' do array e atribuímos à propriedade 'id' da classe.
            $this->id = $dadosUsuario['id'];

            // Fazemos o mesmo para o nome.
            $this->nome = $dadosUsuario['nome'];

            // E para o email.
            $this->email = $dadosUsuario['email'];

            // Perceba que, mesmo que o array $dadosUsuario contivesse a senha ou a data_criacao,
            // nós simplesmente os ignoramos! A nossa "caixa" de resposta não tem
            // compartimento para eles, garantindo que nunca sejam expostos.
        }
    }

?>