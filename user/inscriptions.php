<?php
session_start();

function load_env($path) {
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        list($k, $v) = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
    return $env;
}

$env = load_env(dirname(__DIR__) . '/.env');

$dbHost = $env['DB_HOST'] ?? 'localhost';
$dbName = $env['DB_NAME'] ?? 'dungeon_explorer';
$dbUser = $env['DB_USER'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';

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
                $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
                $pdo = new PDO($dsn, $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                $stmt = $pdo->prepare("SELECT id FROM users_aria WHERE username = :username OR email = :email LIMIT 1");
                $stmt->execute([':username' => $username, ':email' => $email]);
                if ($stmt->fetch()) {
                    $errors[] = "Nom d'utilisateur ou email déjà utilisé.";
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $insert = $pdo->prepare("INSERT INTO users_aria (username, email, password_hash) VALUES (:username, :email, :password_hash)");
                    $insert->execute([':username' => $username, ':email' => $email, ':password_hash' => $password_hash]);
                    $success = "Inscription réussie. Vous pouvez maintenant vous connecter.";
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
<body class="min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('../sprites/background/inscriptions.png'); font-family: 'Press Start 2P', monospace; image-rendering: pixelated;">
    <div class="max-w-2xl mx-auto my-10 px-4">

        <?php if (!empty($errors)): ?>
            <div class="mb-4">
                <div class="border-4 border-[#2b2116] bg-[#fff8e0] p-3">
                    <?php foreach ($errors as $e): ?>
                        <div class="text-red-700 text-[10px]"><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-4">
                <div class="border-4 border-[#2b2116] bg-[#e6f7e9] p-3">
                    <div class="text-green-700 text-[10px]"><?= htmlspecialchars($success) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="max-w-md mx-auto
                    bg-gradient-to-b from-[#f4e7c8] via-[#e6cf97] to-[#ddbf74]
                    border-8 border-[#2b2116] p-5
                    shadow-[6px_6px_0_0_#1b150f,-6px_-6px_0_0_#3a2f24]">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">

            <div class="flex items-center justify-between">
                <div class="text-[#2b2116] text-[10px]">Nom d'utilisateur</div>
                <div class="text-[#6b5341] text-[9px]">min 3 caractères</div>
            </div>
            <label class="block mt-2">
                <input type="text" name="username" required
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-[12px] focus:outline-none">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-[10px]">Email</div>
                <div class="text-[#6b5341] text-[9px]">email@exemple.com</div>
            </div>
            <label class="block mt-2">
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-[12px] focus:outline-none">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-[10px]">Mot de passe</div>
                <div class="text-[#6b5341] text-[9px]">min 6 caractères</div>
            </div>
            <label class="block mt-2">
                <input type="password" name="password" required
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-[12px] focus:outline-none">
            </label>

            <div class="flex items-center justify-between mt-3">
                <div class="text-[#2b2116] text-[10px]">Confirmer mot de passe</div>
                <div class="text-[#6b5341] text-[9px]">&nbsp;</div>
            </div>
            <label class="block mt-2">
                <input type="password" name="password_confirm" required
                       class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] shadow-inner shadow-[inset_0_-6px_0_rgba(0,0,0,0.08)] p-2 tracking-wider text-[12px] focus:outline-none">
            </label>

            <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="text-white text-[12px] px-4 py-2
                               bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05]
                               border-[5px] border-[#2b2116]
                               shadow-[0_6px_0_#1b150f] hover:brightness-95">
                    S'inscrire
                </button>
                <a href="login.php" class="text-yellow-900 ml-11 text-[10px] underline">Déjà un compte ? Se connecter</a>
            </div>
        </form>

    </div>
</body>
</html>
