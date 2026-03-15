<?php

require_once("include/dbms.inc.php");

class DB_Connection
{

    protected ?PDO $conn;

    public function __construct(){
        try {
            // Inizializzo la connessione al database
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Errore di connessione: " . $e->getMessage());
        }
    }

    public function getConnection(): ?PDO{
        return $this->conn;
    }

    
}
