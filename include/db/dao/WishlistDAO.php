<?php

require_once("include/model/Wishlist.php");
require_once("include/model/proxy/WishlistProxy.php");
require_once("include/db/DAO.php");

class WishlistDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetByUser;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtDelete;
    private PDOStatement $stmtDeleteByUserAndBook;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        // Query basate sulla tabella WISHLIST del tuo database
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM WISHLIST WHERE ID = ?;");
        $this->stmtGetByUser = $this->conn->prepare("SELECT * FROM WISHLIST WHERE ID_UTENTE = ? ORDER BY DATA_INSERIMENTO DESC;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES (?, ?, ?);");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM WISHLIST WHERE ID = ?;");
        
        // Comodo per rimuovere un libro dalla wishlist direttamente dalla scheda libro
        $this->stmtDeleteByUserAndBook = $this->conn->prepare("DELETE FROM WISHLIST WHERE ID_UTENTE = ? AND ID_LIBRO = ?;");
    }

    public function getWishlistItemById(int $id): ?Wishlist {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createWishlist($rs) : null;
    }

    /**
     * Recupera l'intera lista dei desideri di un utente
     */
    public function getWishlistByUser(int $userId): array {
        $this->stmtGetByUser->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtGetByUser->execute();
        $result = [];
        while ($rs = $this->stmtGetByUser->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createWishlist($rs);
        }
        return $result;
    }

    public function storeWishlist(Wishlist $wishlist): ?Wishlist {
        // Le wishlist solitamente non si aggiornano, si aggiungono o si tolgono
        $user = $wishlist->getUser();
        $book = $wishlist->getBook();
        
        $userId = ($user !== null) ? $user->getId() : null;
        $bookId = ($book !== null) ? $book->getId() : null;

        $this->stmtInsert->bindValue(1, $wishlist->getCreatedAt(), PDO::PARAM_STR);
        $this->stmtInsert->bindValue(2, $userId, PDO::PARAM_INT);
        $this->stmtInsert->bindValue(3, $bookId, PDO::PARAM_INT);

        if ($this->stmtInsert->execute()) {
            $wishlist->setId((int)$this->conn->lastInsertId());
            return $wishlist;
        }
        return null;
    }

    public function deleteWishlistItem(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    public function removeBookFromWishlist(int $userId, int $bookId): bool {
        $this->stmtDeleteByUserAndBook->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtDeleteByUserAndBook->bindValue(2, $bookId, PDO::PARAM_INT);
        return $this->stmtDeleteByUserAndBook->execute();
    }

    private function createWishlist(array $rs): Wishlist {
        // Proxy per caricare User e Book solo all'occorrenza
        $wishlist = new WishlistProxy($this->dataLayer);
        $wishlist->setId((int)$rs['ID']);
        $wishlist->setCreatedAt($rs['DATA_INSERIMENTO']);
        
        // Passiamo gli ID delle chiavi esterne al Proxy
        if (method_exists($wishlist, 'setUserId')) {
            $wishlist->setUserId((int)$rs['ID_UTENTE']);
        }
        if (method_exists($wishlist, 'setBookId')) {
            $wishlist->setBookId((int)$rs['ID_LIBRO']);
        }
        
        return $wishlist;
    }
}