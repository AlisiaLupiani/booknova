<?php

require_once("include/model/Cart.php");
require_once("include/model/proxy/CartProxy.php");
require_once("include/db/DAO.php");

class CartDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetByUser;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDelete;
    private PDOStatement $stmtDeleteByUser;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        // Recupera un singolo elemento del carrello
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID = ?;");
        
        // Recupera tutti i libri nel carrello di un utente specifico
        $this->stmtGetByUser = $this->conn->prepare("SELECT * FROM CARRELLO WHERE ID_UTENTE = ?;");
        
        // Inserisce un nuovo libro nel carrello
        $this->stmtInsert = $this->conn->prepare("INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (?, ?, ?);");
        
        // Aggiorna solo la quantità (utile se l'utente aggiunge lo stesso libro due volte)
        $this->stmtUpdate = $this->conn->prepare("UPDATE CARRELLO SET QUANTITA = ? WHERE ID = ?;");
        
        // Rimuove un libro dal carrello
        $this->stmtDelete = $this->conn->prepare("DELETE FROM CARRELLO WHERE ID = ?;");
        
        // Svuota l'intero carrello di un utente (da usare dopo l'acquisto)
        $this->stmtDeleteByUser = $this->conn->prepare("DELETE FROM CARRELLO WHERE ID_UTENTE = ?;");
    }

    public function getCartById(int $id): ?Cart {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createCart($rs) : null;
    }

    public function getCartByUser(int $userId): array {
        $this->stmtGetByUser->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtGetByUser->execute();
        $result = [];
        while ($rs = $this->stmtGetByUser->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createCart($rs);
        }
        return $result;
    }

    public function storeCart(Cart $cart): ?Cart {
        if ($cart->getId() !== null) {
            // Se l'ID esiste, aggiorniamo solo la quantità
            $this->stmtUpdate->bindValue(1, $cart->getQuantity(), PDO::PARAM_INT);
            $this->stmtUpdate->bindValue(2, $cart->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $cart;
        } else {
            // Se l'ID è nullo, inseriamo una nuova riga
            $this->stmtInsert->bindValue(1, $cart->getUser() ? $cart->getUser()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsert->bindValue(2, $cart->getBook() ? $cart->getBook()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsert->bindValue(3, $cart->getQuantity(), PDO::PARAM_INT);

            if ($this->stmtInsert->execute()) {
                $cart->setId((int)$this->conn->lastInsertId());
                return $cart;
            }
        }
        return null;
    }

    public function deleteCartItem(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    public function emptyCartByUser(int $userId): bool {
        $this->stmtDeleteByUser->bindValue(1, $userId, PDO::PARAM_INT);
        return $this->stmtDeleteByUser->execute();
    }

    private function createCart(array $rs): Cart {
        // Usiamo il Proxy per caricare l'utente e il libro solo quando servono
        $cart = new CartProxy($this->dataLayer);
        $cart->setId((int)$rs['ID']);
        $cart->setQuantity((int)$rs['QUANTITA']);
        
        // Impostiamo gli ID nel Proxy (Lazy Loading)
        $cart->setUserId((int)$rs['ID_UTENTE']);
        $cart->setBookId((int)$rs['ID_LIBRO']);
        
        return $cart;
    }
}