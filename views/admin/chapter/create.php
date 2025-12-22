<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">Ajouter un chapitre</h1>
</div>

<div class="admin-card">
    <a href="index" class="admin-btn">Retour</a>

    <!-- IMPORTANT : enctype="multipart/form-data" -->
    <form method="POST" enctype="multipart/form-data" class="admin-form">

        <label class="admin-label">Titre</label>
        <input type="text" name="title" class="admin-input" required>

        <label class="admin-label">Contenu</label>
        <textarea name="content" class="admin-textarea" required></textarea>



        <label class="admin-label">uploader une image</label>
        <input type="file" name="image_file" class="admin-input" accept="image/*">

        <button type="submit" class="admin-btn">Créer</button>
    </form>
</div>
