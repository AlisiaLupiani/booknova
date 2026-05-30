<?php

require_once("include/model/Wishlist.php");
require_once("include/model/proxy/WishlistProxy.php");
require_once("include/db/DAO.php");
require_once("include/db/DataLayer.php");
require_once("include/db/dao/BookDAO.php");
require_once("include/db/dao/UserDAO.php");

class WishlistDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetByUser;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDelete;
    private PDOStatement $stmtDeleteByUserAndBook;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {

        $this->stmtGetById = $this->conn->prepare(
            "SELECT * FROM WISHLIST WHERE ID = ?;"
        );

        $this->stmtGetByUser = $this->conn->prepare(
            "SELECT * FROM WISHLIST WHERE ID_UTENTE = ? ORDER BY DATA_INSERIMENTO DESC;"
        );

        $this->stmtInsert = $this->conn->prepare(
            "INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO)
             VALUES (?, ?, ?);"
        );

        $this->stmtUpdate = $this->conn->prepare(
            "UPDATE WISHLIST SET DATA_INSERIMENTO = ?, ID_UTENTE = ?, ID_LIBRO = ?
             WHERE ID = ?;"
        );

        $this->stmtDelete = $this->conn->prepare(
            "DELETE FROM WISHLIST WHERE ID = ?;"
        );

        $this->stmtDeleteByUserAndBook = $this->conn->prepare(
            "DELETE FROM WISHLIST WHERE ID_UTENTE = ? AND ID_LIBRO = ?;"
        );
    }

    public function getWishlistItemById(int $id): ?Wishlist {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createWishlist($rs) : null;
    }

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

        // UPDATE
        if ($wishlist->getId() !== null) {

            $this->stmtUpdate->bindValue(1, $wishlist->getCreatedAt(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $wishlist->getUserId(), PDO::PARAM_INT);
            $this->stmtUpdate->bindValue(3, $wishlist->getBookId(), PDO::PARAM_INT);
            $this->stmtUpdate->bindValue(4, $wishlist->getId(), PDO::PARAM_INT);

            if ($this->stmtUpdate->execute()) {
                return $wishlist;
            }

        } else {

            // INSERT
            $this->stmtInsert->bindValue(1, $wishlist->getCreatedAt(), PDO::PARAM_STR);
            $this->stmtInsert->bindValue(2, $wishlist->getUserId(), PDO::PARAM_INT);
            $this->stmtInsert->bindValue(3, $wishlist->getBookId(), PDO::PARAM_INT);

            if ($this->stmtInsert->execute()) {
                $wishlist->setId((int)$this->conn->lastInsertId());
                return $wishlist;
            }
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

        // Usa SEMPRE WishlistProxy
        $wishlist = new WishlistProxy($this->dataLayer);

        $wishlist->setId((int)$rs['ID']);
        $wishlist->setCreatedAt($rs['DATA_INSERIMENTO']);

        // Imposta gli ID (il Proxy carica gli oggetti quando servono)
        $wishlist->setUserId((int)$rs['ID_UTENTE']);
        $wishlist->setBookId((int)$rs['ID_LIBRO']);

        return $wishlist;
    }
}
