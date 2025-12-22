<?php
require_once "models/ClassRepository.php";

class AdminClassController
{
    private ClassRepository $repo;

    public function __construct()
    {
        $this->repo = new ClassRepository();
    }

    public function index()
    {
        $classes = $this->repo->getAll();
        require "views/admin/class/index.php";
    }

    /* ===========================================================
        CREATE (page)
    ============================================================ */
    public function create()
    {
        require "views/admin/class/create.php";
    }

    /* ===========================================================
        STORE (traitement)
    ============================================================ */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: create");
            exit;
        }

        $imagePath = null;

        // Upload image
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $originalName = $_FILES['image_file']['name'];
            $tmpFile      = $_FILES['image_file']['tmp_name'];

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) die("Erreur : format d'image non valide");

            $baseName = preg_replace('/[^a-zA-Z0-9-_]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $newName  = $baseName . "_" . uniqid() . "." . $ext;

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/R3_01-Dungeon-Explorer/sprites/joueur/";
            $finalPath = $uploadDir . $newName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!move_uploaded_file($tmpFile, $finalPath)) die("Erreur lors du déplacement du fichier.");

            // Chemin stocké en BDD (URL)
            $imagePath = "/R3_01-Dungeon-Explorer/sprites/joueur/" . $newName;
        }

        $class = new GameClass(
            $_POST["name"],
            $_POST["description"],
            $_POST["base_pv"],
            $_POST["base_mana"],
            $_POST["strength"],
            $_POST["initiative"],
            $_POST["max_items"],
            null,
            $imagePath
        );

        $this->repo->create($class);
        header("Location: index");
        exit;
    }

    /* ===========================================================
        EDIT (page)
    ============================================================ */
    public function edit()
    {
        $id = (int)($_GET["id"] ?? 0);
        $class = $this->repo->getById($id);

        if (!$class) die("Classe introuvable.");

        require "views/admin/class/edit.php";
    }

    /* ===========================================================
        UPDATE (traitement)
    ============================================================ */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index");
            exit;
        }

        $id = (int)($_POST["id"] ?? 0);
        $existing = $this->repo->getById($id);

        if (!$existing) die("Classe introuvable.");

        // Par défaut : on garde l'image existante
        $imagePath = $existing->getImage();

        // Si nouvelle image uploadée
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {

            // 1) supprimer ancienne image (si existante)
            if (!empty($existing->getImage())) {
                $oldFile = $_SERVER['DOCUMENT_ROOT'] . $existing->getImage();
                if (file_exists($oldFile)) unlink($oldFile);
            }

            // 2) upload nouvelle image
            $originalName = $_FILES['image_file']['name'];
            $tmpFile      = $_FILES['image_file']['tmp_name'];

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) die("Erreur : format d'image non valide");

            $baseName = preg_replace('/[^a-zA-Z0-9-_]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $newName  = $baseName . "_" . uniqid() . "." . $ext;

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/R3_01-Dungeon-Explorer/sprites/joueur/";
            $finalPath = $uploadDir . $newName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!move_uploaded_file($tmpFile, $finalPath)) die("Erreur lors du déplacement du fichier.");

            $imagePath = "/R3_01-Dungeon-Explorer/sprites/joueur/" . $newName;
        }

        // 3) update BDD
        $class = new GameClass(
            $_POST["name"],
            $_POST["description"],
            $_POST["base_pv"],
            $_POST["base_mana"],
            $_POST["strength"],
            $_POST["initiative"],
            $_POST["max_items"],
            $id,
            $imagePath
        );

        $this->repo->update($class);
        header("Location: index");
        exit;
    }

    /* ===========================================================
        DELETE
    ============================================================ */
    public function delete()
    {
        $id = (int)($_GET["id"] ?? 0);
        $existing = $this->repo->getById($id);

        // Supprimer l'image associée
        if ($existing && !empty($existing->getImage())) {
            $file = $_SERVER['DOCUMENT_ROOT'] . $existing->getImage();
            if (file_exists($file)) unlink($file);
        }

        $this->repo->delete($id);

        header("Location: index");
        exit;
    }
}
