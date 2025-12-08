<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<a href="../../admin/link/index" class="retour-btn">Retour</a>

<div class="admin-container">
<h1 class="admin-title">Créer un lien entre chapitres</h1>

</div>
<label class="admin-label">Choisir le chapitre source :</label>
<div class="chapter-grid">
    <?php foreach ($chapters as $c): ?>
        <a class="chapter-card" href="create?from=<?= $c['id'] ?>">
            <h3><?= htmlspecialchars($c['title']); ?></h3>
            <p>ID : <?= $c['id']; ?></p>
        </a>
    <?php endforeach; ?>
</div>