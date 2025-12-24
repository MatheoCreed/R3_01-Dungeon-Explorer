<link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/admin/admin.css">

<div class="admin-container">
    <h1 class="admin-title">Créer un monstre</h1>
</div>

<div class="admin-card">
    <a href="index" class="admin-btn">Retour</a>

    <?php
    // variables attendues par _form.php
    $action = "store";
    $submitLabel = "Créer";
    require __DIR__ . '/_form.php';
    ?>
</div>
