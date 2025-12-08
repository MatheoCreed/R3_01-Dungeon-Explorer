<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<a href="index" class="retour-btn">Retour</a>

<div class="admin-container">
    <h1 class="admin-title">Modifier le lien #<?= $link['id'] ?></h1>
</div>

<form method="POST">

    <input type="hidden" name="id" value="<?= $link['id'] ?>">

    <label class="admin-label">Chapitre de départ :</label>
    <input type="text" class="admin-input" value="<?= $link['chapter_id'] ?>" disabled>

    <label class="admin-label">Choisir le chapitre cible :</label>
    <div class="chapter-grid">
        <?php foreach ($chapters as $c): ?>
            <label class="chapter-card">
                <input type="radio" name="next_id"
                       value="<?= $c['id'] ?>"
                       <?= $c['id'] == $link['next_chapter_id'] ? 'checked' : '' ?>>
                <h3><?= htmlspecialchars($c['title']) ?></h3>
                <p>ID : <?= $c['id'] ?></p>
            </label>
        <?php endforeach; ?>
    </div>

    <label class="admin-label">Texte du choix :</label>
    <textarea class="admin-textarea" name="choice_text" required>
<?= htmlspecialchars($link['choice_text']) ?>
    </textarea>

    <button type="submit" class="admin-btn">Modifier le lien</button>
</form>
