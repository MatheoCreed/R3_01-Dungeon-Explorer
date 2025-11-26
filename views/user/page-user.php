
<?php $isAdmin = true; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Page utilisateur</title>
    
</head>
<body>
    
    <img src="../../sprites/joueur/guerrierMale/doubleHache/guerrierMaleDoubleHache1-Photoroom.png" alt="guerrier" class="guerrier-image">
    <div class="nameplate">
        <p>requette sql bdd</p>
    </div>

    <div class="buttons-container">
        <button class="btn">Continuer</button>
        <button class="btn">Nouvelle partie</button>
        <button class="btn">Supprimer Sauvegarde</button>
        <?php if ($isAdmin): ?>
    <button class="btn" onclick="location.href='../admin/admin.php'">Accès admin</button>
<?php endif; ?>
    </div>

    <div class="xp-container">
        <p id="level-text"></p>
        <div class="xp-bar">
            <div class="xp-fill" id="xp-fill"></div>
        </div>
        <p id="xp-text"></p>
        
    </div>

   

    <script src="script.js"></script>

</body>
</html>
