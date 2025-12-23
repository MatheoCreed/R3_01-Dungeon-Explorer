<?php

require_once __DIR__ . '/../models/Chapter.php';

class ChapterController
{
    private PDO $pdo;

    public function __construct()
    {
        global $db;
        if (!isset($db) || !($db instanceof PDO)) {
            die("Erreur : PDO (\$db) non disponible dans ChapterController.");
        }
        $this->pdo = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getChapter(int $heroId): ?Chapter
    {
        // chapitre actuel du héros
        $stmt = $this->pdo->prepare('
            SELECT ch.id, ch.title, ch.content AS description, ch.image
            FROM Chapter ch
            JOIN Hero_Progress hp ON ch.id = hp.chapter_id
            WHERE hp.hero_id = ?
            LIMIT 1
        ');
        $stmt->execute([$heroId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        // choix
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
            (int)$row['id'],
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

        $heroId = (int)$_SESSION['hero_id'];

        $chapter = $this->getChapter($heroId);
        if (!$chapter) {
            die("Chapitre introuvable.");
        }

        include __DIR__ . '/../views/chapter_view.php';
    }

    public function nextChapter()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // si quelqu'un arrive en GET sur /next, on renvoie au chapitre
            header('Location: /R3_01-Dungeon-Explorer/chapter/show');
            exit;
        }

        $hero_id = (int)($_SESSION['hero_id'] ?? 1);

        $action = $_POST['action'] ?? null;

        // ✅ Choix -> update progression -> retour show via ROUTER
        if ($action === 'choose') {
            $next_chapter_id = (int)($_POST['next_chapter_id'] ?? 0);

            if ($next_chapter_id <= 0) {
                header('Location: /R3_01-Dungeon-Explorer/chapter/show');
                exit;
            }

            $stmt = $this->pdo->prepare('
                UPDATE Hero_Progress
                SET chapter_id = ?
                WHERE hero_id = ?
            ');
            $stmt->execute([$next_chapter_id, $hero_id]);

            // IMPORTANT : redirection vers une route du Router
            header('Location: /R3_01-Dungeon-Explorer/chapter/show');
            exit;
        }

        // ✅ Fight -> redirection vers route combat/show
        if ($action === 'fight') {
            $chapter_id = (int)($_POST['chapter_id'] ?? 0);

            header('Location: /R3_01-Dungeon-Explorer/combat/show?chapter=' . $chapter_id);
            exit;
        }

        // fallback
        header('Location: /R3_01-Dungeon-Explorer/chapter/show');
        exit;
    }
}
