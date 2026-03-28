<?php

require_once("DB_Connection.php");
require_once("dao/UserDAO.php");
require_once("dao/RoleDAO.php");
require_once("dao/CategoryDAO.php");
require_once("dao/BookDAO.php");



class DataLayer{


    private ?DB_Connection $DBConnection;
    private ?PDO $conn;
    


    private UserDAO $userDAO;
    private RoleDAO $roleDAO;
    private CategoryDAO $categoryDAO;
    private BookDAO $bookDAO;


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
        $this->roleDAO = new RoleDAO($this);
        $this->categoryDAO = new CategoryDAO($this);
        $this->bookDAO = new BookDAO($this);
    }

    public function getCategoryDAO(): CategoryDAO{
        return $this->categoryDAO;
    }

    public function getBookDAO(): BookDAO{
        return $this->bookDAO;
    }


    public function getUserDAO(): UserDAO{
        return $this->userDAO;
    }

    public function getRoleDAO(): RoleDAO{
        return $this->roleDAO;
    }

}
?>