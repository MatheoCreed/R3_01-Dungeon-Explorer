<?php
// controllers/MerchantController.php
require_once __DIR__ . '/../Database.php';

class MerchantController
{
    private PDO $pdo;

    public function __construct()
    {
        global $db;
        if (!isset($db) || !($db instanceof PDO)) {
            die("Erreur : PDO (\$db) non disponible dans MarchandController.");
        }
        $this->pdo = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // GET /marchand/show?pnj=2
    public function show()
    {
        $heroId = (int)($_SESSION['hero_id'] ?? 0);
        if ($heroId <= 0) die("Aucun héros sélectionné.");

        $pnjId = (int)($_GET['pnj'] ?? 2);

        // Marchand
        $stmt = $this->pdo->prepare("
            SELECT m.pnj_id, m.shop_name, m.currency, p.name AS pnj_name, p.image AS pnj_image, p.description
            FROM marchand m
            JOIN pnj p ON p.id = m.pnj_id
            WHERE m.pnj_id = ?
            LIMIT 1
        ");
        $stmt->execute([$pnjId]);
        $marchand = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$marchand) die("Marchand introuvable (pnj_id=$pnjId).");

        // Pièces du héros (item_id = 1)
        $coins = $this->getHeroCoins($heroId);

        // Stock du marchand
        $stmt = $this->pdo->prepare("
            SELECT mi.id, mi.item_id, mi.price, mi.quantity,
                   i.name, i.description, i.item_type, i.value, i.bonus
            FROM marchand_inventory mi
            JOIN items i ON i.id = mi.item_id
            WHERE mi.marchand_id = ?
            ORDER BY i.item_type, i.name
        ");
        $stmt->execute([$pnjId]);
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inventaire du héros (pour affichage)
        $stmt = $this->pdo->prepare("
            SELECT inv.item_id, inv.quantity, i.name, i.item_type, i.bonus, i.value
            FROM inventory inv
            JOIN items i ON i.id = inv.item_id
            WHERE inv.hero_id = ?
            ORDER BY i.item_type, i.name
        ");
        $stmt->execute([$heroId]);
        $heroInventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        require __DIR__ . '/../views/merchant/index.php';
    }

    // POST /marchand/buy
    public function buy()
    {
        $heroId = (int)($_SESSION['hero_id'] ?? 0);
        if ($heroId <= 0) die("Aucun héros sélectionné.");

        $pnjId = (int)($_POST['pnj_id'] ?? 0);
        $itemId = (int)($_POST['item_id'] ?? 0);
        $qty    = max(1, (int)($_POST['qty'] ?? 1));

        if ($pnjId <= 0 || $itemId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => "Requête invalide."];
            header("Location: /R3_01-Dungeon-Explorer/merchant");
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // Lock stock marchand
            $stmt = $this->pdo->prepare("
                SELECT mi.quantity, mi.price
                FROM marchand_inventory mi
                WHERE mi.marchand_id = ? AND mi.item_id = ?
                FOR UPDATE
            ");
            $stmt->execute([$pnjId, $itemId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Objet non vendu par ce marchand.");
            }

            $stockQty = (int)$row['quantity'];
            $price = (float)$row['price'];

            if ($stockQty < $qty) {
                throw new Exception("Stock insuffisant.");
            }

            // Lock pièces héros
            $coins = $this->getHeroCoinsForUpdate($heroId);
            $total = (int)round($price * $qty);

            if ($coins < $total) {
                throw new Exception("Pas assez de pièces (besoin: $total, vous avez: $coins).");
            }

            // Déduire pièces (item_id=1)
            $this->setHeroCoins($heroId, $coins - $total);

            // Ajouter item au héros
            $this->addToInventory($heroId, $itemId, $qty);

            $this->pdo->commit();

            $_SESSION['flash'] = ['type' => 'ok', 'msg' => "Achat réussi ! (-$total pièces)"];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $_SESSION['flash'] = ['type' => 'error', 'msg' => $e->getMessage()];
        }

        header("Location: /R3_01-Dungeon-Explorer/merchant");
        exit;
    }

    private function getHeroCoins(int $heroId): int
    {
        $stmt = $this->pdo->prepare("SELECT quantity FROM inventory WHERE hero_id = ? AND item_id = 1 LIMIT 1");
        $stmt->execute([$heroId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['quantity'] ?? 0);
    }

    private function getHeroCoinsForUpdate(int $heroId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT quantity
            FROM inventory
            WHERE hero_id = ? AND item_id = 1
            FOR UPDATE
        ");
        $stmt->execute([$heroId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['quantity'] ?? 0);
    }

    private function setHeroCoins(int $heroId, int $newQty): void
    {
        // upsert coins
        $stmt = $this->pdo->prepare("SELECT id FROM inventory WHERE hero_id = ? AND item_id = 1 LIMIT 1");
        $stmt->execute([$heroId]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            $stmt = $this->pdo->prepare("UPDATE inventory SET quantity = ? WHERE hero_id = ? AND item_id = 1");
            $stmt->execute([$newQty, $heroId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO inventory (hero_id, item_id, quantity) VALUES (?, 1, ?)");
            $stmt->execute([$heroId, $newQty]);
        }
    }

    private function addToInventory(int $heroId, int $itemId, int $qty): void
    {
        $stmt = $this->pdo->prepare("SELECT quantity FROM inventory WHERE hero_id = ? AND item_id = ? LIMIT 1");
        $stmt->execute([$heroId, $itemId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $stmt = $this->pdo->prepare("UPDATE inventory SET quantity = quantity + ? WHERE hero_id = ? AND item_id = ?");
            $stmt->execute([$qty, $heroId, $itemId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO inventory (hero_id, item_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$heroId, $itemId, $qty]);
        }
    }
}
