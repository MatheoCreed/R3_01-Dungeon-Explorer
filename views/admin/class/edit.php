<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">Modifier la classe : <?= htmlspecialchars($class->getName()) ?></h1>
</div>

<div class="admin-card">
    <a href="index" class="admin-btn">Retour</a>

    <form method="POST" action="update" enctype="multipart/form-data" class="admin-form">

        <input type="hidden" name="id" value="<?= (int)$class->getId() ?>">

        <label class="admin-label">Nom</label>
        <input type="text" name="name" class="admin-input" value="<?= htmlspecialchars($class->getName()) ?>" required>

        <label class="admin-label">Description</label>
        <textarea name="description" class="admin-textarea"><?= htmlspecialchars($class->getDescription()) ?></textarea>

        <label class="admin-label">PV</label>
        <input type="number" name="base_pv" class="admin-input" value="<?= (int)$class->getBasePv() ?>" required>

        <label class="admin-label">Mana</label>
        <input type="number" name="base_mana" class="admin-input" value="<?= (int)$class->getBaseMana() ?>" required>

        <label class="admin-label">Force</label>
        <input type="number" name="strength" class="admin-input" value="<?= (int)$class->getStrength() ?>" required>

        <label class="admin-label">Initiative</label>
        <input type="number" name="initiative" class="admin-input" value="<?= (int)$class->getInitiative() ?>" required>

        <label class="admin-label">Max Items</label>
        <input type="number" name="max_items" class="admin-input" value="<?= (int)$class->getMaxItems() ?>" required>

        <?php if (!empty($class->getImage())): ?>
            <label class="admin-label">Image actuelle</label>
            <img src="<?= htmlspecialchars($class->getImage()) ?>" alt="class image"
                 style="max-width:200px;border-radius:8px;margin-bottom:10px;">
        <?php endif; ?>

        <label class="admin-label">Nouvelle image (optionnel)</label>
        <input type="file" name="image_file" class="admin-input" accept="image/*">

        <button type="submit" class="admin-btn">Mettre à jour</button>
    </form>
</div>
