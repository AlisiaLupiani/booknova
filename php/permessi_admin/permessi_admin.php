<?php

require_once("include/template2.inc.php");
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// 🔒 Controllo login
if (!isset($_SESSION['auth'])) {
    header("Location: ../login.php?reference=gestione_permessi.php");
    exit;
}

// 🔧 Inizializzo DataLayer
$dataLayer = new DataLayer(new DB_Connection());

// 🔧 Carico template
$body_page = new Template("html/permessi_admin/permessi_admin.html");

// ------------------------------------------------------------
// SEZIONE 1 — UTENTI + RUOLI
// ------------------------------------------------------------
$users = $dataLayer->getUserDAO()->getAllUsers();
$roles = $dataLayer->getRoleDAO()->getAllRoles();

$users_rows = "";

foreach ($users as $u) {

    // Ruolo attuale dell’utente (oggetto Role)
    $currentRole = $dataLayer->getUserRoleDAO()->getGroupByUserId($u->getId());

    // Select ruoli
    $select = "<select name='role_{$u->getId()}' onchange='location.href=\"update_user_role.php?user={$u->getId()}&role=\"+this.value'>";
    foreach ($roles as $r) {
        $selected = ($currentRole && $currentRole->getId() == $r->getId()) ? "selected" : "";
        $select .= "<option value='{$r->getId()}' $selected>{$r->getRuolo()}</option>";
    }
    $select .= "</select>";

    $users_rows .= "
        <tr>
            <td>{$u->getName()}</td>
            <td>{$u->getEmail()}</td>
            <td>" . ($currentRole ? $currentRole->getRuolo() : "Nessuno") . "</td>
            <td>$select</td>
        </tr>
    ";
}

$body_page->setContent("users_rows", $users_rows);

// ------------------------------------------------------------
// SEZIONE 2 — RUOLI + SERVIZI
// ------------------------------------------------------------
$roles_rows = "";
$services = $dataLayer->getServiceDAO()->getAllServices();

foreach ($roles as $r) {

    // Servizi assegnati al ruolo (array di RoleService)
    $roleServices = $dataLayer->getRoleServiceDAO()->getServicesByRole($r->getId());

    $serviceNames = [];

    foreach ($roleServices as $rs) {
        // Recupero oggetto Service tramite serviceId
        $service = $dataLayer->getServiceDAO()->getById($rs->getServiceId());
        if ($service) {
            $serviceNames[] = $service->getName();
        }
    }

    $serviceList = implode(", ", $serviceNames);

    $roles_rows .= "
        <tr>
            <td>{$r->getRuolo()}</td>
            <td>$serviceList</td>
            <td><a href='modifica_servizi_ruolo.php?id={$r->getId()}'>Modifica</a></td>
        </tr>
    ";
}

$body_page->setContent("groups_rows", $roles_rows);

// ------------------------------------------------------------
// SEZIONE 3 — LISTA SERVIZI
// ------------------------------------------------------------
$services_rows = "";

foreach ($services as $s) {
    $services_rows .= "
        <tr>
            <td>{$s->getName()}</td>
            <td><a href='modifica_servizio.php?id={$s->getId()}'>Modifica</a></td>
            <td><a href='elimina_servizio.php?id={$s->getId()}'>Elimina</a></td>
        </tr>
    ";
}

$body_page->setContent("services_rows", $services_rows);

$body_page->close();
?>
