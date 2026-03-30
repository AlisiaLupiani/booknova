<?php

include_once("include/model/OrderItem.php");
include_once("include/model/proxy/OrderItemProxy.php");
include_once("include/db/DAO.php");

class OrderItemDAO extends DAO {
    private PDOStatement $stmtGetOrderItemById;
    private PDOStatement $stmtGetAllOrderItems;
    private PDOStatement $stmtInsertOrderItem;
    private PDOStatement $stmtUpdateOrderItem;
    private PDOStatement $stmtDeleteOrderItem; 

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetOrderItemById = $this->conn->prepare("SELECT * FROM ORDINE_OGGETTO WHERE ID = ?;");
        $this->stmtGetAllOrderItems = $this->conn->prepare("SELECT * FROM ORDINE_OGGETTO;");

        $this->stmtInsertOrderItem = $this->conn->prepare("INSERT INTO ORDINE_OGGETTO (ID_ORDINE, ID_LIBRO, QUANTITA) VALUES (?, ?, ?);");
        $this->stmtUpdateOrderItem = $this->conn->prepare("UPDATE ORDINE_OGGETTO SET QUANTITA = ? WHERE ID = ?;");
        $this->stmtDeleteOrderItem = $this->conn->prepare("DELETE FROM ORDINE_OGGETTO WHERE ID = ?;");
    }

    public function getOrderItemById(int $id): ?OrderItem {
        $this->stmtGetOrderItemById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetOrderItemById->execute();
        $rs = $this->stmtGetOrderItemById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createOrderItem($rs) : null;
    }

    public function getAllOrderItems(): array {
        $this->stmtGetAllOrderItems->execute();
        $result = [];
        while ($rs = $this->stmtGetAllOrderItems->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createOrderItem($rs);
        }
        return $result;
    }

    public function storeOrderItem(OrderItem $orderItem): ?OrderItem {
        if ($orderItem->getId() !== null) {
            $this->stmtUpdateOrderItem->bindValue(1, $orderItem->getOrder()->getId(), PDO::PARAM_INT);
            $this->stmtUpdateOrderItem->bindValue(2, $orderItem->getBook()->getId(), PDO::PARAM_INT);
            $this->stmtUpdateOrderItem->bindValue(3, $orderItem->getQuantity(), PDO::PARAM_INT);
            $this->stmtUpdateOrderItem->bindValue(4, $orderItem->getId(), PDO::PARAM_INT);

            if ($this->stmtUpdateOrderItem->execute()) return $orderItem;
        } else {
            $this->stmtInsertOrderItem->bindValue(1, $orderItem->getOrder()->getId(), PDO::PARAM_INT);
            $this->stmtInsertOrderItem->bindValue(2, $orderItem->getBook()->getId(), PDO::PARAM_INT);
            $this->stmtInsertOrderItem->bindValue(3, $orderItem->getQuantity(), PDO::PARAM_INT);

            if ($this->stmtInsertOrderItem->execute()) {
                $orderItem->setId((int)$this->conn->lastInsertId());
                return $orderItem;
            }
        }
        return null;
                  
    }

    private function createOrderItem(array $rs): OrderItem {
        $orderItem = new OrderItemProxy($this->dataLayer);
        $orderItem->setId((int)$rs['ID']);
        $orderItem->setOrderId((int)$rs['ID_ORDINE']);
        $orderItem->setBookId((int)$rs['ID_LIBRO']);
        $orderItem->setQuantity((int)$rs['QUANTITA']);
        $orderItem->setUnitPrice((float)$rs['PREZZO_UNITARIO']);
        
        
        return $orderItem;
    }

    public function deleteOrderItem(int $id): bool {
        $this->stmtDeleteOrderItem->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteOrderItem->execute();
    }
}   
