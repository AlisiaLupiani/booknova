<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");
require_once("include/utility/QueryStringBuilder.php");

$factory = new DataLayer(new DB_Connection());
$permission = new PermissionProxy($factory);
$permission->checkPermission("category_view");

$body_page = new Template("html/visualizza_categorie/visualizza_categorie.html");

$categoryDAO = $factory->getCategoryDAO();
$categories = $categoryDAO->getAllCategories();

foreach ($categories as $category) {
    $body_page->setContent("category_id", $category->getId());
    $body_page->setContent("category", str_replace(' ', '_', $category->getName()));
    $body_page->setContent("categorylabel", $category->getName());

    $string_builder = new QueryStringBuilder('elimina_categoria.php');
    $string_builder->add("category_id", $category->getId());

    $body_page->setContent("categoryhref", $string_builder->build());
}
?>
