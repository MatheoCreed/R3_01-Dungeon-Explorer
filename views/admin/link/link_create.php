<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<a href="../../admin/link/create1" class="retour-btn">Retour</a>


<div class="admin-container">
<h1 class="admin-title">Créer un lien depuis le chapitre :
    <?= $_GET['from'] ?>
</h1>
</div>
<form method="POST">

    <input type="hidden" name="chapter_id" value="<?= $_GET['from']; ?>">

    <label class="admin-label">Choisir le chapitre cible :</label>
    <div class="chapter-grid">
        <?php foreach ($chapters as $c): ?>
            <?php if ($c['id'] != $_GET['from']): ?>
                <label class="chapter-card">
                    <input type="radio" name="next_id" value="<?= $c['id']; ?>" required>
                    <h3><?= htmlspecialchars($c['title']); ?></h3>
                    <p>ID : <?= $c['id']; ?></p>
                </label>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <br>

    <label class="admin-label">Texte du choix :</label><br>
    <textarea  class="admin-textarea" name="choice_text" required></textarea>

    <button type="submit" class="admin-btn">Créer le lien</button>
</form>

