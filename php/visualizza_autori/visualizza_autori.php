<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");
require_once("include/utility/QueryStringBuilder.php");

// 🔥 CREA IL DATALAYER
$factory = new DataLayer(new DB_Connection());

// 🔥 CREA IL PERMISSION PROXY
$permission = new PermissionProxy($factory);

// 🔥 CONTROLLA IL PERMESSO (NOME CORRETTO!)
$permission->checkPermission("author_view");

// Template
$body_page = new Template("html/visualizza_autori/visualizza_autori.html");

// DAO
$authorDAO = $factory->getAuthorDAO();
$authors = $authorDAO->getAllAuthors();

// Loop autori
foreach ($authors as $author) {

    $body_page->setContent("author_id", $author->getId());
    $body_page->setContent("name", $author->getName());
    $body_page->setContent("biography", $author->getBiography());

    $string_builder = new QueryStringBuilder('eliminaautore.php');
    $string_builder->add("author_id", $author->getId());

    $body_page->setContent("author", $string_builder->build());
}
?>
