<?php

// controllers/CombatController.php

require_once __DIR__ . '/../models/Hero.php';
require_once __DIR__ . '/../models/Monster.php';


class CombatController
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

    public function getMonster($chapterId) {
        $stmt = $this->pdo->prepare('SELECT chapter_id, monster_id FROM encounter WHERE chapter_id = ?');
        $stmt->execute([(int)$chapterId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $monster_id = $row['monster_id'];

        $stmt = $this->pdo->prepare('SELECT id, name, pv, mana, initiative, strength, attack, xp, image FROM Monster WHERE id = ?');
        $stmt->execute([(int)$monster_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        
        $stmt = $this->pdo->prepare('SELECT ml.item_id, IFNULL(i.name, CONCAT("item_", ml.item_id)) AS item_name, ml.quantity FROM Monster_Loot ml JOIN Items i ON ml.item_id = i.id WHERE ml.monster_id = ?');
        $stmt->execute([(int)$monster_id]);

        // Construire un tableau associatif item_name => quantity
        $treasures = [];
        while ($loot = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemName = $loot['item_name'] ?? ('item_' . ($loot['item_id'] ?? '')); 
            $quantity = (int)($loot['quantity'] ?? 0);
            // Si le même item apparaît plusieurs fois, additionner les quantités
            if (isset($treasures[$itemName])) {
                $treasures[$itemName] += $quantity;
            } else {
                $treasures[$itemName] = $quantity;
            }
        }

        return new Monster($row['name'], $row['pv'] ?? '', $row['mana'] ?? '', $row['initiative'] ?? '', $row['strength'] ?? '', $row['attack'] ?? '', $row['xp'] ?? '', $treasures, $row['image'] ?? '');
    }

    public function show($id)
    {
        // $id is expected to be a chapter id. Load chapter and monster for that chapter.
        $chapter = $this->getChapter($id);
        if (!$chapter) {
            header('HTTP/1.0 404 Not Found');
            echo "Chapitre non trouvé";
            return;
        }

        $monster = $this->getMonster($id);
        if ($monster) {
            // La vue `combat_view.php` attend $monster et éventuellement $chapter ; on passe aussi le controller si besoin
            $combatController = $this;
            include __DIR__ . '/../views/combat_view.php';
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Combat non trouvé";
        }
    }
}