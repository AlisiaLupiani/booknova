<?php


$body_page = new Template("html/visualizza_autori/visualizza_autori.html");
require_once("include/utility/QueryStringBuilder.php");
$dataLayer = new DataLayer(new DB_Connection());
$authorDAO = $dataLayer->getAuthorDAO();
$authors = $authorDAO->getAllAuthors();



# Prende tutti i libri
foreach ($authors as $author) {
      
        $body_page->setContent("author_id", $author->getId());
        $body_page->setContent("name", $author->getName());
        $body_page->setContent("biography", $author->getBiography());
    
 


        $string_builder = new QueryStringBuilder('eliminautente.php');
        $string_builder->add("author_id", $author->getId());

        $body_page->setContent("author", $string_builder->build());


}
?>