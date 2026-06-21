<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");

// 🔥 CREA IL DATALAYER
$factory = new DataLayer(new DB_Connection());



// Template
$body_page = new Template("html/permessi_admin/permessi_admin.html");

// DAO
$userDAO = $factory->getUserDAO();
$roleDAO = $factory->getRoleDAO();
$userRoleDAO = $factory->getUserRoleDAO();
$serviceDAO = $factory->getServiceDAO();
$roleServiceDAO = $factory->getRoleServiceDAO();

// ===============================
// SEZIONE 1 — UTENTI + RUOLI
// ===============================
$users = $userDAO->getAllUsers();
$roles = $roleDAO->getAllRoles();

foreach ($users as $user) {

    $currentRole = $userRoleDAO->getGroupByUserId($user->getId());

    $body_page->setContent("user_id", $user->getId());
    $body_page->setContent("user_name", $user->getName());
    $body_page->setContent("user_email", $user->getEmail());
    $body_page->setContent("current_role", $currentRole ? $currentRole->getRuolo() : "Nessuno");

    // Costruisco la select ruoli
    $select = "<select class='role-select' data-user='{$user->getId()}'>";
    foreach ($roles as $r) {
        $selected = ($currentRole && $currentRole->getId() == $r->getId()) ? "selected" : "";
        $select .= "<option value='{$r->getId()}' $selected>{$r->getRuolo()}</option>";
    }
    $select .= "</select>";

    $body_page->setContent("role_select", $select);
}

// ===============================
// SEZIONE 2 — RUOLI + SERVIZI
// ===============================
foreach ($roles as $role) {

    $roleServices = $roleServiceDAO->getServicesByRole($role->getId());
    $serviceNames = [];

    foreach ($roleServices as $rs) {
        $service = $serviceDAO->getById($rs->getServiceId());
        if ($service) {
            $serviceNames[] = $service->getName();
        }
    }

    $body_page->setContent("group_name", $role->getRuolo());
    $body_page->setContent("group_services", implode(", ", $serviceNames));
}

// ===============================
// SEZIONE 3 — LISTA SERVIZI (SOLO VISUALIZZAZIONE)
// ===============================
$services = $serviceDAO->getAllServices();

foreach ($services as $service) {
    $body_page->setContent("service_id", $service->getId());
    $body_page->setContent("service_name", $service->getName());
}

?>
