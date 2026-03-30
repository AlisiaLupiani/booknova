<?php
// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

$dataLayer = new DataLayer(new DB_Connection());
$authorDAO = $dataLayer->getAuthorDAO();
$bookDAO = $dataLayer->getBookDAO();
foreach($authorDAO->getAllAuthors() as $author){
    echo $author -> getName() . "<br>";
}
foreach($bookDAO->getAllBooks() as $book){
    echo $book -> getTitle() . "<br>";
}
?>