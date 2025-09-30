<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    // Parâmetros de ligação à BD
    private $host = 'localhost';
    private $db_name = 'api_crud_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Método de ligação
    public function connect(): ?PDO {
        $this->conn = null;

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Boa prática para retornar sempre arrays associativos
        } catch(PDOException $e) {
            // Em produção, seria melhor registar o erro num log do que exibi-lo.
            echo 'Erro de Ligação: ' . $e->getMessage();
            return null; // Retorna nulo em caso de falha
        }

        return $this->conn;
    }
}

