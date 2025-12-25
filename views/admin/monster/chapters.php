<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">
        Apparitions — <?= htmlspecialchars($monster['name']) ?> (#<?= (int)$monster['id'] ?>)
    </h1>
</div>

<div class="admin-card">
    <div style="display:flex; gap:10px; margin-bottom:14px;">
        <a class="admin-btn" href="index.php?url=admin/monster/index"> Retour monstres</a>
        <a class="admin-btn" href="index.php?url=admin/monster/edit&id=<?= (int)$monster['id'] ?>"> Modifier monstre</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="admin-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h3 style="margin-top:0;">Ajouter ce monstre à un chapitre</h3>

    <form class="admin-form" method="POST"
          action="index.php?url=admin/monster/chapters/add&id=<?= (int)$monster['id'] ?>">

        <label class="admin-label">Chapitre</label>
        <select name="chapter_id" class="admin-input" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($chapters as $c): ?>
                <option value="<?= (int)$c['id'] ?>">
                    #<?= (int)$c['id'] ?> — <?= htmlspecialchars($c['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="admin-btn" type="submit">Ajouter</button>
    </form>

    <hr style="margin:18px 0;">

    <h3>Chapitres liés</h3>

    <?php if (empty($linkedChapters)): ?>
        <p>Aucun chapitre lié pour ce monstre.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
            <tr>
                <th>Chapitre</th>
                <th>Titre</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($linkedChapters as $lc): ?>
                <tr>
                    <td>#<?= (int)$lc['chapter_id'] ?></td>
                    <td><?= htmlspecialchars($lc['title']) ?></td>
                    <td>
                        <form method="GET" action="index.php"
                              style="display:inline"
                              onsubmit="return confirm('Retirer ce monstre de ce chapitre ?');">
                            <input type="hidden" name="url" value="admin/monster/chapters/delete">
                            <input type="hidden" name="id" value="<?= (int)$monster['id'] ?>">
                            <input type="hidden" name="encounter_id" value="<?= (int)$lc['encounter_id'] ?>">
                            <button class="admin-btn" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
