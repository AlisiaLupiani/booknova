<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");

$factory = new DataLayer(new DB_Connection());

$permission = new PermissionProxy($factory);

$permission->checkPermission("category_add");

// Template
$body_page = new Template("html/aggiungi_categoria/aggiungi_categoria.html");
?>
