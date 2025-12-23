<?php

require_once "Class.php";

class ClassRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Class");
        $classes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $classes[] = new GameClass(
                $row['name'],
                $row['description'],
                (int)$row['base_pv'],
                (int)$row['base_mana'],
                (int)$row['strength'],
                (int)$row['initiative'],
                (int)$row['max_items'],
                (int)$row['id'],
                $row['image']
            );
        }

        return $classes;
    }

    public function getById(int $id): ?GameClass
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Class WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new GameClass(
            $row['name'],
            $row['description'],
            (int)$row['base_pv'],
            (int)$row['base_mana'],
            (int)$row['strength'],
            (int)$row['initiative'],
            (int)$row['max_items'],
            (int)$row['id'],
            $row['image']
        );
    }

    public function create(GameClass $class): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO Class (name, description, base_pv, base_mana, strength, initiative, max_items, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $class->getName(),
            $class->getDescription(),
            $class->getBasePv(),
            $class->getBaseMana(),
            $class->getStrength(),
            $class->getInitiative(),
            $class->getMaxItems(),
            $class->getImage()
        ]);
    }

    public function update(GameClass $class): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE Class
            SET name = ?, description = ?, base_pv = ?, base_mana = ?, strength = ?, initiative = ?, max_items = ?, image = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $class->getName(),
            $class->getDescription(),
            $class->getBasePv(),
            $class->getBaseMana(),
            $class->getStrength(),
            $class->getInitiative(),
            $class->getMaxItems(),
            $class->getImage(),
            $class->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Class WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
