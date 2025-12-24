<?php

class AdminMonsterController
{
    private PDO $pdo;

    public function __construct()
    {
        global $db;

        if (!isset($db) || !($db instanceof PDO)) {
            die("Erreur : la connexion PDO (\$db) n'est pas accessible dans AdminMonsterController.");
        }

        $this->pdo = $db;

        // (optionnel) sécurité admin
        // if (!isset($_SESSION['user']) || empty($_SESSION['user']['is_admin'])) {
        //     header('Location: index.php?url=connexion');
        //     exit;
        // }
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT * FROM monster ORDER BY id DESC");
        $monsters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/admin/monster/index.php';
    }

    public function create()
    {
        $errors = [];
        $old = [
            'name' => '',
            'pv' => '',
            'mana' => '',
            'initiative' => '',
            'strength' => '',
            'attack' => '',
            'xp' => '',
            'image' => ''
        ];

        require __DIR__ . '/../views/admin/monster/create.php';
    }

    public function store()
    {
        $errors = [];
        $old = $this->collectForm();

        $this->validate($old, $errors);

        $imagePath = $this->handleUpload($errors);
        if ($imagePath !== null) {
            $old['image'] = $imagePath;
        }

        if (!empty($errors)) {
            require __DIR__ . '/../views/admin/monster/create.php';
            return;
        }

        $sql = "INSERT INTO monster (name, pv, mana, initiative, strength, attack, xp, image)
                VALUES (:name, :pv, :mana, :initiative, :strength, :attack, :xp, :image)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $old['name'],
            ':pv' => (int)$old['pv'],
            ':mana' => ($old['mana'] === '' ? null : (int)$old['mana']),
            ':initiative' => (int)$old['initiative'],
            ':strength' => (int)$old['strength'],
            ':attack' => ($old['attack'] === '' ? null : $old['attack']),
            ':xp' => (int)$old['xp'],
            ':image' => ($old['image'] === '' ? null : $old['image']),
        ]);

        header('Location: index.php?url=admin/monster/index');
        exit;
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $stmt = $this->pdo->prepare("SELECT * FROM monster WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $monster = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$monster) {
            require __DIR__ . '/../views/404.php';
            return;
        }

        $errors = [];
        $old = $monster;

        require __DIR__ . '/../views/admin/monster/edit.php';
    }

    public function update()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $stmt = $this->pdo->prepare("SELECT * FROM monster WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $monster = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$monster) {
            require __DIR__ . '/../views/404.php';
            return;
        }

        $errors = [];
        $old = $this->collectForm();
        $this->validate($old, $errors);

        $imagePath = $this->handleUpload($errors);
        if ($imagePath !== null) {
            $old['image'] = $imagePath;
        } else {
            $old['image'] = $monster['image'] ?? '';
        }

        if (!empty($errors)) {
            require __DIR__ . '/../views/admin/monster/edit.php';
            return;
        }

        $sql = "UPDATE monster
                SET name = :name,
                    pv = :pv,
                    mana = :mana,
                    initiative = :initiative,
                    strength = :strength,
                    attack = :attack,
                    xp = :xp,
                    image = :image
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':name' => $old['name'],
            ':pv' => (int)$old['pv'],
            ':mana' => ($old['mana'] === '' ? null : (int)$old['mana']),
            ':initiative' => (int)$old['initiative'],
            ':strength' => (int)$old['strength'],
            ':attack' => ($old['attack'] === '' ? null : $old['attack']),
            ':xp' => (int)$old['xp'],
            ':image' => ($old['image'] === '' ? null : $old['image']),
        ]);

        header('Location: index.php?url=admin/monster/index');
        exit;
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        try {
            $stmt = $this->pdo->prepare("DELETE FROM monster WHERE id = :id");
            $stmt->execute([':id' => $id]);

            header('Location: index.php?url=admin/monster/index');
            exit;
        } catch (PDOException $e) {
            $error = "Impossible de supprimer : ce monstre est probablement utilisé (rencontre/loot).";

            $stmt = $this->pdo->query("SELECT * FROM monster ORDER BY id DESC");
            $monsters = $stmt->fetchAll(PDO::FETCH_ASSOC);

            require __DIR__ . '/../views/admin/monster/index.php';
        }
    }

    private function collectForm(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'pv' => trim($_POST['pv'] ?? ''),
            'mana' => trim($_POST['mana'] ?? ''),
            'initiative' => trim($_POST['initiative'] ?? ''),
            'strength' => trim($_POST['strength'] ?? ''),
            'attack' => trim($_POST['attack'] ?? ''),
            'xp' => trim($_POST['xp'] ?? ''),
            'image' => trim($_POST['image'] ?? ''),
        ];
    }

    private function validate(array $data, array &$errors): void
    {
        if ($data['name'] === '' || mb_strlen($data['name']) > 50) {
            $errors[] = "Nom requis (max 50 caractères).";
        }

        foreach (['pv', 'initiative', 'strength', 'xp'] as $field) {
            if ($data[$field] === '' || !ctype_digit($data[$field]) || (int)$data[$field] < 0) {
                $errors[] = strtoupper($field) . " doit être un entier >= 0.";
            }
        }

        if ($data['mana'] !== '' && (!ctype_digit($data['mana']) || (int)$data['mana'] < 0)) {
            $errors[] = "MANA doit être vide ou un entier >= 0.";
        }
    }

    private function handleUpload(array &$errors): ?string
    {
        if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors de l'upload de l'image.";
            return null;
        }

        $tmp = $_FILES['image_file']['tmp_name'];
        $original = $_FILES['image_file']['name'];
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed, true)) {
            $errors[] = "Format d'image non supporté (jpg, png, webp).";
            return null;
        }

        $dir = __DIR__ . '/../public/uploads/monsters';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $filename = 'monster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $filename;

        if (!move_uploaded_file($tmp, $dest)) {
            $errors[] = "Impossible de déplacer l'image uploadée.";
            return null;
        }

        return 'public/uploads/monsters/' . $filename;
    }

    public function chapters()
{
    $monsterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Monstre
    $stmt = $this->pdo->prepare("SELECT * FROM monster WHERE id = :id");
    $stmt->execute([':id' => $monsterId]);
    $monster = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$monster) {
        require __DIR__ . '/../views/404.php';
        return;
    }

    // Tous les chapitres (adapte le nom de table/colonnes si besoin)
    $chapters = $this->pdo->query("SELECT id, title FROM chapter ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

    // Chapitres où ce monstre apparaît (via encounter)
    $sql = "SELECT e.id AS encounter_id, c.id AS chapter_id, c.title
            FROM encounter e
            JOIN chapter c ON c.id = e.chapter_id
            WHERE e.monster_id = :mid
            ORDER BY c.id DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':mid' => $monsterId]);
    $linkedChapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $error = $_GET['error'] ?? null;

    require __DIR__ . '/../views/admin/monster/chapters.php';
}

