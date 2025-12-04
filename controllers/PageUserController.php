<?php
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../models/Item.php';


class PageUserController {

    private $pdo;
    private $itemModel;
    
    public function __construct() {
        global $db;
        $this->pdo = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->itemModel = new ItemModel($this->pdo);
    }

    public function index() {

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            die("Vous devez être connecté pour accéder à cette page.");
        }

        $userId = $_SESSION['user_id'];

        // ⬇ 1) Récupérer les infos de l'utilisateur
        $stmt = $this->pdo->prepare("
            SELECT id, username, is_admin
            FROM users_aria
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Utilisateur introuvable.");
        }

          // Charger TOUS les héros de l'utilisateur (order by id asc)
        $stmt = $this->pdo->prepare("SELECT * FROM Hero WHERE user_id = ? ORDER BY id ASC");
        $stmt->execute([$userId]);
        $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC); // peut être []
        // Déterminer le héros sélectionné : ?hero=ID sinon premier (ou null si aucun)
        $selectedHero = null;
        if (isset($_GET['hero']) && $_GET['hero'] !== '') {
            $candidateId = (int) $_GET['hero'];
            foreach ($heroes as $h) {
                if ((int)$h['id'] === $candidateId) {
                    $selectedHero = $h;
                    break;
                }
            }
        }
        if ($selectedHero === null && !empty($heroes)) {
            // si tu veux le dernier créé à la place du premier, utilise end($heroes)
            $selectedHero = end($heroes);
        }
        // Récupérer l'équipement (items équipés) et l'inventaire pour le héros sélectionné
        $equipment = [
            'armor' => null,
            'primary_weapon' => null,
            'secondary_weapon' => null,
            'shield' => null,
        ];
        $inventory = [];

        if (!empty($selectedHero)) {
            // équipements — noms des colonnes dans ta table Hero : armor_item_id, primary_weapon_item_id, ...
            $armorId = $selectedHero['armor_item_id'] ?? null;
            $primaryId = $selectedHero['primary_weapon_item_id'] ?? null;
            $secondaryId = $selectedHero['secondary_weapon_item_id'] ?? null;
            $shieldId = $selectedHero['shield_item_id'] ?? null;

            $ids = array_filter([$armorId, $primaryId, $secondaryId, $shieldId], function($v) { return !is_null($v) && $v !== ''; });
            $items = $this->itemModel->getByIds($ids);

            if ($armorId && isset($items[$armorId])) $equipment['armor'] = $items[$armorId];
            if ($primaryId && isset($items[$primaryId])) $equipment['primary_weapon'] = $items[$primaryId];
            if ($secondaryId && isset($items[$secondaryId])) $equipment['secondary_weapon'] = $items[$secondaryId];
            if ($shieldId && isset($items[$shieldId])) $equipment['shield'] = $items[$shieldId];

            // inventaire complet (table Inventory join Items)
            $inventory = $this->itemModel->getInventoryForHero((int)$selectedHero['id']); // tableau de rows item + quantity
        }

        // Passer tout à la vue
        // variables disponibles dans la vue : $user, $heroes, $selectedHero (ou $hero), $equipment, $inventory
        $hero = $selectedHero; // pour compatibilité avec ta vue existante

        // ⬇ 3) Charger la vue
        require __DIR__ . '/../views/user/page-user.php';
    }
}
        
        


