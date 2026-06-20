<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");
require_once("include/utility/QueryStringBuilder.php");

$factory = new DataLayer(new DB_Connection());

$permission = new PermissionProxy($factory);

$permission->checkPermission("users_view");

// DAO
$userDAO = $factory->getUserDAO();
$users = $userDAO->getAllUsers();

// Template
$body_page = new Template("html/visualizza_utenti/visualizza_utenti.html");

// Loop utenti
foreach ($users as $user) {

    $body_page->setContent("user_id", $user->getId());
    $body_page->setContent("name", $user->getName());
    $body_page->setContent("surname", $user->getSurname());
    $body_page->setContent("email", $user->getEmail());
    $body_page->setContent("indirizzo", $user->getIndirizzo());

    $string_builder = new QueryStringBuilder('eliminautente.php');
    $string_builder->add("user_id", $user->getId());

    $body_page->setContent("user", $string_builder->build());
}
?>