public function addChapter()
{
    $monsterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $chapterId = isset($_POST['chapter_id']) ? (int)$_POST['chapter_id'] : 0;

    if ($monsterId <= 0 || $chapterId <= 0) {
        header("Location: chapters&id={$monsterId}&error=Paramètres invalides");
        exit;
    }

    // Empêche doublon (un même monstre dans le même chapitre)
    $check = $this->pdo->prepare("SELECT COUNT(*) FROM encounter WHERE monster_id=:mid AND chapter_id=:cid");
    $check->execute([':mid' => $monsterId, ':cid' => $chapterId]);
    if ((int)$check->fetchColumn() > 0) {
        header("Location: chapters&id={$monsterId}&error=Déjà lié à ce chapitre");
        exit;
    }

    // INSERT liaison
    $stmt = $this->pdo->prepare("INSERT INTO encounter (chapter_id, monster_id) VALUES (:cid, :mid)");
    $stmt->execute([':cid' => $chapterId, ':mid' => $monsterId]);

    header("Location: chapters&id={$monsterId}");
    exit;
}

public function deleteChapter()
{
    $monsterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $encounterId = isset($_GET['encounter_id']) ? (int)$_GET['encounter_id'] : 0;

    if ($monsterId <= 0 || $encounterId <= 0) {
        header("Location: chapters&id={$monsterId}&error=Paramètres invalides");
        exit;
    }

    $stmt = $this->pdo->prepare("DELETE FROM encounter WHERE id = :eid AND monster_id = :mid");
    $stmt->execute([':eid' => $encounterId, ':mid' => $monsterId]);

    header("Location: chapters&id={$monsterId}");
    exit;
}

}
