<?php

require_once("include/model/Author.php");
require_once("include/model/proxy/AuthorProxy.php");
require_once("include/db/DAO.php");

class AuthorDAO extends DAO {

    private PDOStatement $stmtGetAuthorById;
    private PDOStatement $stmtGetAllAuthors;
    private PDOStatement $stmtInsertAuthor;
    private PDOStatement $stmtUpdateAuthor;
    private PDOStatement $stmtDeleteAuthor;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
       
        $this->stmtGetAuthorById = $this->conn->prepare("SELECT * FROM AUTORE WHERE ID = ?;");
        $this->stmtGetAllAuthors = $this->conn->prepare("SELECT * FROM AUTORE;");

        $this->stmtInsertAuthor = $this->conn->prepare("INSERT INTO AUTORE (NOME, BIOGRAFIA) VALUES (?, ?);");

        $this->stmtUpdateAuthor = $this->conn->prepare("UPDATE AUTORE SET NOME = ?, BIOGRAFIA = ? WHERE ID = ?;");

        $this->stmtDeleteAuthor = $this->conn->prepare("DELETE FROM AUTORE WHERE ID = ?;");
    }

    public function getAuthorById(int $id): ?Author {
        $this->stmtGetAuthorById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetAuthorById->execute();
        $rs = $this->stmtGetAuthorById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createAuthor($rs) : null;
    }

    public function getAllAuthors(): array {
        $this->stmtGetAllAuthors->execute();
        $result = [];
        while ($rs = $this->stmtGetAllAuthors->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createAuthor($rs);
        }
        return $result;
    }

    public function storeAuthor(Author $author): ?Author {
        if ($author->getId() !== null) {
            
            $this->stmtUpdateAuthor->bindValue(1, $author->getName(), PDO::PARAM_STR);
            $this->stmtUpdateAuthor->bindValue(2, $author->getBiography(), PDO::PARAM_STR);
            $this->stmtUpdateAuthor->bindValue(3, $author->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdateAuthor->execute()) {
                return $author;
            }
        } else {
            
            $this->stmtInsertAuthor->bindValue(1, $author->getName(), PDO::PARAM_STR);
            $this->stmtInsertAuthor->bindValue(2, $author->getBiography(), PDO::PARAM_STR);
            if ($this->stmtInsertAuthor->execute()) {
                $author->setId((int)$this->conn->lastInsertId());
                return $author;
            }
        }
        return null;
    }

    public function deleteAuthorById(int $id): bool {
        $this->stmtDeleteAuthor->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteAuthor->execute();
    }

    private function createAuthor(array $rs): Author {
        $author = new AuthorProxy($this->dataLayer);
        $author->setId((int)$rs['ID']);
        $author->setName($rs['NOME']);
        $author->setBiography($rs['BIOGRAFIA']);
        return $author;
    }
}