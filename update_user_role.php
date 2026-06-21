<?php
session_start();

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

header("Content-Type: application/json");

if (!isset($_POST['user']) || !isset($_POST['role'])) {
    echo json_encode(["success" => false, "error" => "Parametri mancanti"]);
    exit;
}

$userId = intval($_POST['user']);
$roleId = intval($_POST['role']);

$factory = new DataLayer(new DB_Connection());
$userRoleDAO = $factory->getUserRoleDAO();

$userRoleDAO->removeAllRolesFromUser($userId);

$ok = $userRoleDAO->addRoleToUser($userId, $roleId);
$ok = $userRoleDAO->addRoleToUser($userId, $roleId);



echo json_encode(["success" => $ok]);
