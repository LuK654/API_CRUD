<?php

class Usuario {
    private $conn;
    private $table = 'usuarios';

    // Propriedades do Usuário
    public $id;
    public $nome;
    public $email;
    public $senha;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buscar todos os usuários
    public function buscarTodos() {
        $query = 'SELECT id, nome, email, data_criacao FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Buscar um único usuário por ID
    public function buscarPorId($id) {
        $query = 'SELECT id, nome, email, data_criacao FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->email = $row['email'];
        }
    }

    // Criar usuário
    public function criar() {
        $query = 'INSERT INTO ' . $this->table . ' SET nome = :nome, email = :email, senha = :senha';
        $stmt = $this->conn->prepare($query);

        // Limpando os dados (para segurança)
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Criptografando a senha
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);

        // Vinculando os parâmetros
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Atualizar usuário
    public function atualizar($id) {
        $query = 'UPDATE ' . $this->table . ' SET nome = :nome, email = :email WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $id = htmlspecialchars(strip_tags($id));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Deletar usuário
    public function deletar($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $id = htmlspecialchars(strip_tags($id));
        
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}