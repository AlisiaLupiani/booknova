<?php
$body_page = new Template("html/modifica_offerta/modifica_offerta.html");
require_once("include/utility/QueryStringBuilder.php");

$dataLayer = new DataLayer(new DB_Connection());
$offerDAO = $dataLayer->getOfferDAO(); 

$messaggio = "";

// ==========================================
// FASE A: SALVATAGGIO DEI DATI (POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_offerta = isset($_POST['offerId']) ? (int)$_POST['offerId'] : 0;
    
    if ($id_offerta > 0) {
        $offerta = $offerDAO->getOfferById($id_offerta);
        
        if ($offerta !== null) {
            // Aggiorniamo le proprietà dell'oggetto usando i setter e i getter del tuo OfferDAO
            $offerta->setValue(isset($_POST['offerValue']) ? (float)$_POST['offerValue'] : 0.0);
            $offerta->setStartDate(isset($_POST['startDate']) ? trim($_POST['startDate']) : '');
            $offerta->setEndDate(isset($_POST['endDate']) ? trim($_POST['endDate']) : '');
            
            // Salva le modifiche usando il tuo metodo originale storeOffer
            $risultato_salvataggio = $offerDAO->storeOffer($offerta);
            
            if ($risultato_salvataggio !== null) {
                $messaggio = "Offerta aggiornata con successo!";
            } else {
                $messaggio = "Errore durante l'aggiornamento dell'offerta.";
            }
        } else {
            $messaggio = "Impossibile trovare l'offerta da modificare.";
        }
    }
}

// ==========================================
// FASE B: LETTURA DATI PER IL FORM (GET / POST)
// ==========================================
$id_offerta_visualizza = isset($_GET['offer_id']) ? (int)$_GET['offer_id'] : (isset($_POST['offerId']) ? (int)$_POST['offerId'] : 0);

if ($id_offerta_visualizza > 0) {
    
    $offerta = $offerDAO->getOfferById($id_offerta_visualizza);

    if ($offerta !== null) {
        // Mappiamo i segnaposto dell'offerta esattamente come facevi per il libro
        $body_page->setContent("offer_id", $offerta->getId());
        $body_page->setContent("valore_sconto", $offerta->getValue()); 
        $body_page->setContent("data_inizio", $offerta->getStartDate());
        $body_page->setContent("data_fine", $offerta->getEndDate());
    } else {
        $messaggio = "Offerta non trovata nel database.";
    }
}

$body_page->setContent("messaggio", $messaggio);
?>