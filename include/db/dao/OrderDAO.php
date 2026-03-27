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
        
        $this->stmtInsertOrder = $this->conn->prepare("SELECT * FROM ORDINE;");
        
        $this->stmtUpdateOrder = $this->conn->prepare("SELECT * FROM ORDINE;");
        $this->stmtDeleteOrder = $this->conn->prepare("SELECT * FROM ORDINE;");
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
        if ($order->getId() !== null) {
            $this->stmtUpdateOrder->bindValue(1, $order->getDate(), PDO::PARAM_STR);
            $this->stmtUpdateOrder->bindValue(2, $order->getUser() ? $order->getUser()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateOrder->bindValue(3, $order->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdateOrder->execute()) {
                return $order;
            }
        } else {
            $this->stmtInsertOrder->bindValue(1, $order->getDate(), PDO::PARAM_STR);
            $this->stmtInsertOrder->bindValue(2, $order->getUser() ? $order->getUser()->getId() : null, PDO::PARAM_INT);
            if ($this->stmtInsertOrder->execute()) {
                $order->setId((int)$this->conn->lastInsertId());
                return $order;
            }
        }
        return null;
    }

    public function createOrder(array $rs): Order {
        $order = new OrderProxy($this->dataLayer);
        $order->setId((int)$rs['ID']);
        $order->setDate($rs['DATA']);
        $order->setUserId((int)$rs['ID_UTENTE']);
        return $order;
    }

    public function deleteOrder(int $id): bool {
        $this->stmtDeleteOrder->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteOrder->execute();
    }
            
    
}
        