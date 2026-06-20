<?php

// Import
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/AuthManager.php");
require_once("include/model/proxy/PermissionProxy.php");

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DAO
$factory = new DataLayer(new DB_Connection());
$userDAO = $factory->getUserDAO();

// --- LOGIN SUBMIT ---
if (isset($_POST["email"]) && isset($_POST["password"])) {

    $user = $userDAO->getUserByEmail($_POST["email"]);

    // Check credentials
    if ($user != null && AuthManager::verifyPasswordSHA($_POST["password"], $user->getPassword())) {

        // Save session data
        $_SESSION["auth"] = true;
        $_SESSION["id"] = $user->getId();
        $_SESSION["user_id"] = $user->getId();
        $_SESSION["name"] = $user->getName();
        $_SESSION["surname"] = $user->getSurname();
        $_SESSION["email"] = $user->getEmail();

        // Save role string (not used for permissions but harmless)
        $_SESSION["role"] = strtoupper($user->getRole()->toString());

        // --- NEW PERMISSION SYSTEM ---
        $permission = new PermissionProxy($factory);

        // If user has backend access → go to admin dashboard
        if ($permission->userHasPermission($user->getId(), "access_backend")) {
            header("Location: index_admin.php?user_id=" . $user->getId());
            exit;
        }

        // --- NORMAL USER REDIRECT ---
        $defaultRedirect = 'index.php?user_id=' . $user->getId(); 
        $redirect = $defaultRedirect;

        if (isset($_REQUEST["reference"]) && !empty($_REQUEST["reference"])) {
            $decoded = base64_decode($_REQUEST["reference"], true);

            if ($decoded !== false && preg_match('/^[a-zA-Z0-9_\-\.\/\?&\=]+$/', $decoded)) {
                $redirect = $decoded;

                if (preg_match('/^index\.php(\?user_id=)?$/', $redirect)) {
                    $redirect = $defaultRedirect;
                }
            }
        }

        header("Location: " . $redirect);
        exit;

    } else {
        // Wrong credentials
        header("Location: login.php?error=on");
        exit;
    }
}

// --- LOAD LOGIN PAGE ---
$body_page = new Template("html/login/login.html");

// LOGIN / LOGOUT BUTTON
if (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) {
    $login_button = '<a href="logout.php" class="nav-link">Logout (' . $_SESSION["name"] . ')</a>';
} else {
    $login_button = '<a href="login.php" class="nav-link">Accedi</a>';
}
$body_page->setContent("auth_button", $login_button);

// REFERENCE HANDLING
if (isset($_REQUEST["reference"]) && !empty($_REQUEST["reference"])) {
    $reference = $_REQUEST["reference"];
} else {
    $reference = base64_encode("index.php");
}
$body_page->setContent("reference_page", $reference);

// ERROR MESSAGE
if (isset($_GET["error"])) {
    $body_page->setContent("error", "Invalid username or password.");
}

?>
