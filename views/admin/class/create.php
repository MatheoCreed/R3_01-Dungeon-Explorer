<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">Créer une classe</h1>
</div>

<div class="admin-card">
    <a href="index" class="admin-btn">Retour</a>

    <form method="POST" action="store" enctype="multipart/form-data" class="admin-form">

        <label class="admin-label">Nom</label>
        <input type="text" name="name" class="admin-input" required>

        <label class="admin-label">Description</label>
        <textarea name="description" class="admin-textarea"></textarea>

        <label class="admin-label">PV</label>
        <input type="number" name="base_pv" class="admin-input" required>

        <label class="admin-label">Mana</label>
        <input type="number" name="base_mana" class="admin-input" required>

        <label class="admin-label">Force</label>
        <input type="number" name="strength" class="admin-input" required>

        <label class="admin-label">Initiative</label>
        <input type="number" name="initiative" class="admin-input" required>

        <label class="admin-label">Max Items</label>
        <input type="number" name="max_items" class="admin-input" required>

        <label class="admin-label">Uploader une image</label>
        <input type="file" name="image_file" class="admin-input" accept="image/*">

        <button type="submit" class="admin-btn">Créer</button>
    </form>
</div>
