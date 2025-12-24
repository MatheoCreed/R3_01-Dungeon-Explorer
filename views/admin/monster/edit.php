<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">
        Modifier le monstre #<?= (int)$monster['id'] ?>
    </h1>
</div>

<div class="admin-card">
    <a href="index" class="admin-btn">
        ⬅ Retour
    </a>

    <?php
    $action = "update&id=" . (int)$monster['id'];
    $submitLabel = "Enregistrer";
    require __DIR__ . '/_form.php';
    ?>
</div>
