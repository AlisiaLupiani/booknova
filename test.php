<?php
// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

$dataLayer = new DataLayer(new DB_Connection());
$categoryDAO = $dataLayer->getCategoryDAO();
$categories = $categoryDAO->getAllCategories();
foreach ($categories as $category) {
    echo "ID: " . $category->getId() . " - Name: " . $category->getName() . "<br>";
}

?>