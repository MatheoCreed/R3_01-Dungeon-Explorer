<?php

// controllers/ChapterController.php

require_once __DIR__ . '/../models/Chapter.php';

class ChapterController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Charge un Chapter et ses choix depuis la BDD
    public function getChapter($id)
    {
        $stmt = $this->pdo->prepare('SELECT id, title, content AS description, image FROM Chapter WHERE id = ?');
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        // Récupération des choix (Links)
        $stmt2 = $this->pdo->prepare('SELECT next_chapter_id, choice_text FROM Links WHERE chapter_id = ? ORDER BY id ASC');
        $stmt2->execute([(int)$id]);
        $choices = [];
        while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $choices[] = ['text' => $r['choice_text'], 'chapter' => (int)$r['next_chapter_id']];
        }

        return new Chapter($row['id'], $row['title'] ?? '', $row['description'] ?? '', $row['image'] ?? '', $choices);
    }

    public function show($id)
    {
        $chapter = $this->getChapter($id);
        if ($chapter) {
            // La vue `chapter_view.php` attend $chapter ; on passe aussi le controller si besoin
            $chapterController = $this;
            $chapterId = $id;
            include __DIR__ . '/../views/chapter_view.php';
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Chapitre non trouvé";
        }
    }
}