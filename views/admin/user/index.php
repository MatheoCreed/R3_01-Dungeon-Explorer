<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">
</head>

<body>

<a href="../../admin" class="retour-btn">Retour</a>

<div class="admin-container">
    <h1 class="admin-title">Gestion des Utilisateurs</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['is_admin'] ? "Oui" : "Non" ?></td>

                    <td>
                        <?php if (!$u['is_admin']): // sécurité pour éviter de supprimer un admin ?>
                            <a href="delete?id=<?= $u['id'] ?>"
                               class="delete-btn"
                               onclick="return confirm('Supprimer ce joueur ?');">
                               Supprimer
                            </a>
                        <?php else: ?>
                            <span style="opacity:0.5;">(Admin)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
