<?php

include_once("include/model/Order.php");
include_once("include/model/proxy/OrderProxy.php");
include_once("include/db/DAO.php");

class OrderDAO extends DAO {
    private PDOStatement $stmtGetOrderById;
    private PDOStatement $stmtGetAllOrders;
    private PDOStatement $stmtInsertOrder;
    private PDOStatement $stmtUpdateOrder;
    private PDOStatement $stmtDeleteOrder;  

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetOrderById = $this->conn->prepare("SELECT * FROM ORDINE WHERE ID = ?;");
        $this->stmtGetAllOrders = $this->conn->prepare("SELECT * FROM ORDINE;");
        $this->stmtInsertOrder = $this->conn->prepare("INSERT INTO ORDINE (ID_UTENTE, ID_METODO_PAGAMENTO, ID_METODO_SPEDIZIONE, DATA_ORDINE, TOTALE) VALUES (?, ?, ?, ?, ?);");
        $this->stmtUpdateOrder = $this->conn->prepare("UPDATE ORDINE SET ID_UTENTE = ?, ID_METODO_PAGAMENTO = ?, ID_METODO_SPEDIZIONE = ?, DATA_ORDINE = ?, TOTALE = ? WHERE ID = ?;");
        $this->stmtDeleteOrder = $this->conn->prepare("DELETE FROM ORDINE WHERE ID = ?;");
    }
    
    public function getOrderById(int $id): ?Order {
        $this->stmtGetOrderById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetOrderById->execute();
        $rs = $this->stmtGetOrderById->fetch(PDO::FETCH_ASSOC);
        return $rs ? $this->createOrder($rs) : null;
    }

    public function getAllOrders(): array {
        $this->stmtGetAllOrders->execute();
        $result = [];   
        while($rs = $this->stmtGetAllOrders->fetch(PDO::FETCH_ASSOC)){
            $result[] = $this->createOrder($rs);
        }
        return $result;
    }

    public function storeOrder(Order $order): ?Order {
        // Estraggo gli ID dagli oggetti per il database
        $userId = $order->getUser()->getId();
        $paymentId = $order->getPaymentMethod()->getId();
        $shippingId = $order->getShippingMethod()->getId();

        if ($order->getId() !== null) {
            $this->stmtUpdateOrder->bindValue(1, $userId, PDO::PARAM_INT);
            $this->stmtUpdateOrder->bindValue(2, $paymentId, PDO::PARAM_INT);
            $this->stmtUpdateOrder->bindValue(3, $shippingId, PDO::PARAM_INT);
            $this->stmtUpdateOrder->bindValue(4, $order->getOrderDate(), PDO::PARAM_STR);
            $this->stmtUpdateOrder->bindValue(5, $order->getTotal(), PDO::PARAM_STR);
            $this->stmtUpdateOrder->bindValue(6, $order->getId(), PDO::PARAM_INT);

            if($this->stmtUpdateOrder->execute()){
                return $order;
            }
        } else {
            $this->stmtInsertOrder->bindValue(1, $userId, PDO::PARAM_INT);
            $this->stmtInsertOrder->bindValue(2, $paymentId, PDO::PARAM_INT);
            $this->stmtInsertOrder->bindValue(3, $shippingId, PDO::PARAM_INT);
            $this->stmtInsertOrder->bindValue(4, $order->getOrderDate(), PDO::PARAM_STR);
            $this->stmtInsertOrder->bindValue(5, $order->getTotal(), PDO::PARAM_STR);

            if($this->stmtInsertOrder->execute()){
                $order->setId((int)$this->conn->lastInsertId());
                return $order;
            }
        }
        return null;
    }

    public function createOrder(array $rs): Order {
        $order = new OrderProxy($this->dataLayer);
        $order->setId($rs['ID']);
        
        // SOLUZIONE TYPERROR: Recupero l'oggetto User tramite il suo DAO
        // Invece di passare l'intero $rs['ID_UTENTE'], passiamo l'oggetto User
        $user = $this->dataLayer->getUserDAO()->getUserById($rs['ID_UTENTE']);
        $order->setUser($user);

        // Fai lo stesso per i metodi di pagamento e spedizione se il modello Order lo richiede
        $payment = $this->dataLayer->getPaymentMethodDAO()->getPaymentMethodById($rs['ID_METODO_PAGAMENTO']);
        $order->setPaymentMethod($payment);

        $shipping = $this->dataLayer->getShippingMethodDAO()->getShippingMethodById($rs['ID_METODO_SPEDIZIONE']);
        $order->setShippingMethod($shipping);

        $order->setOrderDate($rs['DATA_ORDINE']);
        $order->setTotal($rs['TOTALE']);
        
        return $order;
    }

    public function deleteOrder(int $id): bool {
        $this->stmtDeleteOrder->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteOrder->execute();
    }
}