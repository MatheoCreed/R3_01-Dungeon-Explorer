<?php
// controllers/CombatController.php

require_once __DIR__ . '/../models/Hero.php';
require_once __DIR__ . '/../models/Monster.php';
require_once __DIR__ . '/../models/Chapter.php';
require_once __DIR__ . '/../models/Spell.php';

class CombatController
{
    private PDO $pdo;

    public function __construct()
    {
        global $db;
        if (!isset($db) || !($db instanceof PDO)) {
            die("Erreur : PDO (\$db) non disponible dans CombatController.");
        }
        $this->pdo = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===========================================================
        ROUTE: /combat/show?chapter=ID
    ============================================================ */
    public function show()
    {
        
        // accepte GET chapter ou POST chapter_id (sécurité)
        $chapterId = (int)($_GET['chapter'] ?? $_POST['chapter_id'] ?? 1);
        $heroId    = (int)($_SESSION['hero_id'] ?? 0);
        if (isset($_SESSION['combat']['hero_id']) && (int)$_SESSION['combat']['hero_id'] !== (int)$heroId) {
         unset($_SESSION['combat']);
        }   

        if ($heroId <= 0) {
            die("Erreur : aucun héros sélectionné.");
        }

        // 1) init combat si nécessaire
        if (
            !isset($_SESSION['combat']) ||
            (int)$_SESSION['combat']['chapter_id'] !== $chapterId ||
            (!empty($_SESSION['combat']['finished']) && $_SESSION['combat']['finished'] === true)
        ) {
            $this->initCombat($chapterId, $heroId);
        }

        // 2) actions POST pendant le combat
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'cast_spell' && isset($_POST['spell_id'])) {
                $this->handlePlayerTurn('cast_spell', (int)$_POST['spell_id']);
            } else {
                $this->handlePlayerTurn($action);
            }
        }

        // 3) préparer la vue
        $combatData = $_SESSION['combat'];

        $hero    = $this->getHeroById($heroId);
        $monster = $this->getMonster($chapterId);
        $chapter = $this->getChapter($chapterId);

        if (!$monster) {
            die("Aucun monstre pour ce chapitre !");
        }

        // Charger sorts du héros (affichage)
        $this->loadHeroSpells($heroId, $hero);

        // Appliquer PV/Mana du combat
        $hero->setPv((int)$combatData['hero_pv']);
        $hero->setMana((int)$combatData['hero_mana']);

