<?php

class Database {
    // Parâmetros de conexão com o BD
    private $host = 'localhost';
    private $db_name = 'api_crud_db';
    private $username = 'root';
    private $password = ''; // No XAMPP padrão, a senha é vazia
    private $conn;

    // Método de conexão
    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}