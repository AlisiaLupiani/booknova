<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");

// 🔥 CREA IL DATALAYER
$factory = new DataLayer(new DB_Connection());

// 🔥 CREA IL PERMISSION PROXY
$permission = new PermissionProxy($factory);

// 🔥 CONTROLLA IL PERMESSO
$permission->checkPermission("author_add");

// Template
$body_page = new Template("html/aggiungi_autore/aggiungi_autore.html");
?>
