<?php


$body_page = new Template("html/ordini_admin/ordini_admin.html");
require_once("include/utility/QueryStringBuilder.php");
$dataLayer = new DataLayer(new DB_Connection());
$orderDAO = $dataLayer->getOrderDAO();
require_once("include/model/proxy/PermissionProxy.php");

$factory = new DataLayer(new DB_Connection());

$permission = new PermissionProxy($factory);

$permission->checkPermission("orders_view");
$orders = $orderDAO->getAllOrders();


foreach ($orders as $order) {
      
        $body_page->setContent("order_id", $order->getId());
        $body_page->setContent("username_order", $order->getUser()->getName());
        $body_page->setContent("usersurnaname_order", $order->getUser()->getSurname());
        $body_page->setContent("date_order", $order->getOrderDate());
        $body_page->setContent("total_order", $order->getTotal());
        $body_page->setContent("payment_order", $order->getPaymentMethod()->getName());
        $body_page->setContent("shipping_order", $order->getUser()->getIndirizzo());  
    


}
?>