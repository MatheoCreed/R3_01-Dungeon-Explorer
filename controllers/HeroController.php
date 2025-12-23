<?php
require_once __DIR__ . '/../models/Hero.php';
require_once __DIR__ . '/../models/Class.php';
require_once __DIR__ . '/../Database.php';

class HeroController {

    private $pdo;

    public function __construct() {
        // Assure que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        global $db;
        $this->pdo = $db;
    }

    public function createPage() {
        // Charge les classes depuis la BDD (ou hardcodées si tu préfères)
        $stmt = $this->pdo->query("SELECT * FROM class");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $classes = [];
        foreach ($rows as $row) {
            $classes[] = new GameClass(
                $row['name'],
                $row['description'],
                $row['base_pv'],
                $row['base_mana'],
                $row['strength'],
                $row['initiative'],
                $row['max_items'],
                $row['id'],
                $row['image'] ?? ''
            );
        }

        require __DIR__ . '/../views/creationPers_view.php';
    }

    public function insert() {
        // Méthode et session
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Méthode non autorisée");
        }

        // Vérifier login utilisateur
       if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour créer un héros.");
}
$userId = $_SESSION['user_id'];


        // Champs requis
        if (!isset($_POST['name'], $_POST['class_id'])) {
            die("Formulaire incomplet");
        }

        $name = trim($_POST['name']);
        $classId = (int) $_POST['class_id'];

        // Validate
        if ($name === '') die("Nom invalide");

        $class = $this->findClassById($classId);
        if (!$class) die("Classe invalide");

        // Construire l'objet Hero **en respectant le constructeur existant**
        // Ton modèle Hero actuel attend : __construct($name, $classId = null, $image = null, $biography = '', $pv = 0, $mana = 0, $strength = 0, $initiative = 0, ..., $id = null)
        // Nous fournissons les bons paramètres dans le bon ordre :
        $hero = new Hero(
            $name,                 // $name
            $class->getId(),       // $classId
            $class->getImage(),    // $image (optionnel)
            '',                    // $biography
            $class->getBasePv(),   // $pv
            $class->getBaseMana(), // $mana
            $class->getStrength(), // $strength
            $class->getInitiative(), // $initiative
            null, null, null, null, // items ids
            [], // spellList
            0,  // xp
            1,  // currentLevel
        );

        // Sauvegarde en DB (on passe $userId pour remplir user_id)
        $id = $this->saveHero($hero, $userId);

        header("Location: /R3_01-Dungeon-Explorer/pageUser?hero=" . $id);
        exit;
    }

    private function findClassById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM class WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new GameClass(
            $row['name'],
            $row['description'],
            $row['base_pv'],
            $row['base_mana'],
            $row['strength'],
            $row['initiative'],
            $row['max_items'],
            $row['id'],
            $row['image'] ?? ''
        );
    }

    /**
     * Sauvegarde le héros en base.
     * On fournit user_id pour satisfaire la colonne NOT NULL.
     */
   private function saveHero(Hero $hero, int $userId) {
    $stmt = $this->pdo->prepare("
        INSERT INTO hero
            (name, pv, mana, strength, initiative, class_id, xp, current_level, user_id, image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

     $stmt2 = $this->pdo->prepare("
        INSERT INTO hero_progress
            (hero_id, chapter_id)
        VALUES (?, ?)
    ");


    $stmt->execute([
        $hero->getName(),
        $hero->getPv(),
        $hero->getMana(),
        $hero->getStrength(),
        $hero->getInitiative(),
        $hero->getClassId(),
        $hero->getXp(),
        $hero->getLevel(),
        $userId,
        $hero->getImage()         // ← image enregistrée
    ]);

    $stmt2->execute([
        $this->pdo->lastInsertId(),
        1 // Commencer au chapitre 1
    ]);

    return $this->pdo->lastInsertId();
}


   public function show() {

    // Vérifier la connexion
    if (!isset($_SESSION['user_id'])) {
        die("Vous devez être connecté.");
    }

    $userId = $_SESSION['user_id'];

    // 1) Récupérer le héros du joueur connecté
    $stmt = $this->pdo->prepare("SELECT * FROM hero WHERE user_id = ?");
    $stmt->execute([$userId]);
    $hero = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2) Récupérer les infos utilisateur
    $stmt2 = $this->pdo->prepare("
        SELECT id, username, is_admin
        FROM users_aria
        WHERE id = ?
    ");
    $stmt2->execute([$userId]);
    $user = $stmt2->fetch(PDO::FETCH_ASSOC);

    // 3) Afficher la page
    require __DIR__ . '/../views/user/page-user.php';
}

public function delete()
{
    // Autoriser uniquement POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die("Méthode non autorisée");
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        die("Vous devez être connecté.");
    }

    $userId = (int)$_SESSION['user_id'];
    $heroId = (int)($_POST['hero_id'] ?? 0);

    if ($heroId <= 0) {
        die("Héros invalide.");
    }

    // Vérifier que le héros appartient bien à l'utilisateur connecté
    $stmt = $this->pdo->prepare("SELECT id FROM hero WHERE id = ? AND user_id = ? LIMIT 1");
    $stmt->execute([$heroId, $userId]);
    $heroRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$heroRow) {
        die("Suppression interdite : héros introuvable ou ne vous appartient pas.");
    }

    try {
        $this->pdo->beginTransaction();

        // 1) Supprimer inventaire (sinon FK bloque la suppression du hero)
        $stmt = $this->pdo->prepare("DELETE FROM inventory WHERE hero_id = ?");
        $stmt->execute([$heroId]);

        // 2) Supprimer sorts du héros
        $stmt = $this->pdo->prepare("DELETE FROM hero_spell WHERE hero_id = ?");
        $stmt->execute([$heroId]);

        // 3) Supprimer progression
        $stmt = $this->pdo->prepare("DELETE FROM hero_progress WHERE hero_id = ?");
        $stmt->execute([$heroId]);

        // 4) Supprimer le héros
        $stmt = $this->pdo->prepare("DELETE FROM hero WHERE id = ? AND user_id = ?");
        $stmt->execute([$heroId, $userId]);

        $this->pdo->commit();
    } catch (Exception $e) {
        $this->pdo->rollBack();
        die("Erreur suppression : " . $e->getMessage());
    }

    // Si c'était le héros sélectionné, on le retire de la session
    if (isset($_SESSION['hero_id']) && (int)$_SESSION['hero_id'] === $heroId) {
        unset($_SESSION['hero_id']);
    }

    // Rediriger vers un autre héros si possible, sinon pageUser sans param
    $stmt = $this->pdo->prepare("SELECT id FROM hero WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$userId]);
    $other = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($other) {
        header("Location: /R3_01-Dungeon-Explorer/pageUser?hero=" . (int)$other['id']);
    } else {
        header("Location: /R3_01-Dungeon-Explorer/pageUser");
    }
    exit;
}
}