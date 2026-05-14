<?php
// php/offer/offer.php

// 1. Inizializza il Template e il Database
$body_page = new Template("html/offer/offer.html");
$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();

$libri_in_offerta = [];

// 2. Recupera i 3 ID dall'URL
if (isset($_GET['id1'], $_GET['id2'], $_GET['id3'])) {
    $id1 = (int)$_GET['id1'];
    $id2 = (int)$_GET['id2'];
    $id3 = (int)$_GET['id3'];
    $ids_ricevuti = [$id1, $id2, $id3];
    
    foreach ($ids_ricevuti as $id) {
        if ($id > 0) { 
            $book = $bookDAO->getBookById($id);
            if ($book) {
                $libri_in_offerta[] = $book;
            }
        }
    }
} else {
    echo "Errore: Nessun ID libro specificato nell'URL.";
    exit();
}


$struttura_ciclo = [];

foreach($libri_in_offerta as $b) {
    $prezzoOriginale = $b->getPrice();
    $prezzoScontato = number_format($prezzoOriginale * 0.90, 2);
    

    $struttura_ciclo[] = [
        'random_image_offer'      => "static/img/" . $b->getImagePath(),
        'random_id_offer'         => $b->getId(),
        'random_title_offer'      => $b->getTitle(),
        'random_author_offer'     => $b->getAuthor() ? $b->getAuthor()->getName() : "Autore Sconosciuto",
        'random__price_offer'     => $prezzoOriginale,
        'random_discounted_price' => $prezzoScontato
    ];
}
foreach ($struttura_ciclo as $chiave => $valori) {
    foreach ($valori as $placeholder => $valore) {
        
        $body_page->setContent($placeholder, $valore);
    }
}

?>