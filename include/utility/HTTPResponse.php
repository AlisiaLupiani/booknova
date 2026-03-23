<?php

class HTTPResponse {
 
    private string $status;
    private array $params = [];
    private int $httpCode = 200;

    public function __construct(string $status){
        $this->status = strtoupper($status); 
    }

    public function setHttpCode(int $code): void {
        $this->httpCode = $code;
    }

    public function add(string $key, mixed $value){
        $this->params[strtolower($key)] = $value;
    }

    public function addEncode(string $key, mixed $value){
        $this->params[strtolower($key)] = base64_encode($value);
    }

    public function build(){
        $this->params["status"] = $this->status;
        return json_encode($this->params);
    }

    public function send(): void {
        http_response_code($this->httpCode);
        if ($this->httpCode !== 204) {
            header('Content-Type: application/json');
            echo $this->build();
        }
        exit;
    }

    public function cleanParams(): void{
        $this->params = [];
    }

    public function refresh(string $status){
        $this->status = strtoupper($status); 
        $this->cleanParams();
    }

    /* =======================
       SUCCESSO
    ======================= */

    public static function ok(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("OK");
        $res->setHttpCode(200);
        $res->add("title_message", "Operazione completata.");
        $res->add("text_message", $message ?? "Operazione eseguita con successo.");
        return $res;
    }

    public static function okCreated(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("CREATED");
        $res->setHttpCode(201);
        $res->add("title_message", "Operazione completata.");
        $res->add("text_message", $message ?? "Operazione eseguita con successo.");
        return $res;
    }

    public static function okNoContent(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("OK NO CONTENT");
        $res->setHttpCode(204);
        return $res;
    }

    /* =======================
       ERRORI CLIENT (400)
    ======================= */

    public static function badRequest(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("BAD_REQUEST");
        $res->setHttpCode(400);
        $res->add("title_message", "Richiesta non valida.");
        $res->add("text_message", $message ?? "I dati inviati non sono corretti.");
        return $res;
    }

    /* =======================
       AUTENTICAZIONE
    ======================= */

    public static function unauthorized(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("UNAUTHORIZED");
        $res->setHttpCode(401);
        $res->add("title_message", "Non autenticato.");
        $res->add("text_message", $message ?? "Effettua il login per continuare.");
        return $res;
    }

    public static function sessionError(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("SESSION_ERROR");
        $res->setHttpCode(401);
        $res->add("title_message", "Errore di sessione.");
        $res->add("text_message", $message ?? "La sessione non è attiva.");
        return $res;
    }

    public static function forbidden(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("FORBIDDEN");
        $res->setHttpCode(403);
        $res->add("title_message", "Accesso negato.");
        $res->add("text_message", $message ?? "Non hai i permessi per questa operazione.");
        return $res;
    }

    /* =======================
       RISORSE
    ======================= */

    public static function notFound(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("NOT_FOUND");
        $res->setHttpCode(404);
        $res->add("title_message", "Risorsa non trovata.");
        $res->add("text_message", $message ?? "La risorsa richiesta non esiste.");
        return $res;
    }

    /* =======================
       ERRORI SERVER (500)
    ======================= */

    public static function genericServerError(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("GENERIC_ERROR");
        $res->setHttpCode(500);
        $res->add("title_message", "Errore generico.");
        $res->add("text_message", $message ?? "Il server non ha potuto elaborare la richiesta.");
        return $res;
    }

    public static function databaseError(?string $message = null): HTTPResponse {
        $res = new HTTPResponse("DB_ERROR");
        $res->setHttpCode(500);
        $res->add("title_message", "Errore database.");
        $res->add("text_message", $message ?? "Errore durante l'accesso ai dati.");
        return $res;
    }
}

?>