        include __DIR__ . '/../views/combat_view.php';
    }

    /* ===========================================================
        Chapitre
    ============================================================ */
    public function getChapter(int $id): Chapter
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chapter WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return new Chapter($id, "Chapitre $id", "", "", []);
        }

        $title = $row['title'] ?? ("Chapitre " . $id);
        $desc  = $row['description'] ?? ($row['content'] ?? "");
        $img   = $row['image'] ?? "";

        return new Chapter((int)$row['id'], $title, $desc, $img, []);
    }

    /* ===========================================================
        Monstre via Encounter
    ============================================================ */
    public function getMonster(int $chapterId): ?Monster
    {
        $stmt = $this->pdo->prepare('SELECT monster_id FROM encounter WHERE chapter_id = ? LIMIT 1');
        $stmt->execute([$chapterId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $monsterId = (int)$row['monster_id'];

        $stmt = $this->pdo->prepare('SELECT * FROM monster WHERE id = ? LIMIT 1');
        $stmt->execute([$monsterId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        return new Monster(
            $data['name'],
            (int)$data['pv'],
            (int)$data['mana'],
            (int)$data['initiative'],
            (int)$data['strength'],
            (int)$data['attack'],
            (int)$data['xp'],
            [],
            $data['image']
        );
    }

    /* ===========================================================
        Init combat (session)
    ============================================================ */
    private function initCombat(int $chapterId, int $heroId): void
    {
        $hero = $this->getHeroById($heroId);
        $monster = $this->getMonster($chapterId);

        if (!$monster) {
            die("Aucun monstre pour ce chapitre !");
        }

        $_SESSION['combat'] = [
            'chapter_id' => $chapterId,

            // IMPORTANT : on garde class_id et level pour level-up sans dépendre de getters
            'hero_id' => $heroId,
            'hero_class_id' => (int)$this->getHeroClassId($heroId),
            'hero_level' => (int)$this->getHeroLevel($heroId),

            'hero_pv' => (int)$hero->getPv(),
            'hero_max_pv' => (int)$hero->getPv(),
            'hero_mana' => (int)$hero->getMana(),
            'hero_max_mana' => (int)$hero->getMana(),

            'monster_pv' => (int)$monster->getHealth(),
            'monster_max_pv' => (int)$monster->getHealth(),

            'logs' => ["Le combat commence entre " . $hero->getName() . " et " . $monster->getName() . " !"],
            'turn' => 1,
            'finished' => false,
            'win' => false,
            'rewards' => null
        ];
    }

    /* ===========================================================
        Tour joueur
    ============================================================ */
    private function handlePlayerTurn(string $action, ?int $spellId = null): void
    {
        if (!empty($_SESSION['combat']['finished'])) return;

        $heroId = (int)($_SESSION['combat']['hero_id'] ?? ($_SESSION['hero_id'] ?? 0));
        if ($heroId <= 0) return;

        $hero = $this->getHeroById($heroId);

        // sorts
        $this->loadHeroSpells($heroId, $hero);

        // Appliquer PV/Mana session
        $hero->setPv((int)$_SESSION['combat']['hero_pv']);
        $hero->setMana((int)$_SESSION['combat']['hero_mana']);

        $chapterId = (int)$_SESSION['combat']['chapter_id'];
        $monster = $this->getMonster($chapterId);

        if (!$monster) {
            $this->addLog("Erreur : monstre introuvable.");
            $_SESSION['combat']['finished'] = true;
            return;
        }

        // initiative
        $initHero = rand(1, 6) + (int)$hero->getInitiative();
        $initMonster = rand(1, 6) + (int)$monster->getInitiative();
        $this->addLog("Initiative : Héros ($initHero) vs Monstre ($initMonster).");

        $heroGoesFirst = ($initHero > $initMonster);
        if ($initHero === $initMonster) {
            $heroGoesFirst = $hero->isThief(); // tie-break
        }

        if ($heroGoesFirst) {
            $this->executeHeroAction($hero, $monster, $action, $spellId);
            if ((int)$_SESSION['combat']['monster_pv'] > 0) {
                $this->executeMonsterAction($monster, $hero);
            }
        } else {
            $this->executeMonsterAction($monster, $hero);
            if ((int)$_SESSION['combat']['hero_pv'] > 0) {
                $this->executeHeroAction($hero, $monster, $action, $spellId);
            }
        }

        // Sauvegarder état
        $_SESSION['combat']['hero_pv']   = (int)$hero->getPv();
        $_SESSION['combat']['hero_mana'] = (int)$hero->getMana();
        $_SESSION['combat']['turn']      = (int)$_SESSION['combat']['turn'] + 1;

        // Fin combat
        if ((int)$_SESSION['combat']['hero_pv'] <= 0) {
            $_SESSION['combat']['finished'] = true;
            $_SESSION['combat']['win'] = false;
            $this->addLog("Vous êtes mort...");

            $respawnChapter = 10;

            try {
                $this->upsertHeroProgress($hero->getId(), $respawnChapter);
            } catch (Exception $e) {
                $this->addLog('Erreur progression : ' . $e->getMessage());
            }

            unset($_SESSION['combat']);
            header('Location: /R3_01-Dungeon-Explorer/chapter/show?chapter=' . $respawnChapter);
            exit;

        } elseif ((int)$_SESSION['combat']['monster_pv'] <= 0) {
            $_SESSION['combat']['finished'] = true;
            $_SESSION['combat']['win'] = true;
            $this->addLog("Victoire ! Le monstre est vaincu.");

            $this->giveRewards($hero, $monster, $chapterId);
        }
    }

    /* ===========================================================
        Actions héros / monstre
    ============================================================ */
    private function executeHeroAction(Hero $hero, Monster $monster, string $action, ?int $spellId = null): void
    {
        $log = "";

        if ($action === 'physique') {
            $attaque = rand(1, 6) + (int)$hero->getStrength() + (int)$hero->getWeaponBonus();
            $defense = rand(1, 6) + (int)($monster->getStrength() / 2);
            $degats  = max(0, $attaque - $defense);

            $_SESSION['combat']['monster_pv'] -= $degats;
            $log = "Vous attaquez physiquement (Att: $attaque vs Def: $defense). Dégâts : $degats.";

        } elseif ($action === 'magie') {
            $coutSort = 3;
            if ($hero->getMana() >= $coutSort) {
                $attaqueMagique = rand(1, 6) + rand(1, 6) + $coutSort;
                $hero->useMana($coutSort);

                $defense = rand(1, 6) + (int)($monster->getStrength() / 2);
                $degats  = max(0, $attaqueMagique - $defense);

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
                $cost = (int)$spell->getManaCost();

                if ($hero->getMana() < $cost) {
                    $log = "Pas assez de mana pour lancer {$spell->getSpellName()} ({$cost} mana).";
                } else {
                    $hero->useMana($cost);

                    $attaqueMagique = rand(1, 6) + rand(1, 6) + (int)$spell->getDamage();
                    $defense = rand(1, 6) + (int)($monster->getStrength() / 2);
                    $degats  = max(0, $attaqueMagique - $defense);

                    $_SESSION['combat']['monster_pv'] -= $degats;
                    $log = "Vous lancez {$spell->getSpellName()} (AttMag: $attaqueMagique vs Def: $defense). Dégâts : $degats.";
                }
            }

        } elseif ($action === 'potion') {
            $soin = 10;
            $oldPv = (int)$hero->getPv();

            $hero->heal($soin);

            if ($hero->getPv() > (int)$_SESSION['combat']['hero_max_pv']) {
                $hero->setPv((int)$_SESSION['combat']['hero_max_pv']);
            }

            $gained = (int)$hero->getPv() - $oldPv;
            $log = "Vous buvez une potion et récupérez $gained PV.";
        }

        $this->addLog("Héros : " . $log);
    }

    private function executeMonsterAction(Monster $monster, Hero $hero): void
    {
        $attaque = rand(1, 6) + (int)$monster->getStrength();
        $defense = rand(1, 6) + (int)$hero->getArmorBonus();

        if ($hero->isThief()) {
            $defense += (int)($hero->getInitiative() / 2);
        } else {
            $defense += (int)($hero->getStrength() / 2);
        }

        $degats = max(0, $attaque - $defense);
        $hero->takeDamage($degats);

        $this->addLog("Monstre : Attaque (Att: $attaque vs Def: $defense). Vous subissez $degats dégâts.");
    }

    private function addLog(string $message): void
    {
        array_unshift($_SESSION['combat']['logs'], $message);
    }

    /* ===========================================================
        Héros (DB)
    ============================================================ */
    private function getHeroById(int $id): Hero
    {
        $stmt = $this->pdo->prepare('
            SELECT h.*, c.name as class_name 
            FROM hero h 
            LEFT JOIN class c ON h.class_id = c.id 
            WHERE h.id = ?
        ');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            die("Héros introuvable.");
        }

        $hero = new Hero(
            $data['name'],
            (int)$data['class_id'],
            $data['image'],
            $data['biography'],
            (int)$data['pv'],
            (int)$data['mana'],
            (int)$data['strength'],
            (int)$data['initiative'],
            $data['armor_item_id'],
            $data['primary_weapon_item_id'],
            null,
            null,
            (int)$data['xp'],
            (int)$data['current_level'],
            (int)$data['id']
        );

        $hero->setClassName($data['class_name']);
        return $hero;
    }

    private function getHeroClassId(int $heroId): int
    {
        $stmt = $this->pdo->prepare("SELECT class_id FROM hero WHERE id = ? LIMIT 1");
        $stmt->execute([$heroId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['class_id'] ?? 0);
    }

    private function getHeroLevel(int $heroId): int
    {
        $stmt = $this->pdo->prepare("SELECT current_level FROM hero WHERE id = ? LIMIT 1");
        $stmt->execute([$heroId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['current_level'] ?? 1);
    }

    private function loadHeroSpells(int $heroId, Hero $hero): void
    {
        $stmt = $this->pdo->prepare('
            SELECT s.* 
            FROM hero_spell hs 
            JOIN spell s ON hs.spell_id = s.id 
            WHERE hs.hero_id = ?
        ');
        $stmt->execute([$heroId]);
        $spells = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($spells as $s) {
            $spellObj = new Spell(
                (int)$s['id'],
                $s['spell_name'],
                (int)$s['mana_cost'],
                (int)$s['damage'],
                $s['description']
            );
            $hero->addToSpell($spellObj);
        }
    }

    /* ===========================================================
        Rewards + progression + LEVEL UP via table `level`
    ============================================================ */
    private function giveRewards(Hero $hero, Monster $monster, int $chapterId): void
    {
        $xpGained = (int)$monster->getExperienceValue();

        $rewards = ['xp' => $xpGained, 'items' => []];

        try {
            $this->pdo->beginTransaction();

            // 1) XP + Level up (DB-driven)
            $heroId = (int)$hero->getId();
            $classId = (int)($_SESSION['combat']['hero_class_id'] ?? $this->getHeroClassId($heroId));

            $levelResult = $this->applyXpAndLevelUp($heroId, $classId, $xpGained);

            // 2) Loot
            $stmt = $this->pdo->prepare('SELECT monster_id FROM encounter WHERE chapter_id = ? LIMIT 1');
            $stmt->execute([$chapterId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $monsterId = $row['monster_id'] ?? null;

            if ($monsterId) {
                $stmt = $this->pdo->prepare('
                    SELECT ml.item_id, ml.quantity, ml.drop_rate, i.name 
                    FROM monster_loot ml 
                    LEFT JOIN items i ON ml.item_id = i.id 
                    WHERE ml.monster_id = ?
                ');
                $stmt->execute([(int)$monsterId]);
                $loots = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($loots as $loot) {
                    $dropRate = (float)$loot['drop_rate'];
                    $roll = mt_rand(0, 10000) / 10000;

                    if ($roll <= $dropRate) {
                        $itemId = (int)$loot['item_id'];
                        $qty = (int)$loot['quantity'];

                        $stmtInv = $this->pdo->prepare('SELECT quantity FROM inventory WHERE hero_id = ? AND item_id = ?');
                        $stmtInv->execute([$heroId, $itemId]);
                        $existing = $stmtInv->fetch(PDO::FETCH_ASSOC);

                        if ($existing) {
                            $newQty = (int)$existing['quantity'] + $qty;
                            $stmtUpdate = $this->pdo->prepare('UPDATE inventory SET quantity = ? WHERE hero_id = ? AND item_id = ?');
                            $stmtUpdate->execute([$newQty, $heroId, $itemId]);
                        } else {
                            $stmtInsert = $this->pdo->prepare('INSERT INTO inventory (hero_id, item_id, quantity) VALUES (?, ?, ?)');
                            $stmtInsert->execute([$heroId, $itemId, $qty]);
                        }

                        $hero->addToInventory($itemId, $qty);
                        $rewards['items'][] = ['item_id' => $itemId, 'name' => $loot['name'], 'quantity' => $qty];
                    }
                }
            }

            // 3) Progression chapitre suivant
            $nextChapter = $chapterId + 1;
            $this->upsertHeroProgress($heroId, $nextChapter);

            $this->pdo->commit();

            // Logs
            $this->addLog("Vous gagnez $xpGained XP !");
            foreach ($levelResult['logs'] as $l) {
                $this->addLog($l);
            }
            $this->addLog("Progression mise à jour : chapitre $nextChapter.");

        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->addLog("Erreur récompenses : " . $e->getMessage());
            return;
        }

        // Update session stats pour l'écran combat
        // On garde la même PV/Mana courante, mais on met à jour les max
        $_SESSION['combat']['hero_max_pv'] = (int)$levelResult['pv'];
        $_SESSION['combat']['hero_max_mana'] = (int)$levelResult['mana'];

        // (option) clamp
        $_SESSION['combat']['hero_pv'] = min((int)$_SESSION['combat']['hero_pv'], (int)$levelResult['pv']);
        $_SESSION['combat']['hero_mana'] = min((int)$_SESSION['combat']['hero_mana'], (int)$levelResult['mana']);

        $_SESSION['combat']['rewards'] = $rewards;
    }

    private function upsertHeroProgress(int $heroId, int $chapterId): void
    {
        $stmtProg = $this->pdo->prepare('SELECT chapter_id FROM hero_progress WHERE hero_id = ? LIMIT 1');
        $stmtProg->execute([$heroId]);
        $exists = $stmtProg->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            $stmtUpdateProg = $this->pdo->prepare('UPDATE hero_progress SET chapter_id = ? WHERE hero_id = ?');
            $stmtUpdateProg->execute([$chapterId, $heroId]);
        } else {
            $stmtInsertProg = $this->pdo->prepare('INSERT INTO hero_progress (hero_id, chapter_id) VALUES (?, ?)');
            $stmtInsertProg->execute([$heroId, $chapterId]);
        }
    }

    /**
     * XP cumulatif:
     * - Hero.xp = total xp
     * - level.required_xp = seuil total requis pour atteindre ce niveau
     */
    private function applyXpAndLevelUp(int $heroId, int $classId, int $xpGained): array
    {
        // Lock le héros pendant la transaction
        $stmt = $this->pdo->prepare("
            SELECT xp, current_level, pv, mana, strength, initiative
            FROM hero
            WHERE id = ?
            FOR UPDATE
        ");
        $stmt->execute([$heroId]);
        $h = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$h) throw new Exception("Héros introuvable pour level up.");

        $xp = (int)$h['xp'] + $xpGained;
        $level = (int)$h['current_level'];

        $pv = (int)$h['pv'];
        $mana = (int)$h['mana'];
        $strength = (int)$h['strength'];
        $initiative = (int)$h['initiative'];

        $logs = [];

        while (true) {
            $nextLevel = $level + 1;

            $stmtLvl = $this->pdo->prepare("
                SELECT required_xp, pv_bonus, mana_bonus, strength_bonus, initiative_bonus
                FROM `level`
                WHERE class_id = ? AND level = ?
                LIMIT 1
            ");
            $stmtLvl->execute([$classId, $nextLevel]);
            $lvlRow = $stmtLvl->fetch(PDO::FETCH_ASSOC);

            if (!$lvlRow) break;

            $required = (int)$lvlRow['required_xp'];
            if ($xp < $required) break;

            $level = $nextLevel;

            $pv += (int)$lvlRow['pv_bonus'];
            $mana += (int)$lvlRow['mana_bonus'];
            $strength += (int)$lvlRow['strength_bonus'];
            $initiative += (int)$lvlRow['initiative_bonus'];

            $logs[] = "Niveau $level atteint ! (+{$lvlRow['pv_bonus']} PV, +{$lvlRow['mana_bonus']} Mana, +{$lvlRow['strength_bonus']} STR, +{$lvlRow['initiative_bonus']} INIT)";
        }

        $stmtUp = $this->pdo->prepare("
            UPDATE hero
            SET xp = ?, current_level = ?, pv = ?, mana = ?, strength = ?, initiative = ?
            WHERE id = ?
        ");
        $stmtUp->execute([$xp, $level, $pv, $mana, $strength, $initiative, $heroId]);

        // mettre à jour aussi session combat pour affichage sur l'instant
        $_SESSION['combat']['hero_level'] = $level;

        return [
            'xp' => $xp,
            'level' => $level,
            'pv' => $pv,
            'mana' => $mana,
            'strength' => $strength,
            'initiative' => $initiative,
            'logs' => $logs
        ];
    }
}
