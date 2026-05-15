<?php
$body_page = new Template("html/visualizza_offerte/visualizza_offerte.html");


require_once("include/utility/QueryStringBuilder.php");
$dataLayer = new DataLayer(new DB_Connection());
$offerDAO = $dataLayer->getOfferDAO();
$offers = $offerDAO->getAllOffers();




# Prende tutti i libri
foreach ($offers as $offer) {
      
        $body_page->setContent("offer_id", $offer->getId());
        $body_page->setContent("data_inizio", $offer->getStartDate());
        $body_page->setContent("data_fine", $offer->getEndDate());
        $body_page->setContent("value", $offer->getValue());




      

}
?>