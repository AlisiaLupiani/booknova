<?php 

// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// 🔥 Devi creare il DataLayer PRIMA del PermissionProxy
$factory = new DataLayer(new DB_Connection());

// 🔥 Permission system
require_once("include/model/proxy/PermissionProxy.php");
$permission = new PermissionProxy($factory);
$permission->checkPermission("book_add");

// Recupero dati
$authors = $factory->getAuthorDAO()->getAllAuthors();
$publishers = $factory->getPublisherDAO()->getAllPublishers();
$categories = $factory->getCategoryDAO()->getAllCategories();
$formats = $factory->getFormatDAO()->getAllFormats();
$conditions = $factory->getConditionDAO()->getAllConditions();

// Carico template
$body_page = new Template("html/aggiungi_libro/aggiungi_libro.html");

// Popolo le select
foreach ($authors as $author) {
    $body_page->setContent("author_id", $author->getId());
    $body_page->setContent("author_name", $author->getName(), true);
}

foreach ($publishers as $publisher) {
    $body_page->setContent("publisher_id", $publisher->getId());
    $body_page->setContent("publisher_name", $publisher->getName(), true);
}

foreach ($categories as $category) {
    $body_page->setContent("category_id", $category->getId());
    $body_page->setContent("category_name", $category->getName(), true);
}

foreach ($formats as $format) {
    $body_page->setContent("format_id", $format->getId());
    $body_page->setContent("format_name", $format->getFormat(), true);
}

foreach ($conditions as $condition) {
    $body_page->setContent("condition_id", $condition->getId());
    $body_page->setContent("condition_name", $condition->getCondition(), true);
}
?>
