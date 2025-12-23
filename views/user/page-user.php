<?php
// $hero vient de show()
// $user vient de show()

$displayName = htmlspecialchars($user['username'] ?? 'Invité');
$xp = (int) ($hero['xp'] ?? 0);
$xp_max = (int)($xp_max ?? 0);
$level  = max(1, (int)($hero['current_level'] ?? 1));
$heroName = $hero['name'] ?? 'Aucun héros';


$isAdmin = (isset($user['is_admin']) && $user['is_admin'] == 1);
$heroesList = $heroes ?? [];
$currentIndex = null;
if (!empty($heroesList) && $hero) {
    foreach ($heroesList as $k => $h) {
        if ((int) $h['id'] === (int) $hero['id']) {
            $currentIndex = $k;
            break;
        }
    }
}
$prevId = $nextId = null;
if ($currentIndex !== null) {
    $prev = ($currentIndex - 1) >= 0 ? $heroesList[$currentIndex - 1]['id'] : end($heroesList)['id'];
    $next = ($currentIndex + 1) < count($heroesList) ? $heroesList[$currentIndex + 1]['id'] : $heroesList[0]['id'];
    $prevId = $prev;
    $nextId = $next;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/R3_01-Dungeon-Explorer/views/user/style.css">
    <title>Page Héros</title>
</head>

<body>

    <!-- Sélecteur héros -->
    <?php if (!empty($heroesList)): ?>
        <div class="hero-selector">
            <?php if ($prevId !== null): ?><a class="">
                    <a class="arrow prev" href="/R3_01-Dungeon-Explorer/pageUser?hero=<?= (int) $prevId ?>">⬅ Précédent</a>
                <?php endif; ?>

                <?php if (!empty($hero)): ?>
                    <div>
                        <img src="<?= htmlspecialchars($hero['image'] ?? '') ?>" alt="Héros" class="guerrier-image">
                    </div>
                <?php else: ?>
                    <div>Aucun héros</div>
                <?php endif; ?>

                <?php if ($nextId !== null): ?>
                    <a class="arrow next" href="/R3_01-Dungeon-Explorer/pageUser?hero=<?= (int) $nextId ?>">Suivant ➡</a>
                <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="btn" id="profil" onclick="location.href='/R3_01-Dungeon-Explorer/gestionCompte'">Profil</div>

    <div class="nameplate">
        <p><?= $displayName ?> — <?= htmlspecialchars($heroName) ?></p>
    </div>

    <div class="buttons-container">
        <button class="btn" onclick="location.href='chapter/show'">
            Continuer
        </button>

        <button class="btn" onclick="location.href='/R3_01-Dungeon-Explorer/hero/create'">
            Nouvelle partie
        </button>


        <button class="btn">Supprimer Sauvegarde</button>
        <button class="btn"><a href="connexion">Se déconnecter</a></button>

        <?php if ($isAdmin): ?>
            <button class="btn" onclick="location.href='admin'">
                Accès admin
            </button>
        <?php endif; ?>
    </div>

    <div class="xp-container">
        <p id="level-text">Niveau <?= $level ?></p>
        <div class="xp-bar">
            <div id="xp-fill" class="xp-fill"></div>
        </div>
        <p id="xp-text">
            <?= $xp ?> / <?= $xp_max > 0 ? $xp_max : "MAX" ?> XP
</p>
    </div>

    <!-- Panneau central : stats / équipement / inventaire -->
    <div class="center-panel">
        <!-- STATS -->
        <div class="stats-card">
            <h3>Statistiques</h3>
            <p><strong>Niveau :</strong> <?= $level ?></p>
            <p><strong>XP :</strong> <?= $xp ?> / <?= $xp_max > 0 ? $xp_max : "MAX" ?></p>
            <p><strong>PV :</strong> <?= (int) ($hero['pv'] ?? 0) ?></p>
            <p><strong>Mana :</strong> <?= (int) ($hero['mana'] ?? 0) ?></p>
            <p><strong>Force :</strong> <?= (int) ($hero['strength'] ?? 0) ?></p>
            <p><strong>Initiative :</strong> <?= (int) ($hero['initiative'] ?? 0) ?></p>     
        </div>

        <!-- EQUIPEMENT -->
        <div class="equipment-card">
            <h3>Équipement</h3>

            <div class="equip-slot">
                <div><strong>Armure</strong></div>
                <?php if (!empty($equipment['armor'])): ?>
                    <?php $it = $equipment['armor']; ?>
                    <div><?= htmlspecialchars($it['name']) ?></div>
                <?php else: ?>
                    <div>Aucune</div>
                <?php endif; ?>
            </div>

            <div class="equip-slot">
                <div><strong>Arme principale</strong></div>
                <?php if (!empty($equipment['primary_weapon'])): ?>
                    <?php $it = $equipment['primary_weapon']; ?>
                    <div><?= htmlspecialchars($it['name']) ?></div>
                <?php else: ?>
                    <div>Aucune</div>
                <?php endif; ?>
            </div>

            <div class="equip-slot">
                <div><strong>Arme secondaire</strong></div>
                <?php if (!empty($equipment['secondary_weapon'])): ?>
                    <?php $it = $equipment['secondary_weapon']; ?>
                    <div><?= htmlspecialchars($it['name']) ?></div>
                <?php else: ?>
                    <div>Aucune</div>
                <?php endif; ?>
            </div>

            <div class="equip-slot">
                <div><strong>Bouclier</strong></div>
                <?php if (!empty($equipment['shield'])): ?>
                    <?php $it = $equipment['shield']; ?>
                    <div><?= htmlspecialchars($it['name']) ?></div>
                <?php else: ?>
                    <div>Aucun</div>
                <?php endif; ?>
            </div>

        </div>

        <!-- INVENTAIRE -->
        <div class="inventory-card">
            <h3>Inventaire</h3>
            <?php if (empty($inventory)): ?>
                <p>Vide</p>
            <?php else: ?>
                <div class="inventory-list">
                    <?php foreach ($inventory as $inv): ?>
                        <div class="inventory-item">
                                <div><?= htmlspecialchars($inv['name']) ?></div>
                            <div><?= (int) $inv['quantity'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <script>
        window.USER_DATA = {
            xp: <?= (int) $xp ?>,
            xp_max: <?= (int) $xp_max ?>,
            level: <?= (int) $level ?>
        };
    </script>


    <script src="/R3_01-Dungeon-Explorer/views/user/script.js"></script>
</body>

</html>