<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">Gestion des Classes</h1>
</div>

<a href="/R3_01-Dungeon-Explorer/admin" class="admin-btn">Retour</a>
<a href="create" class="admin-btn">Ajouter une classe</a>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>PV</th>
            <th>Mana</th>
            <th>Force</th>
            <th>Initiative</th>
            <th>Max items</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>

        <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $c): ?>
                <tr>
                    <td><?= (int)$c->getId(); ?></td>
                    <td><?= htmlspecialchars($c->getName()); ?></td>
                    <td><?= (int)$c->getBasePv(); ?></td>
                    <td><?= (int)$c->getBaseMana(); ?></td>
                    <td><?= (int)$c->getStrength(); ?></td>
                    <td><?= (int)$c->getInitiative(); ?></td>
                    <td><?= (int)$c->getMaxItems(); ?></td>

                    <td>
                        <?php if (!empty($c->getImage())): ?>
                            <img src="<?= htmlspecialchars($c->getImage()); ?>"
                                 alt="classe"
                                 style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="edit?id=<?= (int)$c->getId(); ?>" class="admin-btn small">
                            Modifier
                        </a>

                        <a href="delete?id=<?= (int)$c->getId(); ?>"
                           class="admin-btn small"
                           onclick="return confirm('Supprimer cette classe ?');">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align:center;">
                    Aucune classe trouvée.
                </td>
            </tr>
        <?php endif; ?>

    </table>
</div>
