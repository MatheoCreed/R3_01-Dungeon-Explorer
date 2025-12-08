<?php
// models/ChapterAdminModel.php

class ChapterAdmin
{

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Chapter ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Chapter WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($content, $image)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Chapter (content, image) VALUES (?, ?)");
        return $stmt->execute([$content, $image]);
    }

    public function update($id, $title, $content, $image)
    {
        $stmt = $this->pdo->prepare("UPDATE Chapter SET title = ?, content = ?, image = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $image, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Chapter WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getLinks()
{
    $stmt = $this->pdo->query("
        SELECT id, chapter_id, next_chapter_id, choice_text
        FROM Links
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
