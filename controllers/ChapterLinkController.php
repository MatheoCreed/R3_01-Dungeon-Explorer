<?php

require_once __DIR__ . '/../models/ChapterAdmin.php';

class ChapterLinkController
{
    private $model;

    public function __construct()
    {
        global $db;
        $this->model = new ChapterAdmin($db);
    }

    public function index()
    {
        $chapters = $this->model->getAll();
        $links = $this->model->getLinks();

        include __DIR__ . '/../views/admin/link/link_index.php';
    }
    public function create1()
    {

        $chapters = $this->model->getAll();
        $links = $this->model->getLinks();

        include __DIR__ . '/../views/admin/link/link_create1.php';
    }

    public function create()
    {
        global $db;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapter_id = $_POST['chapter_id'];
            $next_id = $_POST['next_id'];
            $text = $_POST['choice_text'];

            $stmt = $db->prepare("
                INSERT INTO links(chapter_id, next_chapter_id, choice_text)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$chapter_id, $next_id, $text]);

            header('Location: index');
            exit;
        }

        $chapters = $this->model->getAll();
        include __DIR__ . '/../views/admin/link/link_create.php';
    }

    public function delete()
    {
        global $db;

        $id = $_GET['id'];

        $stmt = $db->prepare("DELETE FROM links WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: index');
        exit;
    }

    public function edit()
    {
        global $db;

        $id = $_GET['id'];

        // Récupérer le lien
        $stmt = $db->prepare("SELECT * FROM links WHERE id = ?");
        $stmt->execute([$id]);
        $link = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$link) {
            require __DIR__ . '/../views/404.php';
            exit;
        }

        // Mise à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $next_id = $_POST['next_id'];
            $text = $_POST['choice_text'];

            $stmt = $db->prepare("
            UPDATE links
            SET next_chapter_id = ?, choice_text = ?
            WHERE id = ?
        ");
            $stmt->execute([$next_id, $text, $id]);

            header("Location: index");
            exit;
        }

        $chapters = $this->model->getAll();

        include __DIR__ . '/../views/admin/link/link_edit.php';
    }


}
