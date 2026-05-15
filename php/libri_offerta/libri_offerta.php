<?php
$body_page = new Template("html/libri_offerta/libri_offerta.html");
require_once("include/utility/QueryStringBuilder.php");

$dataLayer = new DataLayer(new DB_Connection());
$bookofferDAO = $dataLayer->getBookOfferDAO();

$id_offerta = isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : 0;

if ($id_offerta > 0) {
    
    $risultato = $bookofferDAO->getBookOfferById($id_offerta);

    // Controllo se il risultato esiste (non è null)
    if ($risultato !== null) {
        
        /* 
           Se il template si aspetta una lista (un foreach), 
           dobbiamo mettere l'oggetto dentro un array.
        */
        $lista_libri = array($risultato); 
        
        $body_page->setContent("libri_associati", $lista_libri);
        $body_page->setContent("current_offer_id", $id_offerta);
        
    } else {
        $body_page->setContent("messaggio", "Nessun libro trovato per questa offerta.");
    }

} else {
    $body_page->setContent("messaggio", "ID offerta non valido.");
}
?>