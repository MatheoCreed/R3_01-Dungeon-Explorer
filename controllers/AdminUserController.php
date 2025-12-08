<?php

require_once __DIR__ .'/../models/User.php';

class AdminUserController {
    private $model;
    public function __construct() {
        // On récupère le PDO défini dans core/Database.php
        global $db;

        if (!isset($db)) {
            die("Erreur : la connexion PDO (\$db) n'est pas accessible dans ChapterAdminController.");
        }

        // On passe PDO au model
        $this->model = new UserModel($db);
    }

    public function index() {
        $users = $this->model->getAllUsers();

        require_once 'views/admin/user/index.php';
    }

    public function delete() {
        if (!isset($_GET['id'])) {
            die("ID manquant.");
        }

        $id = intval($_GET['id']);

        
        $this->model->deleteUser($id);

        header("Location: index");
        exit();
    }
}
