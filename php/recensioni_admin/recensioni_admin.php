<?php
require_once("include/utility/QueryStringBuilder.php");

$body_page = new Template("html/recensioni_admin/recensioni_admin.html");
$dataLayer = new DataLayer(new DB_Connection());

// 1. Recupera l'ID del libro dalla URL (es: recensioni.php?book_id=5)
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

if ($book_id > 0) {
    $recensioniDAO = $dataLayer->getReviewDAO();
    // Usa un nome diverso per l'array e il singolo elemento
    $lista_recensioni = $recensioniDAO->getReviewsByBook($book_id);

    foreach ($lista_recensioni as $recensione) {
        $body_page->setContent("recensioni_id", $recensione->getId());
        
        // Assicurati che getUser() non sia null per evitare Fatal Errors
        if ($recensione->getUser()) {
            $body_page->setContent("username_recensioni", $recensione->getUser()->getName());
            $body_page->setContent("usersurnaname_recensioni", $recensione->getUser()->getSurname());
        }
        
        $body_page->setContent("date_recensioni", $recensione->getDate());        
        $body_page->setContent("content_recensioni", $recensione->getContent());
    }
} else {
    // Gestisci il caso in cui l'ID non sia presente
    $body_page->setContent("content_recensioni", "Nessun libro selezionato.");
}
?>