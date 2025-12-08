<?php

class UserModel {

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT id, username, email, is_admin FROM users_aria ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {

        // Supprime automatiquement les Héros grâce au ON DELETE CASCADE !
        $stmt = $this->pdo->prepare("DELETE FROM users_aria WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
