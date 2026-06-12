<?php

require_once("include/model/User.php");
require_once("include/model/proxy/UserProxy.php");
require_once("include/db/DAO.php");

class UserDAO extends DAO {

    private PDOStatement $stmtGetUserById;
    private PDOStatement $stmtGetAllUsers;
    private PDOStatement $stmtGetAllUsersExceptId;
    private PDOStatement $stmtGetUserByEmail;
    private PDOStatement $stmtGetAllUsersByRole;
    private PDOStatement $stmtGetAllUsersCount;
    private PDOStatement $stmtGetAllUsersByGenericString;
    private PDOStatement $stmtInsertUser;
    private PDOStatement $stmtUpdateUser;
    private PDOStatement $stmtUpdatePassword;
    private PDOStatement $stmtDeleteUser;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetUserById = $this->conn->prepare("SELECT * FROM UTENTE WHERE ID = ?;");
        $this->stmtGetAllUsers = $this->conn->prepare("SELECT * FROM UTENTE;");
        $this->stmtGetUserByEmail = $this->conn->prepare("SELECT * FROM UTENTE WHERE EMAIL = ?;");
        $this->stmtGetAllUsersCount = $this->conn->prepare("SELECT COUNT(*) AS COUNTER FROM UTENTE WHERE ID_RUOLO = ?;");
        $this->stmtGetAllUsersByRole = $this->conn->prepare("SELECT * FROM UTENTE WHERE ID_RUOLO = ?;");
        $this->stmtGetAllUsersExceptId = $this->conn->prepare("SELECT * FROM UTENTE WHERE ID != ?;");
        $this->stmtGetAllUsersByGenericString = $this->conn->prepare(
            "SELECT * FROM UTENTE WHERE NOME LIKE ? OR COGNOME LIKE ? OR EMAIL LIKE ? OR INDIRIZZO LIKE ?;"
        );

        $this->stmtInsertUser = $this->conn->prepare(
            "INSERT INTO UTENTE (NOME, COGNOME, EMAIL, PASSWORD, ID_RUOLO, INDIRIZZO) VALUES (?, ?, ?, ?, ?, ?);"
        );

        $this->stmtUpdateUser = $this->conn->prepare(
            "UPDATE UTENTE SET NOME = ?, COGNOME = ?, EMAIL = ?, PASSWORD = ?, ID_RUOLO = ?, INDIRIZZO = ? WHERE ID = ?;"
        );

