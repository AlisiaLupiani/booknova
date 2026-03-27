<?php



if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"wishlist\".php");
    exit;
}
$body_page = new Template("html/wishlist/wishlist.html");

?>