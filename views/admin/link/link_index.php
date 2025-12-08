
<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<a href="../../admin" class="retour-btn">Retour</a>

<div class="admin-container">
    <h1 class="admin-title">Gestion des liens entre chapitres</h1>
</div>
<a href="create1" class="admin-btn">Ajouter un lien</a>

<div class="admin-table-wrapper">
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Depuis</th>
            <th>Vers</th>
            <th>Texte du choix</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($links as $link): ?>
            <tr>
                <td><?= $link['id'] ?></td>
                <td>Chapitre <?= $link['chapter_id'] ?></td>
                <td>Chapitre <?= $link['next_chapter_id'] ?></td>
                <td><?= htmlspecialchars($link['choice_text']) ?></td>

                <td>
                    <a class="admin-btn" href="edit?id=<?= $link['id'] ?>">Modifier</a>
                    <a class="admin-btn" 
                       href="delete?id=<?= $link['id'] ?>"
                       onclick="return confirm('Supprimer ce lien ?');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
