<?php

require_once __DIR__ . '/../models/ChapterAdmin.php';

class ChapterAdminController
{
    private $model;

    public function __construct()
    {
        // On récupère le PDO défini dans core/Database.php
        global $db;

        if (!isset($db)) {
            die("Erreur : la connexion PDO (\$db) n'est pas accessible dans ChapterAdminController.");
        }

        // On passe PDO au model
        $this->model = new ChapterAdmin($db);
    }

    public function index()
    {
        $chapters = $this->model->getAll();
        include __DIR__ . '/../views/admin/chapter/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->model->create(
                $_POST['content'],
                $_POST['image']
            );

            header("Location: index");
            exit;
        }

        include __DIR__ . '/../views/admin/chapter/create.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->model->update(
                $id,
                $_POST['title'],
                $_POST['content'],
                $_POST['image']
            );

            header("Location: index");
            exit;
        }

        $chapter = $this->model->getById($id);
        include __DIR__ . '/../views/admin/chapter/edit.php';
    }

    public function delete()
    {
        $id = $_GET['id'];
        $this->model->delete($id);

        header("Location: index");
        exit;
    }
}
