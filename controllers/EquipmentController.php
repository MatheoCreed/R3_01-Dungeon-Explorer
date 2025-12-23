<?php
// controllers/EquipementController.php
require_once __DIR__ . '/../Database.php';

class EquipmentController
{
    private PDO $pdo;

    public function __construct()
    {
        global $db;
        if (!isset($db) || !($db instanceof PDO)) {
            die("Erreur : PDO (\$db) non disponible dans EquipementController.");
        }
        $this->pdo = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $heroId = (int)($_SESSION['hero_id'] ?? 0);
        if ($heroId <= 0) die("Aucun héros sélectionné.");

        // HERO
        $stmt = $this->pdo->prepare("SELECT * FROM hero WHERE id = ? LIMIT 1");
        $stmt->execute([$heroId]);
        $hero = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$hero) die("Héros introuvable.");

        // INVENTORY + ITEMS
        $stmt = $this->pdo->prepare("
            SELECT i.*, inv.quantity
            FROM inventory inv
            JOIN items i ON i.id = inv.item_id
            WHERE inv.hero_id = ?
            ORDER BY i.item_type, i.name
        ");
        $stmt->execute([$heroId]);
        $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // EQUIPPED (charger les items équipés)
        $equipped = [
            'armor' => null,
            'primary' => null,
            'secondary' => null,
            'shield' => null
        ];

        $equippedIds = array_filter([
            $hero['armor_item_id'] ?? null,
            $hero['primary_weapon_item_id'] ?? null,
            $hero['secondary_weapon_item_id'] ?? null,
            $hero['shield_item_id'] ?? null,
        ], fn($v) => !empty($v));

        if (!empty($equippedIds)) {
            $in = implode(',', array_fill(0, count($equippedIds), '?'));
            $stmt = $this->pdo->prepare("SELECT * FROM items WHERE id IN ($in)");
            $stmt->execute(array_values($equippedIds));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $map = [];
            foreach ($rows as $r) $map[(int)$r['id']] = $r;

            if (!empty($hero['armor_item_id'])) {
                $equipped['armor'] = $map[(int)$hero['armor_item_id']] ?? null;
            }
            if (!empty($hero['primary_weapon_item_id'])) {
                $equipped['primary'] = $map[(int)$hero['primary_weapon_item_id']] ?? null;
            }
            if (!empty($hero['secondary_weapon_item_id'])) {
                $equipped['secondary'] = $map[(int)$hero['secondary_weapon_item_id']] ?? null;
            }
            if (!empty($hero['shield_item_id'])) {
                $equipped['shield'] = $map[(int)$hero['shield_item_id']] ?? null;
            }
        }

        require __DIR__ . '/../views/equipment/index.php';
    }

    public function equip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /R3_01-Dungeon-Explorer/equipment");
            exit;
        }

        $heroId = (int)($_SESSION['hero_id'] ?? 0);
        $itemId = (int)($_POST['item_id'] ?? 0);
        $slot   = trim($_POST['slot'] ?? ''); // pour arme: primary/secondary

        if ($heroId <= 0 || $itemId <= 0) die("Paramètres invalides.");

        // Vérifier dans inventaire
        $stmt = $this->pdo->prepare("SELECT quantity FROM inventory WHERE hero_id = ? AND item_id = ? LIMIT 1");
        $stmt->execute([$heroId, $itemId]);
        $inv = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$inv || (int)$inv['quantity'] <= 0) die("Objet non présent dans l'inventaire.");

        // Lire item_type
        $stmt = $this->pdo->prepare("SELECT item_type FROM items WHERE id = ? LIMIT 1");
        $stmt->execute([$itemId]);
        $type = (string)$stmt->fetchColumn();
        if ($type === '') die("Objet introuvable.");

        $col = $this->typeToHeroColumn($type, $slot);
        if (!$col) die("Objet non équipable.");

        $stmt = $this->pdo->prepare("UPDATE hero SET {$col} = ? WHERE id = ?");
        $stmt->execute([$itemId, $heroId]);

        header("Location: /R3_01-Dungeon-Explorer/equipment");
        exit;
    }

    public function unequip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /R3_01-Dungeon-Explorer/equipment");
            exit;
        }

        $heroId = (int)($_SESSION['hero_id'] ?? 0);
        $slot = $_POST['slot'] ?? '';

        if ($heroId <= 0) die("Aucun héros sélectionné.");

        $col = match ($slot) {
            'armor' => 'armor_item_id',
            'primary' => 'primary_weapon_item_id',
            'secondary' => 'secondary_weapon_item_id',
            'shield' => 'shield_item_id',
            default => null
        };
        if (!$col) die("Slot invalide.");

        $stmt = $this->pdo->prepare("UPDATE hero SET {$col} = NULL WHERE id = ?");
        $stmt->execute([$heroId]);

        header("Location: /R3_01-Dungeon-Explorer/equipment");
        exit;
    }

    private function typeToHeroColumn(string $type, string $slot = ''): ?string
    {
        $t = mb_strtolower(trim($type));

        if ($t === 'armure') return 'armor_item_id';

        if ($t === 'arme') {
            return ($slot === 'secondary') ? 'secondary_weapon_item_id' : 'primary_weapon_item_id';
        }

        if ($t === 'bouclier') return 'shield_item_id';

        // potions/clés/loot -> non équipable
        return null;
    }
}