        $this->stmtUpdatePassword = $this->conn->prepare("UPDATE UTENTE SET PASSWORD = ? WHERE ID = ?;");
        $this->stmtDeleteUser = $this->conn->prepare("DELETE FROM UTENTE WHERE ID = ?;");
    }

    public function getUserById(int $id): ?User {
        $this->stmtGetUserById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetUserById->execute();

        $rs = $this->stmtGetUserById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createUser($rs) : null;
    }

    public function getAllUsersExceptId(int $id): array {
        $this->stmtGetAllUsersExceptId->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetAllUsersExceptId->execute();

        $result = [];

        while ($rs = $this->stmtGetAllUsersExceptId->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createUser($rs);
        }

        return $result;
    }

    public function getAllUsers(): array {
        $this->stmtGetAllUsers->execute();

        $result = [];

        while ($rs = $this->stmtGetAllUsers->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createUser($rs);
        }

        return $result;
    }

    public function getUserByEmail(string $email): ?User {
        $this->stmtGetUserByEmail->bindValue(1, $email, PDO::PARAM_STR);
        $this->stmtGetUserByEmail->execute();

        $rs = $this->stmtGetUserByEmail->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createUser($rs) : null;
    }

    public function getUserByRole(int $roleId): array {
        $this->stmtGetAllUsersByRole->bindValue(1, $roleId, PDO::PARAM_INT);
        $this->stmtGetAllUsersByRole->execute();

        $result = [];

        while ($rs = $this->stmtGetAllUsersByRole->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createUser($rs);
        }

        return $result;
    }

    public function getAllUsersCount(int $roleId): int {
        $this->stmtGetAllUsersCount->bindValue(1, $roleId, PDO::PARAM_INT);
        $this->stmtGetAllUsersCount->execute();

        $rs = $this->stmtGetAllUsersCount->fetch(PDO::FETCH_ASSOC);

        return (int) $rs['COUNTER'];
    }

    public function getAllUsersByGenericString(string $string): array {
        $search = '%' . $string . '%';

        $this->stmtGetAllUsersByGenericString->bindValue(1, $search, PDO::PARAM_STR);
        $this->stmtGetAllUsersByGenericString->bindValue(2, $search, PDO::PARAM_STR);
        $this->stmtGetAllUsersByGenericString->bindValue(3, $search, PDO::PARAM_STR);
        $this->stmtGetAllUsersByGenericString->bindValue(4, $search, PDO::PARAM_STR);
        $this->stmtGetAllUsersByGenericString->execute();

        $result = [];

        while ($rs = $this->stmtGetAllUsersByGenericString->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createUser($rs);
        }

        return $result;
    }

    public function getAllUsersByGenericStrings(array $strings): array {
        $result = [];

        foreach ($strings as $string) {
            foreach ($this->getAllUsersByGenericString($string) as $user) {
                $result[$user->getId()] = $user;
            }
        }

        return array_values($result);
    }

    public function storeUser(User $user): ?User {
        if ($user->getId() !== null) {
            $this->stmtUpdateUser->bindValue(1, $user->getName(), PDO::PARAM_STR);
            $this->stmtUpdateUser->bindValue(2, $user->getSurname(), PDO::PARAM_STR);
            $this->stmtUpdateUser->bindValue(3, $user->getEmail(), PDO::PARAM_STR);
            $this->stmtUpdateUser->bindValue(4, $user->getPassword(), PDO::PARAM_STR);
            $this->stmtUpdateUser->bindValue(5, $this->getRoleIdFromUser($user), PDO::PARAM_INT);
            $this->stmtUpdateUser->bindValue(6, $user->getIndirizzo(), PDO::PARAM_STR);
            $this->stmtUpdateUser->bindValue(7, $user->getId(), PDO::PARAM_INT);

            if ($this->stmtUpdateUser->execute()) {
                return $user;
            }
        } else {
            $this->stmtInsertUser->bindValue(1, $user->getName(), PDO::PARAM_STR);
            $this->stmtInsertUser->bindValue(2, $user->getSurname(), PDO::PARAM_STR);
            $this->stmtInsertUser->bindValue(3, $user->getEmail(), PDO::PARAM_STR);
            $this->stmtInsertUser->bindValue(4, $user->getPassword(), PDO::PARAM_STR);
            $this->stmtInsertUser->bindValue(5, 2, PDO::PARAM_INT);
            $this->stmtInsertUser->bindValue(6, $user->getIndirizzo(), PDO::PARAM_STR);

            if ($this->stmtInsertUser->execute()) {
                $user->setId((int) $this->conn->lastInsertId());
                return $user;
            }
        }

        return null;
    }

    public function updatePassword(int $userId, string $newPasswordHash): bool {
        $this->stmtUpdatePassword->bindValue(1, $newPasswordHash, PDO::PARAM_STR);
        $this->stmtUpdatePassword->bindValue(2, $userId, PDO::PARAM_INT);

        return $this->stmtUpdatePassword->execute();
    }

    public function deleteUser(User $user): bool {
        $this->stmtDeleteUser->bindValue(1, $user->getId(), PDO::PARAM_INT);

        return $this->stmtDeleteUser->execute();
    }

    public function deleteUserById(int $id): bool {
        $this->stmtDeleteUser->bindValue(1, $id, PDO::PARAM_INT);

        return $this->stmtDeleteUser->execute();
    }

    private function createUser(array $rs): User {
        $user = new UserProxy($this->dataLayer);
        $user->setId((int) $rs['ID']);
        $user->setName($rs['NOME']);
        $user->setSurname($rs['COGNOME']);
        $user->setEmail($rs['EMAIL']);
        $user->setPassword($rs['PASSWORD']);
        $user->setRoleId((int) $rs['ID_RUOLO']);
        $user->setIndirizzo($rs['INDIRIZZO']);

        return $user;
    }

    private function getRoleIdFromUser(User $user): int {
        if (method_exists($user, 'getRoleId')) {
            return (int) $user->getRoleId();
        }

        $role = $user->getRole();

        if (is_object($role) && method_exists($role, 'getId')) {
            return (int) $role->getId();
        }

        return (int) $role;
    }

    public function createEntity(): User {
        return new UserProxy($this->dataLayer);
    }
}

?>