<?php

require_once "Class.php";
class ClassRepository {


	private PDO $db;

	public function __construct()
	{
		$this->db = new PDO(
			"mysql:host=localhost;dbname=dungeon_explorer;charset=utf8",
			"root",
			"",
		);
	}

	public function getAll(): array
	{
		$stmt = $this->db->query("SELECT * FROM Class");

		$classes = [];

		while ($row = $stmt->fetch()) {
			$classes[] = new GameClass(
				$row['name'],
				$row['description'],
				$row['base_pv'],
				$row['base_mana'],
				$row['strength'],
				$row['initiative'],
				$row['max_items'],
				$row['id'],
                $row['image']
			);
		}

		return $classes;
	}

	public function getById(int $id): ?GameClass
    {
        $stmt = $this->db->prepare("SELECT * FROM Class WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new GameClass(
            $row['name'],
            $row['description'],
            $row['base_pv'],
            $row['base_mana'],
            $row['strength'],
            $row['initiative'],
            $row['max_items'],
            $row['id'],
            $row['image']
        );
    }

    public function create(GameClass $class): bool
    {
        $stmt = $this->db->prepare("
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
        $stmt = $this->db->prepare("
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
        $stmt = $this->db->prepare("DELETE FROM Class WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

