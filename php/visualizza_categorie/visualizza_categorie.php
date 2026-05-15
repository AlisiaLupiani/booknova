<?php


$body_page = new Template("html/visualizza_categorie/visualizza_categorie.html");
require_once("include/utility/QueryStringBuilder.php");
$dataLayer = new DataLayer(new DB_Connection());
$categoryDAO = $dataLayer->getCategoryDAO();
$categories = $categoryDAO->getAllCategories();
foreach ($categories as $category) {
    $body_page->setContent("category", str_replace(' ', '_', $category->getName()));
    $body_page->setContent("categorylabel", $category->getName());
    $string_builder = new QueryStringBuilder('eliminautente.php');
        $string_builder->add("category_id", $category->getId());

        $body_page->setContent("category", $string_builder->build());
}

?>