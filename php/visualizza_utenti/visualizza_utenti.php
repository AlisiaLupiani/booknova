<?php

require_once("include/utility/QueryStringBuilder.php");
$body_page = new Template("html/visualizza_utenti/visualizza_utenti.html");
$dataLayer = new DataLayer(new DB_Connection());
$userDAO = $dataLayer->getUserDAO();
$users = $userDAO->getAllUsers();



# Prende tutti i libri
foreach ($users as $user) {
      
        $body_page->setContent("user_id", $user->getId());
        $body_page->setContent("name", $user->getName());
        $body_page->setContent("surname", $user->getSurname());
        $body_page->setContent("email", $user->getEmail());
        $body_page->setContent("indirizzo", $user->getIndirizzo ());
 


        $string_builder = new QueryStringBuilder('eliminautente.php');
        $string_builder->add("user_id", $user->getId());

        $body_page->setContent("user", $string_builder->build());


}
?>