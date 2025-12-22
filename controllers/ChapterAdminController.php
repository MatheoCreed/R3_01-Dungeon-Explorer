<?php

require_once __DIR__ . '/../models/ChapterAdmin.php';

class ChapterAdminController
{
    private $model;

    public function __construct()
    {
        global $db;

        if (!isset($db)) {
            die("Erreur : la connexion PDO (\$db) n'est pas accessible dans ChapterAdminController.");
        }

        $this->model = new ChapterAdmin($db);
    }

    public function index()
    {
        $chapters = $this->model->getAll();
        include __DIR__ . '/../views/admin/chapter/index.php';
    }

    /* ===========================================================
        CREATE
    ============================================================ */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $imagePath = null;

            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $originalName = $_FILES['image_file']['name'];
                $tmpFile      = $_FILES['image_file']['tmp_name'];

                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
                if (!in_array($ext, $allowed)) die("Erreur : format d'image non valide");

                $baseName = preg_replace('/[^a-zA-Z0-9-_]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $newName = $baseName . "_" . uniqid() . "." . $ext;

                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/R3_01-Dungeon-Explorer/sprites/background/";
                $finalPath = $uploadDir . $newName;

                if (!move_uploaded_file($tmpFile, $finalPath)) die("Erreur lors du déplacement du fichier.");

                $imagePath = "/R3_01-Dungeon-Explorer/sprites/background/" . $newName;
            }

            $this->model->create($_POST['content'], $imagePath, $_POST['title']);

            header("Location: index");
            exit;
        }

        include __DIR__ . '/../views/admin/chapter/create.php';
    }

    /* ===========================================================
        EDIT
    ============================================================ */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $chapter = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $imagePath = $chapter['image'] ?? null;

            // Si nouvelle image uploadée
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {

                // Supprimer ancienne image
                if (!empty($chapter['image'])) {
                    $oldFile = $_SERVER['DOCUMENT_ROOT'] . $chapter['image'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }

                // Upload nouvelle image
                $originalName = $_FILES['image_file']['name'];
                $tmpFile      = $_FILES['image_file']['tmp_name'];

                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
                if (!in_array($ext, $allowed)) die("Erreur : format d'image non valide");

                $baseName = preg_replace('/[^a-zA-Z0-9-_]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $newName = $baseName . "_" . uniqid() . "." . $ext;

                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/R3_01-Dungeon-Explorer/sprites/background/";
                $finalPath = $uploadDir . $newName;

                if (!move_uploaded_file($tmpFile, $finalPath)) die("Erreur lors du déplacement du fichier.");

                $imagePath = "/R3_01-Dungeon-Explorer/sprites/background/" . $newName;
            }

            // Mise à jour BDD
            $this->model->update($id, $_POST['title'], $_POST['content'], $imagePath);

            header("Location: index");
            exit;
        }

        include __DIR__ . '/../views/admin/chapter/edit.php';
    }

    /* ===========================================================
        DELETE
    ============================================================ */
    public function delete()
    {
        $id = $_GET['id'];
        $chapter = $this->model->getById($id);

        // Supprimer l'image associée
        if ($chapter && !empty($chapter['image'])) {
            $file = $_SERVER['DOCUMENT_ROOT'] . $chapter['image'];
            if (file_exists($file)) unlink($file);
        }

        $this->model->delete($id);

        header("Location: index");
        exit;
    }
}
