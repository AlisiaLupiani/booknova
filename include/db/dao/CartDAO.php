<?php

require_once("include/model/Cart.php");
require_once("include/model/CartItem.php");
require_once("include/model/proxy/CartProxy.php");
require_once("include/model/proxy/CartItemProxy.php");
require_once("include/db/DAO.php");

class CartDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetItemsByUserId;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDeleteById;
    private PDOStatement $stmtEmptyByUser;
    private PDOStatement $stmtGetByUserAndBook;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {

        $this->stmtGetById = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID = ?");
        $this->stmtGetItemsByUserId = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID_UTENTE = ?");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (?, ?, ?)");
        $this->stmtUpdate = $this->conn->prepare("UPDATE CARRELLO SET QUANTITA = ? WHERE ID = ?");
        $this->stmtDeleteById = $this->conn->prepare("DELETE FROM CARRELLO WHERE ID = ?");
        $this->stmtEmptyByUser = $this->conn->prepare("DELETE FROM CARRELLO WHERE ID_UTENTE = ?");
        $this->stmtGetByUserAndBook = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID_UTENTE = ? AND ID_LIBRO = ?");
    }


    public function getCartByUserId(int $userId): Cart {
        $cart = new CartProxy($this->dataLayer);
        $cart->setUserId($userId);
        return $cart;
    }

    public function getCartItemsByUserId(int $userId): array {
        $this->stmtGetItemsByUserId->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtGetItemsByUserId->execute();
        
        $result = [];
        
        while ($rs = $this->stmtGetItemsByUserId->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createCartItem($rs);
        }
        
        return $result;
    }

    public function getCartItemByUserId(int $id): ?CartItem {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();

        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createCartItem($rs) : null;
    }

    public function getCartItemByUserAndBook(int $userId, int $bookId): ?CartItem {
        $this->stmtGetByUserAndBook->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtGetByUserAndBook->bindValue(2, $bookId, PDO::PARAM_INT);
        $this->stmtGetByUserAndBook->execute();

        $rs = $this->stmtGetByUserAndBook->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createCartItem($rs) : null;
    }

    public function addCartItem(int $userId, int $bookId, int $quantity): bool {

        // controllo se esiste già
        $existing = $this->getCartItemByUserAndBook($userId, $bookId);

        if ($existing !== null) {
            // incremento di UNA sola unità
            return $this->updateCartItemQuantity(
                $existing->getId(),
                $existing->getQuantity() + 1
            );
        }

        // nuovo inserimento con quantità passata
        $this->stmtInsert->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtInsert->bindValue(2, $bookId, PDO::PARAM_INT);
        $this->stmtInsert->bindValue(3, $quantity, PDO::PARAM_INT);

        return $this->stmtInsert->execute();
    }

    public function updateCartItemQuantity(int $itemId, int $quantity): bool {
        $this->stmtUpdate->bindValue(1, $quantity, PDO::PARAM_INT);
        $this->stmtUpdate->bindValue(2, $itemId, PDO::PARAM_INT);

        return $this->stmtUpdate->execute();
    }

    public function deleteCartItemById(int $id): bool {
        $this->stmtDeleteById->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteById->execute();
    }

    public function deleteCartItemByUserAndBook(int $userId, int $bookId): bool {
        $sql = "DELETE FROM CARRELLO WHERE ID_UTENTE = ? AND ID_LIBRO = ?";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $bookId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function emptyCartByUser(int $userId): bool {
        $this->stmtEmptyByUser->bindValue(1, $userId, PDO::PARAM_INT);
        return $this->stmtEmptyByUser->execute();
    }


    private function createCartItem(array $rs): CartItem {
        $item = new CartItemProxy($this->dataLayer);

        $item->setId($rs['ID']);
        $item->setQuantity((int)$rs['QUANTITA']);
        $item->setUserId($rs['ID_UTENTE']);
        $item->setBookId($rs['ID_LIBRO']);

        return $item;
    }
}