<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/Condition.php");

$dataLayer = new DataLayer(new DB_Connection());

// Recupero dati
$authors = $dataLayer->getAuthorDAO()->getAllAuthors();
$publishers = $dataLayer->getPublisherDAO()->getAllPublishers();
$categories = $dataLayer->getCategoryDAO()->getAllCategories();
$formats = $dataLayer->getFormatDAO()->getAllFormats();
$conditions = $dataLayer->getConditionDAO()->getAllConditions();

// Carico template
$body_page = new Template("html/aggiungi_libro/aggiungi_libro.html");

// Popolo le select
foreach ($authors as $author) {
    $body_page->setContent("author_id", $author->getId(), $author->getName());

    $body_page->setContent("author_name", $author->getName(), true  );
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

