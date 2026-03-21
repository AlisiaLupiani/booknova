<?php
// Assicurati che session_start() sia attivo nel file principale (index.php)

$messaggio_errore = "";

// Controlliamo se l'utente ha inviato il form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // ESEMPIO DI LOGICA (Dovrai usare il tuo DataLayer qui)
    // $utente = $dataLayer->validazioneUtente($email, $password);
    
    // Simulazione controllo database
    if ($email === "test@esempio.it" && hash_equals("12345", $password)) {
        // LOGIN OK
        $_SESSION['utente_loggato'] = true;
        $_SESSION['user_email'] = $email;
        
        // Reindirizziamo alla dashboard o area privata
        header("Location: area_privata.php"); 
        exit;
    } else {
        // LOGIN FALLITO
        // Passiamo il parametro error nell'URL come avevi chiesto
        header("Location: index.php?error=1");
        exit;
    }
}

// Prepariamo la pagina HTML del login
$body_page = new Template("html/login/login.html");

// Se nell'URL c'è ?error=1, mostriamo il messaggio nel template
if (isset($_GET['error']) && $_GET['error'] == '1') {
    $body_page->setContent("messaggio_errore", "Email o password errati!");
} else {
    $body_page->setContent("messaggio_errore", "");
}
?>
