<?php
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../models/Item.php';


class PageUserController
{
    private $pdo;
    private $itemModel;

    public function __construct()
    {
        global $db;
        $this->pdo = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->itemModel = new ItemModel($this->pdo);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            die("Vous devez être connecté pour accéder à cette page.");
        }

        $userId = $_SESSION['user_id'];

        $stmt = $this->pdo->prepare("SELECT id, username, is_admin FROM users_aria WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user)
            die("Utilisateur introuvable.");

        $stmt = $this->pdo->prepare("SELECT * FROM Hero WHERE user_id = ? ORDER BY id ASC");
        $stmt->execute([$userId]);
        $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $selectedHero = null;

        if (isset($_GET['hero']) && $_GET['hero'] !== '') {
            $candidateId = (int) $_GET['hero'];
            foreach ($heroes as $h) {
                if ((int) $h['id'] === $candidateId) {
                    $selectedHero = $h;
                    break;
                }
            }
        }

        if ($selectedHero === null && !empty($heroes)) {
            $selectedHero = end($heroes);
        }

        if ($selectedHero) {
            $_SESSION['hero_id'] = (int) $selectedHero['id'];
        }

        $equipment = [
            'armor' => null,
            'primary_weapon' => null,
            'secondary_weapon' => null,
            'shield' => null,
        ];
        $inventory = [];

        if (!empty($selectedHero)) {
            $armorId = $selectedHero['armor_item_id'] ?? null;
            $primaryId = $selectedHero['primary_weapon_item_id'] ?? null;
            $secondaryId = $selectedHero['secondary_weapon_item_id'] ?? null;
            $shieldId = $selectedHero['shield_item_id'] ?? null;

            $ids = array_filter([$armorId, $primaryId, $secondaryId, $shieldId], fn($v) => !is_null($v) && $v !== '');
            $items = $this->itemModel->getByIds($ids);

            if ($armorId && isset($items[$armorId]))
                $equipment['armor'] = $items[$armorId];
            if ($primaryId && isset($items[$primaryId]))
                $equipment['primary_weapon'] = $items[$primaryId];
            if ($secondaryId && isset($items[$secondaryId]))
                $equipment['secondary_weapon'] = $items[$secondaryId];
            if ($shieldId && isset($items[$shieldId]))
                $equipment['shield'] = $items[$shieldId];

            $inventory = $this->itemModel->getInventoryForHero((int) $selectedHero['id']);
        }

        $hero = $selectedHero;

        $xp_max = 0;
        if (!empty($hero)) {
            // ✅ sécurité : niveau minimum 1
            if (!isset($hero['current_level']) || (int) $hero['current_level'] < 1) {
                $hero['current_level'] = 1;

                // (optionnel) corriger direct en BDD
                $fix = $this->pdo->prepare("UPDATE Hero SET current_level = 1 WHERE id = ?");
                $fix->execute([(int) $hero['id']]);
            }

            $xp_max = $this->getNextRequiredXp((int) $hero['class_id'], (int) $hero['current_level']);
        }


        require __DIR__ . '/../views/user/page-user.php';
    }

    private function getNextRequiredXp(int $classId, int $currentLevel): int
{
    // ✅ sécurité : niveau minimum 1
    $currentLevel = max(1, $currentLevel);
    $nextLevel = $currentLevel + 1;

    // XP requis pour le niveau suivant
    $stmt = $this->pdo->prepare("
        SELECT required_xp
        FROM `level`
        WHERE class_id = ? AND level = ?
        LIMIT 1
    ");
    $stmt->execute([$classId, $nextLevel]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) return (int)$row['required_xp'];

    // Si pas de niveau suivant : MAX (0)
    return 0;
}

}






