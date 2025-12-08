<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de personnage</title>

    <!-- CSS correct -->
    <link rel="stylesheet" href="/R3_01-Dungeon-Explorer/style/style.css">
    <style>body {background: url("../sprites/background/ChoixHeros.png") no-repeat center center fixed;
        background-size: cover;
                } 
    </style>
</head>

<body>



<div class="creation-wrapper">
    <a href="../page-user" class="retour-btn">Retour</a>
    <div class="class-list">
        <?php foreach ($classes as $class): ?>
            <button 
                type="button"
                class="class-btn"
                data-id="<?= $class->getId(); ?>"
                data-sprite="<?= $class->getImage(); ?>"
                data-hp="<?= $class->getBasePv(); ?>"
                data-atk="<?= $class->getBaseMana(); ?>"
                data-def="<?= $class->getStrength(); ?>"
                data-def="<?= $class->getInitiative(); ?>"
                data-def="<?= $class->getMaxItems(); ?>"

            >
                <?= htmlspecialchars($class->getName()); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="image">
        <img id="heroSprite" src="" alt="">
    </div>

    <div class="stats">
        <h2>Statistiques</h2>

        <div class="stat">
            <span>PV</span><div class="bar"><div id="bar-pv"></div></div>
        </div>

        <div class="stat">
            <span>Mana</span><div class="bar"><div id="bar-mana"></div></div>
        </div>

        <div class="stat">
            <span>strength</span><div class="bar"><div id="bar-strength"></div></div>
        </div>

    </div>
</div>

<form action="/R3_01-Dungeon-Explorer/hero/insert" method="POST">
    <div class="name-container">
        <input type="text" name="name" id="heroName" placeholder="Nom du héros" required>
    </div>

    <input type="hidden" name="class_id" id="selectedClass">

    <div class="create-container">
        <button type="submit" class="create-btn">Créer mon personnage</button>
    </div>
</form>

<!-- Script externe -->
<script src="/R3_01-Dungeon-Explorer/script/script.js"></script>

</body>
</html>
