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

if (empty($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];

try {
    $stmt = $db->prepare("SELECT id, username, email, password_hash FROM users_aria WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $userId]);
    $currentUser = $stmt->fetch();
    if (!$currentUser) {
        session_destroy();
        header('Location: connexion');
        exit;
    }
} catch (PDOException $e) {
    $errors[] = "Erreur base de données : " . htmlspecialchars($e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($token, $_POST['csrf'])) {
        $errors[] = "Requête invalide (CSRF).";
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'update_profile') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if ($username === '') $errors[] = "Nom d'utilisateur requis.";
            if ($email === '') $errors[] = "Email requis.";
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";

            if (empty($errors)) {
                try {
                    $stmt = $db->prepare("SELECT id FROM users_aria WHERE (username = :username OR email = :email) AND id != :id LIMIT 1");
                    $stmt->execute([':username' => $username, ':email' => $email, ':id' => $userId]);
                    $exists = $stmt->fetch();
                    if ($exists) {
                        $errors[] = "Le nom d'utilisateur ou l'email est déjà utilisé par un autre compte.";
                    } else {
                        $stmt = $db->prepare("UPDATE users_aria SET username = :username, email = :email WHERE id = :id");
                        $stmt->execute([':username' => $username, ':email' => $email, ':id' => $userId]);
                        $_SESSION['username'] = $username;
                        $success = "Profil mis à jour.";
                        $currentUser['username'] = $username;
                        $currentUser['email'] = $email;
                    }
                } catch (PDOException $e) {
                    $errors[] = "Erreur base de données : " . htmlspecialchars($e->getMessage());
                }
            }

        } elseif ($action === 'change_password') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['new_password_confirm'] ?? '';

            $window = 15 * 60; 
            $maxAttempts = 5;
            if (!isset($_SESSION['pw_change_attempts'])) {
                $_SESSION['pw_change_attempts'] = [];
            }
            $_SESSION['pw_change_attempts'] = array_filter($_SESSION['pw_change_attempts'], function($ts) use ($window) {
                return ($ts >= time() - $window);
            });
            if (count($_SESSION['pw_change_attempts']) >= $maxAttempts) {
                $errors[] = "Trop de tentatives de changement de mot de passe. Réessayez plus tard.";
            }

            if ($current === '') $errors[] = "Mot de passe actuel requis.";
            if ($new === '') $errors[] = "Nouveau mot de passe requis.";
            if ($new !== $confirm) $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
            if ($new !== '' && strlen($new) < 8) $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";

            if (empty($errors)) {
                try {
                    if (!password_verify($current, $currentUser['password_hash'])) {
                        $errors[] = "Mot de passe actuel incorrect.";
                        $_SESSION['pw_change_attempts'][] = time();
                    } else {
                        $newHash = password_hash($new, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("UPDATE users_aria SET password_hash = :ph WHERE id = :id");
                        $stmt->execute([':ph' => $newHash, ':id' => $userId]);
                        $success = "Mot de passe mis à jour.";
                        $_SESSION['pw_change_attempts'] = [];
                        session_regenerate_id(true);
                    }
                } catch (PDOException $e) {
                    $errors[] = "Erreur base de données : " . htmlspecialchars($e->getMessage());
                }
            }
        } elseif ($action === 'delete_account') {
            $confirmPwd = $_POST['confirm_password'] ?? '';
            if ($confirmPwd === '') {
                $errors[] = "Veuillez entrer votre mot de passe pour confirmer la suppression.";
            } else {
                try {
                    if (!password_verify($confirmPwd, $currentUser['password_hash'])) {
                        $errors[] = "Mot de passe incorrect. Suppression annulée.";
                    } else {
                        $stmt = $db->prepare("DELETE FROM users_aria WHERE id = :id");
                        $stmt->execute([':id' => $userId]);
                        session_unset();
                        session_destroy();
                        header('Location: inscriptions?account_deleted=1');
                        exit;
                    }
                } catch (PDOException $e) {
                    $errors[] = "Erreur base de données : " . htmlspecialchars($e->getMessage());
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Gestion du compte — Dungeon Explorer</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>body{font-family: 'Press Start 2P', monospace; image-rendering: pixelated;}</style>
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('/R3_01-Dungeon-Explorer/sprites/background/compte.png');">
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

        <div class="max-w-md mx-auto bg-gradient-to-b from-[#f4e7c8] via-[#e6cf97] to-[#ddbf74] border-8 border-[#2b2116] p-5 rounded-lg shadow-lg">
            <h2 class="text-[#2b2116] text-sm font-medium">Gestion du compte</h2>
            <p class="text-[#6b5341] text-xs mb-3">Connecté en tant que <?= htmlspecialchars($currentUser['username']) ?></p>

            <form method="post" action="" class="mb-6">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="action" value="update_profile">

                <div class="text-[#2b2116] text-xs">Nom d'utilisateur</div>
                <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? $currentUser['username']) ?>" class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] p-2 rounded-lg text-sm">

                <div class="text-[#2b2116] text-xs mt-3">Email</div>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? $currentUser['email']) ?>" class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] p-2 rounded-lg text-sm">

                <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="text-white text-sm px-4 py-2 bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05] border-[5px] border-[#2b2116] shadow-[0_6px_0_#1b150f] hover:brightness-95 rounded-lg">Mettre à jour</button>
                    <a href="../index.php" class="text-yellow-900 ml-11 text-xs underline">Retour</a>
                </div>
            </form>

            <hr class="my-4 border-t-2 border-[#2b2116]">

            <form method="post" action="" class="mb-4">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="action" value="change_password">

                <div class="text-[#2b2116] text-xs">Mot de passe actuel</div>
                <input type="password" name="current_password" class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] p-2 rounded-lg text-sm">

                <div class="text-[#2b2116] text-xs mt-3">Nouveau mot de passe</div>
                <input type="password" name="new_password" class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] p-2 rounded-lg text-sm">

                <div class="text-[#2b2116] text-xs mt-3">Confirmer le nouveau mot de passe</div>
                <input type="password" name="new_password_confirm" class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] p-2 rounded-lg text-sm">

                <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="text-white text-sm px-4 py-2 bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05] border-[5px] border-[#2b2116] shadow-[0_6px_0_#1b150f] hover:brightness-95 rounded-lg">Changer le mot de passe</button>
                    <a href="inscriptions.php" class="text-yellow-900 ml-11 text-xs underline">S'inscrire</a>
                </div>
            </form>

            <hr class="my-4 border-t-2 border-[#2b2116]">

            <form method="post" action="" onsubmit="return confirm('Confirmer la suppression de votre compte ? Cette action est irréversible.');">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="action" value="delete_account">

                <div class="text-[#2b2116] text-xs">Confirmer avec votre mot de passe</div>
                <input type="password" name="confirm_password" class="w-full mt-2 bg-[#fff8e6] border-4 border-[#2b2116] p-2 rounded-lg text-sm">

                <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="text-white text-sm px-4 py-2 bg-gradient-to-b from-[#a11] to-[#700] border-[5px] border-[#2b2116] shadow-[0_6px_0_#1b150f] hover:brightness-95 rounded-lg">Supprimer mon compte</button>
                    <a href="index" class="text-yellow-900 ml-11 text-xs underline">Annuler</a>
                </div>
                <div></div>
            </form>

        </div>

    </div>
</body>
</html>