<?php
$body_page = new Template("html/details_order/details_order.html");
require_once("include/utility/QueryStringBuilder.php");

$db_connection = new DB_Connection();
$db = $db_connection->getConnection(); 

$dataLayer = new DataLayer($db_connection);
$orderDAO = $dataLayer->getOrderDAO(); 

$messaggio = "";

// ==========================================
// FASE B: LETTURA DATI PER LA VISUALIZZAZIONE (GET)
// ==========================================
$id_ordine_visualizza = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($id_ordine_visualizza > 0) {
    
    $ordine = $orderDAO->getOrderById($id_ordine_visualizza);

    if ($ordine !== null) {
        // Mappiamo i segnaposto dell'ordine principale
        // NOTA: Se questi metodi restituiscono vuoto, controlla se si chiamano getDataOrdine() o getTotale() nel tuo Model!
        $body_page->setContent("ordine_id", $ordine->getId());
        $body_page->setContent("data_ordine", method_exists($ordine, 'getOrderDate') ? $ordine->getOrderDate() : (method_exists($ordine, 'getDataOrdine') ? $ordine->getDataOrdine() : 'Data non disp.'));
        $body_page->setContent("totale_ordine", method_exists($ordine, 'getTotal') ? $ordine->getTotal() : (method_exists($ordine, 'getTotale') ? $ordine->getTotale() : '0.00'));
        
        $utente = $ordine->getUser();
        $body_page->setContent("utente_email", $utente ? $utente->getEmail() : "Ospite");
        
        // Eseguiamo la query per estrarre i libri
$stmtLibri = $db->prepare("
    SELECT L.TITOLO AS titolo, OO.QUANTITA AS quantita, OO.PREZZO_UNITARIO AS prezzo_unitario
    FROM ORDINE_OGGETTO OO
    JOIN LIBRO L ON OO.ID_LIBRO = L.ID
    WHERE OO.ID_ORDINE = ?;
");

        
        $stmtLibri->execute([$id_ordine_visualizza]);
        $elementi_ordine = $stmtLibri->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($elementi_ordine)) {
            foreach ($elementi_ordine as $item) {
                $sub_page = new Template("html/details_order/details_order.html", "foreach_libri_ordine");
                
                // Gestiamo sia chiavi MAIUSCOLE che minuscole restituite dal DB
                $titolo = isset($item['TITOLO']) ? $item['TITOLO'] : (isset($item['titolo']) ? $item['titolo'] : 'Senza Titolo');
                $quantita = isset($item['QUANTITA']) ? $item['QUANTITA'] : (isset($item['quantita']) ? $item['quantita'] : 0);
                $prezzo = isset($item['PREZZO_UNITARIO']) ? $item['PREZZO_UNITARIO'] : (isset($item['prezzo_unitario']) ? $item['prezzo_unitario'] : 0.00);
                
                $sub_page->setContent("libro_titolo", $titolo);
                $sub_page->setContent("libro_quantita", $quantita); 
                $sub_page->setContent("libro_prezzo", $prezzo);
                
                $body_page->setContent("foreach_libri_ordine", $sub_page);
            }
        } else {
            $messaggio = "Nessun libro trovato per l'ordine #" . $id_ordine_visualizza . " nel database.";
        }
        
    } else {
        $messaggio = "L'ordine con ID " . $id_ordine_visualizza . " non è stato trovato nel database.";
    }
} else {
    $messaggio = "ID ordine non valido o mancante nell'URL (es. details_order.php?order_id=1).";
}

$body_page->setContent("messaggio", $messaggio);
?>