
<?php
// Start session and load DB to fetch current user directly (no helper)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Database.php';

$currentUser = null;
$isAdmin = false;

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $db->prepare("SELECT id, username, 
            COALESCE(current_level, 1) AS current_level, 
            COALESCE(xp, 0) AS xp,
            COALESCE(is_admin, 0) AS is_admin
            FROM users_aria WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        $currentUser = null;
    }
}

$isAdmin = !empty($currentUser['is_admin']);

// Fallback display values when user is not logged in
$displayName = $currentUser['username'] ?? 'Invité';
$level = (int)($currentUser['current_level'] ?? 1);
$xp = (int)($currentUser['xp'] ?? 0);
$xp_max = 1000; // optional: change if you have a dynamic xp max
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Page utilisateur</title>
    
</head>
<body>
    
    <img src="../../sprites/joueur/guerrierMale/doubleHache/guerrierMaleDoubleHache1-Photoroom.png" alt="guerrier" class="guerrier-image">
    <div class="nameplate">
        <p><?= htmlspecialchars($displayName) ?></p>
    </div>

    <div class="buttons-container">
        <button class="btn">Continuer</button>
        <button class="btn">Nouvelle partie</button>
        <button class="btn">Supprimer Sauvegarde</button>
        <?php if ($isAdmin): ?>
            <button class="btn" onclick="location.href='../admin/admin.php'">Accès admin</button>
        <?php endif; ?>
    </div>

    <div class="xp-container">
        <p id="level-text"></p>
        <div class="xp-bar">
            <div class="xp-fill" id="xp-fill"></div>
        </div>
        <p id="xp-text"></p>
        
    </div>

   

    <script>
        // Provide user data to the frontend script
        window.USER_DATA = {
            xp: <?= $xp ?>,
            xp_max: <?= $xp_max ?>,
            level: <?= $level ?>
        };
    </script>
    <script src="script.js"></script>

</body>
</html>
