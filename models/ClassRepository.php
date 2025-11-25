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
}
