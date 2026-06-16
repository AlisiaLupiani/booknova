<?php
$body_page = new Template("html/details_order/details_order.html");
require_once("include/utility/QueryStringBuilder.php");

$db_connection = new DB_Connection();
$db = $db_connection->getConnection(); 

$dataLayer = new DataLayer($db_connection);
$orderDAO = $dataLayer->getOrderDAO(); 
$orderItemDAO = $dataLayer->getOrderItemDAO();
$bookDAO = $dataLayer->getBookDAO();
$book=$bookDAO->getAllBooks();

$messaggio = "";

// ==========================================
// LETTURA DATI PER LA VISUALIZZAZIONE (GET)
// ==========================================
$id_ordine_visualizza = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($id_ordine_visualizza > 0) {
    
    $ordine = $orderDAO->getOrderById($id_ordine_visualizza);

    if ($ordine !== null) {

        // Dati ordine
        $body_page->setContent("ordine_id", $ordine->getId());
        $body_page->setContent("data_ordine", $ordine->getOrderDate());
        $body_page->setContent("totale_ordine", $ordine->getTotal());

    } else {
        $messaggio = "Ordine non trovato.";
    }
}
$body_page->setContent("messaggio", $messaggio);
?>
