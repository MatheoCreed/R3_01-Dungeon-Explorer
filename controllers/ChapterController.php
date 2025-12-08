<?php

require_once __DIR__ . '/../models/Chapter.php';

class ChapterController
{
    private $pdo;

    public function __construct()
    {
        global $db;
        $this->pdo = $db;
    }

    public function getChapter($heroId)
    {
        // Récupérer le chapitre associé au héros
        $stmt = $this->pdo->prepare('
            SELECT ch.id, ch.title, ch.content AS description, ch.image
            FROM Chapter ch
            JOIN Hero_progress hp ON ch.id = hp.chapter_id
            WHERE hp.hero_id = ?
        ');
        $stmt->execute([(int)$heroId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row)
            return null;

        // Récupérer les choix (Links)
        $stmt2 = $this->pdo->prepare('
            SELECT next_chapter_id, choice_text
            FROM Links
            WHERE chapter_id = ?
            ORDER BY id ASC
        ');
        $stmt2->execute([(int)$row['id']]);

        $choices = [];
        while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $choices[] = [
                'text' => $r['choice_text'], 
                'chapter' => (int)$r['next_chapter_id']
            ];
        }

        return new Chapter(
            $row['id'],
            $row['title'] ?? '',
            $row['description'] ?? '',
            $row['image'] ?? '',
            $choices
        );
    }

    public function show()
    {
        if (!isset($_SESSION['hero_id'])) {
            die("Aucun héros sélectionné.");
        }

        $heroId = $_SESSION['hero_id'];

        $chapter = $this->getChapter($heroId);

        if (!$chapter) {
            die("Chapitre introuvable.");
        }

        include __DIR__ . '/../views/chapter_view.php';
    }

    public function nextChapter()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

            $hero_id = $_SESSION['hero_id'] ?? 1; // fallback temporaire

            if ($_POST['action'] === 'choose') {
                $next_chapter_id = (int)($_POST['next_chapter_id'] ?? 0);

                // Mise à jour du chapitre du héros
                $stmt = $this->pdo->prepare('
                    UPDATE Hero_Progress 
                    SET chapter_id = ?
                    WHERE hero_id = ?
                ');
                $stmt->execute([$next_chapter_id, $hero_id]);

                header('Location: show');

                exit;
            }

            if ($_POST['action'] === 'fight') {
                $chapter_id = (int)($_POST['chapter_id'] ?? 0);

                // Redirection vers le combat
                header('Location: index.php?action=combat&chapter=' . $chapter_id);
                exit;
            }
        }
    }
}
