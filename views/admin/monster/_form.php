<?php
// $old (array), $errors (array), $action (string), $submitLabel (string)
?>

<?php if (!empty($errors)): ?>
    <div class="admin-error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= htmlspecialchars($action) ?>" method="POST" enctype="multipart/form-data" class="admin-form">

    <label class="admin-label">Nom *</label>
    <input type="text" name="name" class="admin-input" maxlength="50"
           value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>

    <label class="admin-label">PV *</label>
    <input type="number" name="pv" class="admin-input" min="0"
           value="<?= htmlspecialchars($old['pv'] ?? '') ?>" required>

    <label class="admin-label">Mana</label>
    <input type="number" name="mana" class="admin-input" min="0"
           value="<?= htmlspecialchars($old['mana'] ?? '') ?>">

    <label class="admin-label">Initiative *</label>
    <input type="number" name="initiative" class="admin-input" min="0"
           value="<?= htmlspecialchars($old['initiative'] ?? '') ?>" required>

    <label class="admin-label">Force *</label>
    <input type="number" name="strength" class="admin-input" min="0"
           value="<?= htmlspecialchars($old['strength'] ?? '') ?>" required>

    <label class="admin-label">Attaque (texte)</label>
    <textarea name="attack" class="admin-textarea" rows="4"><?= htmlspecialchars($old['attack'] ?? '') ?></textarea>

    <label class="admin-label">XP *</label>
    <input type="number" name="xp" class="admin-input" min="0"
           value="<?= htmlspecialchars($old['xp'] ?? '') ?>" required>

    <label class="admin-label">Uploader une image</label>
    <input type="file" name="image_file" class="admin-input" accept="image/*">

    <?php if (!empty($old['image'])): ?>
        <div style="margin-top:10px">
            <small>Image actuelle :</small><br>
            <img src="<?= htmlspecialchars($old['image']) ?>" alt="" style="max-height:90px;border-radius:8px">
        </div>
    <?php endif; ?>

    <button type="submit" class="admin-btn"><?= htmlspecialchars($submitLabel) ?></button>
</form>
