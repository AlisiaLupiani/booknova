<?php
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/template2.inc.php");

$q = trim($_GET["q"] ?? "");

$tpl = new Template("template/search_results.html");

$dataLayer = new DataLayer(new DB_Connection());
$conn = $dataLayer->getConnection();

$stmt = $conn->prepare("
    SELECT L.ID, L.TITOLO, L.IMMAGINE, L.PREZZO, A.NOME AS AUTORE
    FROM LIBRO L
    JOIN AUTORE A ON L.ID_AUTORE = A.ID
    WHERE L.TITOLO LIKE ? OR A.NOME LIKE ?
");

$search = "%$q%";
$stmt->execute([$search, $search]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($results)) {
    $tpl->setContent("no_results", "Nessun risultato trovato per \"$q\"");
} else {
    foreach ($results as $book) {
        $tpl->setContent("book_id", $book["ID"]);
        $tpl->setContent("book_title", $book["TITOLO"]);
        $tpl->setContent("book_author", $book["AUTORE"]);
        $tpl->setContent("book_price", $book["PREZZO"]);
        $tpl->setContent("book_image", $book["IMMAGINE"]);
    }
}

$tpl->close();
