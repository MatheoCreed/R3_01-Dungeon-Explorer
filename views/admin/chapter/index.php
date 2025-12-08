<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">
    
<div class="admin-container">

<h1 class="admin-title">Gestion des Chapitres</h1>
</div>
<a href="../../admin" class="admin-btn">Retour</a>
<a href="create" class="admin-btn">Ajouter un chapitre</a>

<div class="admin-table-wrapper">
<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Contenu</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($chapters as $c): ?>
        <tr>
            <td><?= $c['id']; ?></td>
            <td><?= htmlspecialchars($c['title']); ?></td>
            <td class="truncate-text"><?= htmlspecialchars($c['content']); ?></td>
            <td>
                <a href="edit?id=<?= $c['id']; ?>" class="admin-btn small">Modifier</a>
                <a href="delete?id=<?= $c['id']; ?>" class="admin-btn small"
                   onclick="return confirm('Supprimer ?');">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
