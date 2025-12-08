<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
<h1 class="admin-title">Modifier le chapitre</h1>
</div>
<div class="admin-card">

    <a href="index" class="admin-btn">Retour</a>

    <form method="POST" class="admin-form">

        <label class="admin-label">Titre</label>
        <input type="text" name="title" class="admin-input" value="<?= htmlspecialchars($chapter['title']); ?>"
            required>

        <label class="admin-label">Contenu</label>
        <textarea name="content" class="admin-textarea"
            required><?= htmlspecialchars($chapter['content']); ?></textarea>

        <label class="admin-label">Image (URL)</label>
        <input type="text" name="image" class="admin-input" value="<?= htmlspecialchars($chapter['image']); ?>">

        <button type="submit" class="admin-btn">Mettre à jour</button>

    </form>
</div>