<?php
global $db;
session_start();
// session_abort();
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
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($username === '' || strlen($username) < 3) {
            $errors[] = "Nom d'utilisateur requis (>= 3 caractères).";
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide.";
        }
        if ($password === '' || strlen($password) < 6) {
            $errors[] = "Mot de passe requis (>= 6 caractères).";
        }
        if ($password !== $password_confirm) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($errors)) {
            try {
                $stmt = $db->prepare("SELECT id FROM users_aria WHERE username = :username OR email = :email LIMIT 1");
                $stmt->execute([':username' => $username, ':email' => $email]);
                if ($stmt->fetch()) {
                    $errors[] = "Nom d'utilisateur ou email déjà utilisé.";
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $insert = $db->prepare("INSERT INTO users_aria (username, email, password_hash) VALUES (:username, :email, :password_hash)");
                    $insert->execute([':username' => $username, ':email' => $email, ':password_hash' => $password_hash]);
                    $success = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                    header('Location: connexion.php');
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
    <title>Inscription — Dungeon Explorer</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('/R3_01-Dungeon-Explorer/sprites/background/inscriptions.png'); font-family: 'Press Start 2P', monospace; image-rendering: pixelated;">
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

            <div class="flex items-center justify-between">
                <div class="text-[#2b2116] text-sm font-medium">Nom d'utilisateur</div>
                <div class="text-[#6b5341] text-xs">min 3 caractères</div>
            </div>
            <label class="block mt-2">
                <input type="text" name="username" required
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-sm focus:outline-none rounded-lg">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-sm font-medium">Email</div>
                <div class="text-[#6b5341] text-xs">email@exemple.com</div>
            </div>
            <label class="block mt-2">
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-sm focus:outline-none rounded-lg">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-sm font-medium">Mot de passe</div>
                <div class="text-[#6b5341] text-xs">min 6 caractères</div>
            </div>
            <label class="block mt-2">
                <input type="password" name="password" required
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-sm focus:outline-none rounded-lg">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-sm font-medium">Confirmer mot de passe</div>
                <div class="text-[#6b5341] text-xs">&nbsp;</div>
            </div>
            <label class="block mt-2">
                <input type="password" name="password_confirm" required
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-sm focus:outline-none rounded-lg">
            </label>

            <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="text-white text-sm px-4 py-2
                               bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05]
                               border-[5px] border-[#2b2116]
                               shadow-[0_6px_0_#1b150f] hover:brightness-95 rounded-lg">
                    S'inscrire
                </button>
                <a href="connexion" class="text-yellow-900 ml-11 text-xs underline">Déjà un compte ? Se connecter</a>
            </div>
        </form>

    </div>
</body>
</html>