<?php
session_start();

require_once __DIR__ . '/../Database.php';

$errors = [];
$success = null;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($token, $_POST['csrf'])) {
        $errors[] = "Requête invalide (CSRF).";
    } else {
        $identifier = trim($_POST['identifier'] ?? ''); 
        $password = $_POST['password'] ?? '';

        if ($identifier === '') {
            $errors[] = "Nom d'utilisateur ou email requis.";
        }
        if ($password === '') {
            $errors[] = "Mot de passe requis.";
        }

        if (empty($errors)) {
            try {
                $stmt = $db->prepare("SELECT id, username, password_hash FROM users_aria WHERE username = :ident OR email = :ident LIMIT 1");
                $stmt->execute([':ident' => $identifier]);
                $user = $stmt->fetch();

                if (!$user || !password_verify($password, $user['password_hash'])) {
                    $errors[] = "Identifiants incorrects.";
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header('Location: gestion_compte.php');
                    exit;
                }
            } catch (PDOException $e) {
                $errors[] = "Erreur base de données : " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion — Dungeon Explorer</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('../sprites/background/connexion.png'); font-family: 'Press Start 2P', monospace; image-rendering: pixelated;">
    <div class="max-w-2xl mx-auto my-10 px-4">

        <?php if (!empty($errors)): ?>
            <div class="mb-4">
                <div class="border-4 border-[#2b2116] bg-[#fff8e0] p-3 rounded-lg shadow-lg">
                    <?php foreach ($errors as $e): ?>
                        <div class="text-red-700 text-sm font-medium"><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-4">
                <div class="border-4 border-[#2b2116] bg-[#e6f7e9] p-3 rounded-lg shadow-lg">
                    <div class="text-green-700 text-sm font-medium"><?= htmlspecialchars($success) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="max-w-md mx-auto bg-gradient-to-b from-[#f4e7c8] via-[#e6cf97] to-[#ddbf74] border-8 border-[#2b2116] p-5 rounded-lg shadow-lg">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-sm font-medium">Nom d'utilisateur ou Email</div>
                <div class="text-[#6b5341] text-xs">username ou email</div>
            </div>
            <label class="block mt-2">
                <input type="text" name="identifier" required
                       value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>"
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-sm focus:outline-none rounded-lg">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-sm font-medium">Mot de passe</div>
                <div class="text-[#6b5341] text-xs">votre mot de passe</div>
            </div>
            <label class="block mt-2">
                <input type="password" name="password" required
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-sm focus:outline-none rounded-lg">
            </label>

            <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="text-white text-sm px-4 py-2
                               bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05]
                               border-[5px] border-[#2b2116]
                               shadow-[0_6px_0_#1b150f] hover:brightness-95 rounded-lg">
                    Se connecter
                </button>
                <a href="inscriptions.php" class="text-yellow-900 ml-11 text-xs underline">Pas encore de compte ? S'inscrire</a>
            </div>
        </form>

    </div>
</body>
</html>