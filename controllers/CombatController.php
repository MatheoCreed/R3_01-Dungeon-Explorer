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
        $stmt->execute([(int) $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row)
            return null;

        // Récupération des choix (Links)
        $stmt2 = $this->pdo->prepare('SELECT next_chapter_id, choice_text FROM Links WHERE chapter_id = ? ORDER BY id ASC');
        $stmt2->execute([(int) $id]);
        $choices = [];
        while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $choices[] = ['text' => $r['choice_text'], 'chapter' => (int) $r['next_chapter_id']];
        }

        return new Chapter($row['id'], $row['title'] ?? '', $row['description'] ?? '', $row['image'] ?? '', $choices);
    }

    public function getMonster($chapterId)
    {
        $stmt = $this->pdo->prepare('SELECT chapter_id, monster_id FROM encounter WHERE chapter_id = ?');
        $stmt->execute([(int) $chapterId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row)
            return null;
        $monster_id = $row['monster_id'];

        $stmt = $this->pdo->prepare('SELECT id, name, pv, mana, initiative, strength, attack, xp, image FROM Monster WHERE id = ?');
        $stmt->execute([(int) $monster_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row)
            return null;

        $stmt = $this->pdo->prepare('SELECT ml.item_id, IFNULL(i.name, CONCAT("item_", ml.item_id)) AS item_name, ml.quantity, ml.drop_rate FROM Monster_Loot ml JOIN Items i ON ml.item_id = i.id WHERE ml.monster_id = ?');
        $stmt->execute([(int) $monster_id]);

        // Construire un tableau associatif item_name => quantity
        $treasures = [];
        while ($loot = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemName = $loot['item_name'] ?? ('item_' . ($loot['item_id'] ?? ''));
            $quantity = (int) ($loot['quantity'] ?? 0);
            $dropRate = (double) ($loot['drop_rate'] ?? 0);
            // Si le même item apparaît plusieurs fois, additionner les quantités
            $dropped = rand(0, 100);
            if ($dropped <= $dropRate) {
                if (isset($treasures[$itemName])) {
                    $treasures[$itemName] += $quantity;
                } else {
                    $treasures[$itemName] = $quantity;
                }
            }
        }

        return new Monster($row['name'], $row['pv'] ?? '', $row['mana'] ?? '', $row['initiative'] ?? '', $row['strength'] ?? '', $row['attack'] ?? '', $row['xp'] ?? '', $treasures, $row['image'] ?? '');
    }

    public function tourCombat($attaquant, $defenseur)
    {
        // Calcul de l'initiative
        $initiativeAttaquant = rand(1, 6) + $attaquant->initiative;
        $initiativeDefenseur = rand(1, 6) + $defenseur->initiative;

        // Détermination de l'attaquant
        if ($initiativeAttaquant >= $initiativeDefenseur || $attaquant->classe == 'Voleur') {
        
            // Choix de l’action par l'attaquant
            $choix = $attaquant->choisirAction(); // 'physique', 'magie', ou 'potion'
            if ($choix === 'physique') {
        
                // Attaque physique
                $attaque = rand(1, 6) + $attaquant->force + $attaquant->arme->bonus;
                $defense = rand(1, 6) + (int) ($defenseur->force / 2) + $defenseur->armure->bonus;
                $degats = max(0, $attaque - $defense);
                $defenseur->pv -= $degats;
            } elseif ($choix === 'magie' && $attaquant->classe == 'Magicien') {
                $spell = $attaquant->getSpell($spellId);
                
                if ($attaquant->mana >= $cout_sort) {
                    // Attaque magique
                    $attaque_magique = (rand(1, 6) + rand(1, 6)) + $cout_sort;
                    $attaquant->mana -= $cout_sort;
                    $defense = rand(1, 6) + (int) ($defenseur->force / 2) + $defenseur->armure->bonus;
                    $degats = max(0, $attaque_magique - $defense);
                }
            } elseif ($choix === 'potion') {
        
                // Boire une potion
                $potion = $attaquant->choisirPotion();
                if ($potion->type === 'pv') {
                    $attaquant->pv = min($attaquant->pv + $potion->valeur, $attaquant->pv_max);
                } elseif ($potion->type === 'mana') {
                    $attaquant->mana = min($attaquant->mana + $potion->valeur, $attaquant->mana_max);
                }
            }
        }
        // Boucle jusqu'à ce qu'un protagoniste meure
        return $attaquant->pv > 0 && $defenseur->pv > 0 ? $this->tourCombat($defenseur, $attaquant) :
            "Fin du combat";
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