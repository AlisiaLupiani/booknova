<?php

include_once("include/model/CartItem.php");
include_once("include/model/proxy/CartItemProxy.php"); // Ipotizzando la presenza del rispettivo Proxy
include_once("include/db/DAO.php");

class CartItemDAO extends DAO {
    private PDOStatement $stmtGetCartItemById;
    private PDOStatement $stmtGetCartItemsByUserId;
    private PDOStatement $stmtInsertCartItem;
    private PDOStatement $stmtUpdateCartItem;
    private PDOStatement $stmtDeleteCartItem; 

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetCartItemById = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID = ?;");
        $this->stmtGetCartItemsByUserId = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID_UTENTE = ?;");

        $this->stmtInsertCartItem = $this->conn->prepare("INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (?, ?, ?);");
        $this->stmtUpdateCartItem = $this->conn->prepare("UPDATE CARRELLO SET QUANTITA = ? WHERE ID = ?;");
        $this->stmtDeleteCartItem = $this->conn->prepare("DELETE FROM CARRELLO WHERE ID = ?;");
    }

    public function getCartItemById(int $id): ?CartItem {
        $this->stmtGetCartItemById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetCartItemById->execute();
        $rs = $this->stmtGetCartItemById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createCartItem($rs) : null;
    }

    public function getCartItemsByUserId(int $userId): array {
        $this->stmtGetCartItemsByUserId->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtGetCartItemsByUserId->execute();
        $result = [];
        while ($rs = $this->stmtGetCartItemsByUserId->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createCartItem($rs);
        }
        return $result;
    }

    public function storeCartItem(CartItem $cartItem): ?CartItem {
        if ($cartItem->getId() !== null) {
            // Update: aggiorna solo la quantità partendo dall'ID della riga
            $this->stmtUpdateCartItem->bindValue(1, $cartItem->getQuantity(), PDO::PARAM_INT);
            $this->stmtUpdateCartItem->bindValue(2, $cartItem->getId(), PDO::PARAM_INT);

            if ($this->stmtUpdateCartItem->execute()) return $cartItem;
        } else {
            // Insert: nuova riga nel carrello
            $this->stmtInsertCartItem->bindValue(1, $cartItem->getUser()->getId(), PDO::PARAM_INT);
            $this->stmtInsertCartItem->bindValue(2, $cartItem->getBook()->getId(), PDO::PARAM_INT);
            $this->stmtInsertCartItem->bindValue(3, $cartItem->getQuantity(), PDO::PARAM_INT);

            if ($this->stmtInsertCartItem->execute()) {
                $cartItem->setId((int)$this->conn->lastInsertId());
                return $cartItem;
            }
        }
        return null;
    }

    public function deleteCartItem(int $id): bool {
        $this->stmtDeleteCartItem->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteCartItem->execute();
    }

    private function createCartItem(array $rs): CartItem {
        // Usa il Proxy per mappare le chiavi esterne del DB (ID_UTENTE, ID_LIBRO)
        // e caricarle come oggetti completi solo quando richiesti
        $cartItem = new CartItemProxy($this->dataLayer);
        $cartItem->setId((int)$rs['ID']);
        $cartItem->setUserId((int)$rs['ID_UTENTE']); // Metodo atteso nel tuo CartItemProxy
        $cartItem->setBookId((int)$rs['ID_LIBRO']);   // Metodo atteso nel tuo CartItemProxy
        $cartItem->setQuantity((int)$rs['QUANTITA']);
        
        return $cartItem;
    }
}