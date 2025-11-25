<?php /** @var Class[] $classes */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dungeon-Explorer - Création Personnage</title>
    <link rel="stylesheet" href="/R3_01-Dungeon-Explorer/style/style.css">
    <style>
body {
    background: url("/R3_01-Dungeon-Explorer/sprites/background/ChoixHeros.png") no-repeat center center fixed;
    background-size: cover;
}
</style>
</head>
<body>

<div class="container">


    <div class="creation-wrapper">

        <!-- LISTE DES CLASSES -->
        <div class="class-list">
            <?php foreach($classes as $class): ?>
                <button class="class-btn"
                        data-name="<?= $class->getName() ?>"
                        data-desc="<?= $class->getDescription() ?>"
                        data-pv="<?= $class->getBasePv() ?>"
                        data-mana="<?= $class->getBaseMana() ?>"
                        data-str="<?= $class->getStrength() ?>"
                        data-ini="<?= $class->getInitiative() ?>"
                        data-max="<?= $class->getMaxItems() ?>"
                        data-img="<?= $class->getImage() ?>">

                    <?= $class->getName() ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- STATS -->
        <div class="stats">
            <h2 id="className">Sélectionne une classe</h2>
            <p id="classDesc"></p>

            <div class="stat"><span>PV :</span> <div class="bar"><div id="pvBar"></div></div> <span id="pvVal"></span></div>
            <div class="stat"><span>Mana :</span> <div class="bar"><div id="manaBar"></div></div> <span id="manaVal"></span></div>
            <div class="stat"><span>Force :</span> <div class="bar"><div id="strBar"></div></div> <span id="strVal"></span></div>
            <div class="stat"><span>Initiative :</span> <div class="bar"><div id="iniBar"></div></div> <span id="iniVal"></span></div>

            <div class="stat"><span>Objets :</span> <span id="maxVal"></span></div>

        </div>

        <!-- IMAGE -->
        <div class="image">
            <img id="classImg" src="/R3_01-Dungeon-Explorer/images/default.png">
        </div>

    </div>

</div>

<script src="/R3_01-Dungeon-Explorer/script/script.js"></script>

</body>
</html>
