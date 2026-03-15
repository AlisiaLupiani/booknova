<?php

require_once("DB_Connection.php");
require_once("dao/UserDAO.php");



class DataLayer{


    private ?DB_Connection $DBConnection;
    private ?PDO $conn;


    private UserDAO $userDAO;


    public function __construct(DB_Connection $DBConnection) {
        $this->DBConnection = $DBConnection;
        $this->conn = $DBConnection->getConnection();
        $this->init();
    }

    public function getDBConnection(): ?DB_Connection{
        return $this->DBConnection;
    }

    public function getConnection(): ?PDO{
        return $this->DBConnection->getConnection();
    }

    
    public function init(){
        $this->userDAO = new UserDAO($this);
    }


    public function getUserDAO(): UserDAO{
        return $this->userDAO;
    }

}
?>