<?php
// models/Item.php
class ItemModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère un item par son id (ou null s'il n'existe pas).
     * Retourne un tableau associatif ['id'=>..., 'name'=>..., 'description'=>..., 'item_type'=>..., 'image'=>...] si tu as une colonne image.
     */
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Items WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        return $item ?: null;
    }

    /**
     * Récupère plusieurs items par un tableau d'ids.
     * Retourne un tableau id => itemArray
     */
    public function getByIds(array $ids): array {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare("SELECT * FROM Items WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) $out[$r['id']] = $r;
        return $out;
    }

    /**
     * Récupère l'inventaire d'un héros : join Inventory + Items
     * Retourne tableau d'éléments ['item'=>..., 'quantity'=>int]
     */
    public function getInventoryForHero(int $heroId): array {
        $stmt = $this->pdo->prepare("
            SELECT I.*, INV.quantity
            FROM Inventory INV
            JOIN Items I ON I.id = INV.item_id
            WHERE INV.hero_id = ?
        ");
        $stmt->execute([$heroId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // chaque row contient les colonnes item + 'quantity'
    }
}
