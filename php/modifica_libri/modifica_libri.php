<?php
$body_page = new Template("html/modifica_libri/modifica_libri.html");
require_once("include/utility/QueryStringBuilder.php");

$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO(); // Assicurati che si chiami getBookDAO o simile

$messaggio = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_libro = isset($_POST['bookId']) ? (int)$_POST['bookId'] : 0;
    
    if ($id_libro > 0) {
        $book = $bookDAO->getBookById($id_libro);
        
        if ($book !== null) {
            $book->setTitle(isset($_POST['bookTitle']) ? trim($_POST['bookTitle']) : '');
            $book->setPrice(isset($_POST['bookPrice']) ? (float)$_POST['bookPrice'] : 0.0);
            $book->setPublicationYear(isset($_POST['publishYear']) ? (int)$_POST['publishYear'] : 0);
            
            
            
            if ($book->getAuthor()) {
                $book->getAuthor()->setName(isset($_POST['bookAuthor']) ? trim($_POST['bookAuthor']) : '');
            }
            
            if ($book->getPublisher()) {
                $book->getPublisher()->setName(isset($_POST['bookPublisher']) ? trim($_POST['bookPublisher']) : '');
            }

            $risultato_salvataggio = $bookDAO->storeBook($book);
            
            if ($risultato_salvataggio !== null) {
                $messaggio = "Libro aggiornato con successo!";
            } else {
                $messaggio = "Errore durante l'aggiornamento del libro.";
            }
        } else {
            $messaggio = "Impossibile trovare il libro da modificare.";
        }
    }
}


$id_libro_visualizza = isset($_GET['book_id']) ? (int)$_GET['book_id'] : (isset($_POST['bookId']) ? (int)$_POST['bookId'] : 0);

if ($id_libro_visualizza > 0) {
    
    $book = $bookDAO->getBookById($id_libro_visualizza);

    if ($book !== null) {
        $body_page->setContent("book_id", $book->getId());
        $body_page->setContent("name", $book->getTitle()); 
        $body_page->setContent("price", $book->getPrice());
        $body_page->setContent("anno_pubblicazione", $book->getPublicationYear());
        
        $body_page->setContent("autore", $book->getAuthor() ? $book->getAuthor()->getName() : '');
        $body_page->setContent("editore", $book->getPublisher() ? $book->getPublisher()->getName() : '');
    } else {
        $messaggio = "Libro non trovato nel database.";
    }
}

$body_page->setContent("messaggio", $messaggio);
?>