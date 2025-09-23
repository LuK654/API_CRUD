<?php

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buscar todos os usuários
    public function buscarTodos() {
        $query = 'SELECT id, nome, email, data_criacao FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna um array diretamente
    }

    // Buscar um único usuário por ID
    public function buscarPorId($id) {
        $query = 'SELECT id, nome, email, data_criacao FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o usuário ou false
    }

    // Buscar um único usuário por Email
    public function buscarPorEmail($email) {
        $query = 'SELECT id, nome, email FROM ' . $this->table . ' WHERE email = :email LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar usuário (agora recebe um array de dados)
    public function criar(array $dados) {
        $query = 'INSERT INTO ' . $this->table . ' SET nome = :nome, email = :email, senha = :senha';
        $stmt = $this->conn->prepare($query);

        // Vinculando os parâmetros
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->bindParam(':senha', $dados['senha']); // A senha já vem criptografada do Service

        if($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna o ID do usuário criado
        }
        return false;
    }

    // Atualizar usuário
    public function atualizar($id, array $dados) {
        $query = 'UPDATE ' . $this->table . ' SET nome = :nome, email = :email WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->bindParam(':id', $id);

        // execute() em um UPDATE retorna true em sucesso, mesmo que 0 linhas sejam afetadas
        // rowCount() confirma se a linha foi de fato alterada.
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    // Deletar usuário
    public function deletar($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        return $stmt->rowCount() > 0; // Confirma que a linha foi deletada
    }
}
