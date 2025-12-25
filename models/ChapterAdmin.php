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
        $stmt = $this->pdo->query("SELECT * FROM chapter ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chapter WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($content, $image,$title)
    {
        $stmt = $this->pdo->prepare("INSERT INTO chapter (content, image, title) VALUES (?, ?,?)");
        return $stmt->execute([$content, $image, $title]);
    }

    public function update($id, $title, $content, $image)
    {
        $stmt = $this->pdo->prepare("UPDATE chapter SET title = ?, content = ?, image = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $image, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM chapter WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getLinks()
{
    $stmt = $this->pdo->query("
        SELECT id, chapter_id, next_chapter_id, choice_text
        FROM links
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
