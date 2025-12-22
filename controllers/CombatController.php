<?php
// controllers/CombatController.php

require_once __DIR__ . '/../models/Hero.php';
require_once __DIR__ . '/../models/Monster.php';
require_once __DIR__ . '/../models/Chapter.php';

class CombatController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getChapter($id) {
        return new Chapter($id, 'Titre', 'Desc', 'img.jpg', []); 
    } 
    public function getMonster($chapterId) {
        $stmt = $this->pdo->prepare('SELECT chapter_id, monster_id FROM Encounter WHERE chapter_id = ?');
        $stmt->execute([(int) $chapterId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        
        $monster_id = $row['monster_id'];
        $stmt = $this->pdo->prepare('SELECT * FROM Monster WHERE id = ?');
        $stmt->execute([(int) $monster_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return new Monster($data['name'], $data['pv'], $data['mana'], $data['initiative'], $data['strength'], $data['attack'], $data['xp'], [], $data['image']);
    }

    public function show($chapterId)
    {
        $heroId = $_SESSION['hero_id'] ?? 1; // ID du héros connecté
        
        // 1. Si le combat n'existe pas en session, si on change de chapitre, ou si le combat précédent est terminé, on initialise
        if (!isset($_SESSION['combat']) || $_SESSION['combat']['chapter_id'] != $chapterId || (!empty($_SESSION['combat']['finished']) && $_SESSION['combat']['finished'] === true)) {
            $this->initCombat($chapterId, $heroId);
        }

        // 2. Traitement de l'action du joueur 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            // support pour cast_spell + spell_id
            if ($action === 'cast_spell' && isset($_POST['spell_id'])) {
                $this->handlePlayerTurn('cast_spell', (int)$_POST['spell_id']);
            } else {
                $this->handlePlayerTurn($action);
            }
        }

        // 3. Préparation des données pour la vue
        $combatData = $_SESSION['combat'];
        
        $hero = $this->getHeroById($heroId); 
        $monster = $this->getMonster($chapterId);
        $chapter = $this->getChapter($chapterId);

        $stmt = $this->pdo->prepare('SELECT s.* FROM Hero_Spell hs JOIN Spell s ON hs.spell_id = s.id WHERE hs.hero_id = ?');
        $stmt->execute([$heroId]);
        $spells = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($spells as $s) {
            $spellObj = new Spell($s['id'], $s['spell_name'], $s['mana_cost'], $s['damage'], $s['description']);
            $hero->addToSpell($spellObj);
        }

        $hero->setPv($combatData['hero_pv']);
        $hero->setMana($combatData['hero_mana']);
        
        include __DIR__ . '/../views/combat_view.php';
    }

    private function initCombat($chapterId, $heroId)
    {
        $hero = $this->getHeroById($heroId);
        $monster = $this->getMonster($chapterId);

        if (!$monster) {
            die("Aucun monstre pour ce chapitre !");
        }

        $_SESSION['combat'] = [
            'chapter_id' => $chapterId,
            'hero_pv' => $hero->getPv(),
            'hero_max_pv' => $hero->getPv(), 
            'hero_mana' => $hero->getMana(),
            'hero_max_mana' => $hero->getMana(),
            'monster_pv' => $monster->getHealth(),
            'monster_max_pv' => $monster->getHealth(),
            'logs' => ["Le combat commence entre " . $hero->getName() . " et " . $monster->getName() . " !"],
            'turn' => 1,
            'finished' => false,
            'win' => false
        ];
    }

    private function handlePlayerTurn($action, $spellId = null)
    {
        if ($_SESSION['combat']['finished']) return;

        $heroId = $_SESSION['hero_id'] ?? 1;
        $hero = $this->getHeroById($heroId);

        // Charger les sorts du héros pour que les actions 'cast_spell' puissent retrouver l'objet Spell
        $stmt = $this->pdo->prepare('SELECT s.* FROM Hero_Spell hs JOIN Spell s ON hs.spell_id = s.id WHERE hs.hero_id = ?');
        $stmt->execute([$heroId]);
        $spells = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($spells as $s) {
            $spellObj = new Spell($s['id'], $s['spell_name'], $s['mana_cost'], $s['damage'], $s['description']);
            $hero->addToSpell($spellObj);
        }

        $hero->setPv($_SESSION['combat']['hero_pv']);
        $hero->setMana($_SESSION['combat']['hero_mana']);
        
        $chapterId = $_SESSION['combat']['chapter_id'];
        $monster = $this->getMonster($chapterId);
        

        $initHero = rand(1, 6) + $hero->getInitiative();
        $initMonster = rand(1, 6) + $monster->getInitiative();
        
        $this->addLog("Initiative : Héros ($initHero) vs Monstre ($initMonster).");

        $heroGoesFirst = ($initHero > $initMonster);
        if ($initHero == $initMonster) {
            $heroGoesFirst = $hero->isThief();
        }

        // Exécution des tours
        if ($heroGoesFirst) {
            $this->executeHeroAction($hero, $monster, $action, $spellId);
            if ($_SESSION['combat']['monster_pv'] > 0) {
                $this->executeMonsterAction($monster, $hero);
            }
        } else {
            $this->executeMonsterAction($monster, $hero);
            if ($_SESSION['combat']['hero_pv'] > 0) {
                $this->executeHeroAction($hero, $monster, $action, $spellId);
            }
        }

        $_SESSION['combat']['hero_pv'] = $hero->getPv();
        $_SESSION['combat']['hero_mana'] = $hero->getMana();
        $_SESSION['combat']['turn']++;

        // Vérification de fin de combat
        if ($_SESSION['combat']['hero_pv'] <= 0) {
            $_SESSION['combat']['finished'] = true;
            $_SESSION['combat']['win'] = false;
            $this->addLog("Vous êtes mort...");
        } elseif ($_SESSION['combat']['monster_pv'] <= 0) {
            $_SESSION['combat']['finished'] = true;
            $_SESSION['combat']['win'] = true;
            $this->addLog("Victoire ! Le monstre est vaincu.");
            
            $this->giveRewards($hero, $monster, $chapterId);
        }
    }

    private function executeHeroAction($hero, $monster, $action, $spellId = null)
    {
        $log = "";
        
        if ($action === 'physique') {
            $attaque = rand(1, 6) + $hero->getStrength() + $hero->getWeaponBonus();
            
            $defense = rand(1, 6) + (int)($monster->getStrength() / 2); // Pas d'armure pour le monstre dans la BDD
            
            $degats = max(0, $attaque - $defense);
            
            $_SESSION['combat']['monster_pv'] -= $degats;
            $log = "Vous attaquez physiquement (Att: $attaque vs Def: $defense). Dégâts : $degats.";

        } elseif ($action === 'magie') {
            $coutSort = 3; 
            if ($hero->getMana() >= $coutSort) {
                $attaqueMagique = rand(1, 6) + rand(1, 6) + $coutSort;
                $hero->useMana($coutSort);
                
                $defense = rand(1, 6) + (int)($monster->getStrength() / 2);
                
                $degats = max(0, $attaqueMagique - $defense);
                $_SESSION['combat']['monster_pv'] -= $degats;
                $log = "Vous lancez un sort (AttMag: $attaqueMagique vs Def: $defense). Dégâts : $degats.";
            } else {
                $log = "Pas assez de mana ! Tour perdu.";
            }

        } elseif ($action === 'cast_spell') {
            $spell = $spellId ? $hero->getSpell($spellId) : null;
            if (!$spell) {
                $log = "Sort introuvable ou non connu.";
            } else {
                $cost = $spell->getManaCost();
                if ($hero->getMana() < $cost) {
                    $log = "Pas assez de mana pour lancer {$spell->getSpellName()} ({$cost} mana).";
                } else {
                    $hero->useMana($cost);
                    $attaqueMagique = rand(1, 6) + rand(1, 6) + (int)$spell->getDamage();

                    $defense = rand(1, 6) + (int)($monster->getStrength() / 2);
                    $degats = max(0, $attaqueMagique - $defense);
                    $_SESSION['combat']['monster_pv'] -= $degats;
                    $log = "Vous lancez {$spell->getSpellName()} (AttMag: $attaqueMagique vs Def: $defense). Dégâts : $degats.";
                }
            }

        } elseif ($action === 'potion') {
            $soin = 10;
            $oldPv = $hero->getPv();
            $hero->heal($soin); 

            if ($hero->getPv() > $_SESSION['combat']['hero_max_pv']) {
                $hero->setPv($_SESSION['combat']['hero_max_pv']);
            }
            $gained = $hero->getPv() - $oldPv;
            $log = "Vous buvez une potion et récupérez $gained PV.";
        }

        $this->addLog("Héros : " . $log);
    }

    private function executeMonsterAction($monster, $hero)
    {
        $attaque = rand(1, 6) + $monster->getStrength();
        
        $defense = rand(1, 6) + $hero->getArmorBonus();
        
        if ($hero->isThief()) {
            $defense += (int)($hero->getInitiative() / 2);
        } else {
            $defense += (int)($hero->getStrength() / 2);
        }

        $degats = max(0, $attaque - $defense);
        $hero->takeDamage($degats);
        
        $this->addLog("Monstre : Attaque (Att: $attaque vs Def: $defense). Vous subissez $degats dégâts.");
    }

    private function addLog($message)
    {
        array_unshift($_SESSION['combat']['logs'], $message); 
    }

    private function getHeroById($id) {
        $stmt = $this->pdo->prepare('
            SELECT h.*, c.name as class_name 
            FROM Hero h 
            LEFT JOIN Class c ON h.class_id = c.id 
            WHERE h.id = ?
        ');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $hero = new Hero(
            $data['name'], $data['class_id'], $data['image'], $data['biography'], 
            $data['pv'], $data['mana'], $data['strength'], $data['initiative'],
            $data['armor_item_id'], $data['primary_weapon_item_id'], null, null,
            $data['xp'], $data['current_level'], $data['id']
        );
        $hero->setClassName($data['class_name']);
        return $hero;
    }

    private function giveRewards($hero, $monster, $chapterId)
    {
        // XP
        $xp = (int)$monster->getExperienceValue();

        $oldMaxPv = $_SESSION['combat']['hero_max_pv'] ?? $hero->getPv();
        $oldMaxMana = $_SESSION['combat']['hero_max_mana'] ?? $hero->getMana();
        $oldStrength = $hero->getStrength();
        $oldInitiative = $hero->getInitiative();

        $hero->gainXp($xp);
        $levelUps = $hero->getLevelUps();

        $rewards = [
            'xp' => $xp,
            'items' => []
        ];

        $sumPv = 0; $sumMana = 0; $sumStr = 0; $sumInit = 0;
        foreach ($levelUps as $lu) {
            $sumPv += $lu['pv_bonus'];
            $sumMana += $lu['mana_bonus'];
            $sumStr += $lu['strength_bonus'];
            $sumInit += $lu['initiative_bonus'];
        }

        $newBasePv = $oldMaxPv + $sumPv;
        $newBaseMana = $oldMaxMana + $sumMana;
        $newStrength = $oldStrength + $sumStr;
        $newInitiative = $oldInitiative + $sumInit;

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare('UPDATE Hero SET xp = ?, current_level = ?, pv = ?, mana = ?, strength = ?, initiative = ? WHERE id = ?');
            $stmt->execute([$hero->getXp(), $hero->getLevel(), $newBasePv, $newBaseMana, $newStrength, $newInitiative, $hero->getId()]);

            $stmt = $this->pdo->prepare('SELECT monster_id FROM Encounter WHERE chapter_id = ? LIMIT 1');
            $stmt->execute([(int)$chapterId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $monsterId = $row['monster_id'] ?? null;

            if ($monsterId) {
                $stmt = $this->pdo->prepare('SELECT ml.item_id, ml.quantity, ml.drop_rate, i.name FROM Monster_Loot ml LEFT JOIN Items i ON ml.item_id = i.id WHERE ml.monster_id = ?');
                $stmt->execute([(int)$monsterId]);
                $loots = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($loots as $loot) {
                    $dropRate = (float)$loot['drop_rate'];
                    $roll = mt_rand(0, 10000) / 10000; 
                    if ($roll <= $dropRate) {
                        $itemId = (int)$loot['item_id'];
                        $qty = (int)$loot['quantity'];

                        $stmtInv = $this->pdo->prepare('SELECT quantity FROM Inventory WHERE hero_id = ? AND item_id = ?');
                        $stmtInv->execute([$hero->getId(), $itemId]);
                        $existing = $stmtInv->fetch(PDO::FETCH_ASSOC);

                        if ($existing) {
                            $newQty = $existing['quantity'] + $qty;
                            $stmtUpdate = $this->pdo->prepare('UPDATE Inventory SET quantity = ? WHERE hero_id = ? AND item_id = ?');
                            $stmtUpdate->execute([$newQty, $hero->getId(), $itemId]);
                        } else {
                            $stmtInsert = $this->pdo->prepare('INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (?, ?, ?)');
                            $stmtInsert->execute([$hero->getId(), $itemId, $qty]);
                        }

                        $hero->addToInventory($itemId, $qty);
                        $rewards['items'][] = ['item_id' => $itemId, 'name' => $loot['name'], 'quantity' => $qty];
                    }
                }
            }

            // Mettre à jour la progression du héros vers le chapitre suivant
            $nextChapter = (int)$chapterId + 1;
            $stmtProg = $this->pdo->prepare('SELECT chapter_id FROM Hero_Progress WHERE hero_id = ? LIMIT 1');
            $stmtProg->execute([$hero->getId()]);
            $exists = $stmtProg->fetch(PDO::FETCH_ASSOC);

            if ($exists) {
                $stmtUpdateProg = $this->pdo->prepare('UPDATE Hero_Progress SET chapter_id = ? WHERE hero_id = ?');
                $stmtUpdateProg->execute([$nextChapter, $hero->getId()]);
            } else {
                $stmtInsertProg = $this->pdo->prepare('INSERT INTO Hero_Progress (hero_id, chapter_id) VALUES (?, ?)');
                $stmtInsertProg->execute([$hero->getId(), $nextChapter]);
            }

            $this->addLog("Progression mise à jour : chapitre $nextChapter.");

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->addLog('Erreur lors de l\'attribution des récompenses : ' . $e->getMessage());
            return;
        }

        if ($sumPv > 0 || $sumMana > 0 || $sumStr > 0 || $sumInit > 0) {
            $_SESSION['combat']['hero_max_pv'] = $newBasePv;
            $_SESSION['combat']['hero_pv'] += $sumPv; 

            $_SESSION['combat']['hero_max_mana'] = $newBaseMana;
            $_SESSION['combat']['hero_mana'] += $sumMana;

            foreach ($levelUps as $lu) {
                $this->addLog("Niveau {$lu['level']} atteint ! (+{$lu['pv_bonus']} PV, +{$lu['mana_bonus']} Mana, +{$lu['strength_bonus']} STR, +{$lu['initiative_bonus']} INIT)");
            }
        }

        $this->addLog("Vous gagnez $xp XP !");
        if (count($rewards['items']) === 0) {
            $this->addLog("Aucun butin récupéré.");
        } else {
            foreach ($rewards['items'] as $it) {
                $this->addLog("Vous récupérez : {$it['name']} x{$it['quantity']}.");
            }
        }

        $_SESSION['combat']['rewards'] = $rewards;
    }
}