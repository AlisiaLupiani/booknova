<?php

class AuthManager{



    public function __construct(){}


    public static function encryptPasswordSHA(string $password): string {
        return hash('sha256', $password);
    }
    
    public static function encryptPasswordMD5(string $password): string {
        return md5($password);
    }

    public static function verifyPasswordSHA(string $plainPassword, string $hashedPassword): bool {
        return AuthManager::encryptPasswordSHA($plainPassword) === $hashedPassword;
    }

    public static function verifyPasswordMD5(string $plainPassword, string $hashedPassword): bool {
        return AuthManager::encryptPasswordMD5($plainPassword) === $hashedPassword;
    }
}


?>