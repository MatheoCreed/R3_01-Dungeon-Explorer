<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">Administration — Monstres</h1>
</div>

<div class="admin-card">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px;">
        <a href="../../admin" class="admin-btn">⬅ Retour admin</a>
        <a href="create" class="admin-btn">+ Créer un monstre</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="admin-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>PV</th>
                <th>Mana</th>
                <th>Init</th>
                <th>Force</th>
                <th>XP</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($monsters as $m): ?>
                <tr>
                    <td><?= (int) $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= (int) $m['pv'] ?></td>
                    <td><?= $m['mana'] === null ? '-' : (int) $m['mana'] ?></td>
                    <td><?= (int) $m['initiative'] ?></td>
                    <td><?= (int) $m['strength'] ?></td>
                    <td><?= (int) $m['xp'] ?></td>
                    <td>
                        <?php if (!empty($m['image'])): ?>
                            <img src="<?= htmlspecialchars($m['image']) ?>" alt="Monstre"
                                style="max-height:55px; border-radius:10px;">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="admin-btn" href="chapters&id=<?= (int) $m['id'] ?>">
                            Apparitions
                        </a>

                        <a class="admin-btn" href="edit&id=<?= (int) $m['id'] ?>">
                            Modifier
                        </a>

                        <form action="index.php" method="get" style="display:inline"
                            onsubmit="return confirm('Supprimer ce monstre ?');">
                            <input type="hidden" name="url" value="admin/monster/delete">
                            <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                            <button type="submit" class="admin-btn">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>