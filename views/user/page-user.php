<?php
// Récupération des données (inchangé)
$displayName = htmlspecialchars($user['username'] ?? 'Invité');
$xp = (int) ($hero['xp'] ?? 0);
$xp_max = (int) ($xp_max ?? 0);
$level = max(1, (int) ($hero['current_level'] ?? 1));
$heroName = $hero['name'] ?? 'Aucun héros';

// Calcul pourcentage XP pour la barre
$xpPercent = ($xp_max > 0) ? min(100, ($xp / $xp_max) * 100) : 0;

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

// --- STYLES REUTILISABLES (Pour garder le code propre et cohérent) ---

// Style des gros boutons dorés (Gauche)
$goldBtnClass = "block w-full mb-6 px-6 py-4 
                 bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)] 
                 border-[3px] border-[#8f6a1b] rounded-[15px] 
                 shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)] 
                 font-['Cinzel'] text-xl text-[#3b2200] font-bold text-center no-underline cursor-pointer 
                 transition duration-200 hover:scale-105 hover:brightness-110";

// Style des panneaux droits (Stats, Equipement)
$panelClass = "bg-black/85 border-[3px] border-[#8f6a1b] rounded-[20px] p-6 mb-6 text-[#f4d67a] shadow-[0_0_20px_rgba(0,0,0,0.8)]";

// Style des boutons de navigation (Précédent/Suivant) - Fond NOIR, Bordure OR
$navBtnClass = "px-6 py-2 bg-black border-[2px] border-[#8f6a1b] rounded-[10px] text-[#f4d67a] font-['Cinzel'] text-lg hover:bg-[#8f6a1b] hover:text-black transition duration-200";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Héros</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url("/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png") no-repeat center center fixed;
            background-size: cover;
            overflow: hidden; /* Empêche le scroll global pour forcer le layout app */
        }
        /* Scrollbar custom pour la colonne de droite */
        .custom-scroll::-webkit-scrollbar { width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.5); }
        .custom-scroll::-webkit-scrollbar-thumb { background: #fff; border-radius: 4px; }
    </style>
</head>

<body class="h-screen w-full flex justify-between items-stretch p-8 gap-8 font-sans">

    <div class="w-1/4 flex flex-col justify-start">
        
        <div onclick="location.href='/R3_01-Dungeon-Explorer/gestionCompte'" class="<?= $goldBtnClass ?>">
            Profil
        </div>

        <div class="h-16"></div>

        <button onclick="location.href='chapter/show'" class="<?= $goldBtnClass ?>">
            Continuer
        </button>

        <button onclick="location.href='/R3_01-Dungeon-Explorer/hero/create'" class="<?= $goldBtnClass ?>">
            Nouvelle partie
        </button>

        <form action="/R3_01-Dungeon-Explorer/hero/delete" method="post" onsubmit="return confirm('Supprimer ?');" class="w-full">
            <input type="hidden" name="hero_id" value="<?= (int) ($hero['id'] ?? 0) ?>">
            <button type="submit" class="<?= $goldBtnClass ?> leading-tight">
                Supprimer<br>Sauvegarde
            </button>
        </form>

        <a href="connexion" class="<?= $goldBtnClass ?> flex items-center justify-center">
            Se déconnecter
        </a>
    </div>

    <div class="w-1/2 flex flex-col items-center relative">
        
        <div class="w-full max-w-md text-center mt-2">
            <h2 class="text-white font-bold font-['Cinzel'] mb-1">Niveau <?= $level ?></h2>
            <div class="w-full h-6 bg-[#333] border-2 border-[#111] rounded-full overflow-hidden relative">
                <div class="h-full bg-[#2ecc71]" style="width: <?= $xpPercent ?>%;"></div>
                </div>
            <p class="text-white text-sm mt-1"><?= $xp ?> / <?= $xp_max ?> XP</p>
        </div>

        <div class="mt-6 bg-black px-8 py-2 rounded-lg border border-gray-700">
            <span class="text-white font-bold text-lg"><?= $displayName ?> — <?= htmlspecialchars($heroName) ?></span>
        </div>

        <div class="flex-grow"></div>

        <div class="flex items-center justify-center gap-8 mb-10">
            <?php if ($prevId !== null): ?>
                <a href="/R3_01-Dungeon-Explorer/pageUser?hero=<?= (int) $prevId ?>" class="<?= $navBtnClass ?>">
                    ← Précédent
                </a>
            <?php else: ?>
                <div class="<?= $navBtnClass ?> opacity-50 cursor-not-allowed">← Précédent</div>
            <?php endif; ?>

            <div class="w-32 h-32 flex items-center justify-center">
                 <?php if (!empty($hero)): ?>
                    <img src="<?= htmlspecialchars($hero['image'] ?? '') ?>" alt="Héros" class="max-w-full max-h-full object-contain drop-shadow-[0_0_10px_black]">
                <?php else: ?>
                    <span class="text-white">?</span>
                <?php endif; ?>
            </div>

            <?php if ($nextId !== null): ?>
                <a href="/R3_01-Dungeon-Explorer/pageUser?hero=<?= (int) $nextId ?>" class="<?= $navBtnClass ?>">
                    Suivant →
                </a>
            <?php else: ?>
                <div class="<?= $navBtnClass ?> opacity-50 cursor-not-allowed">Suivant →</div>
            <?php endif; ?>
        </div>

    </div>

    <div class="w-1/4 h-full overflow-y-auto custom-scroll pr-2">
        
        <div class="<?= $panelClass ?>">
            <h3 class="text-center text-xl font-bold font-['Cinzel'] mb-4 border-b border-[#8f6a1b] pb-2 text-[#f4d67a]">Statistiques</h3>
            <div class="space-y-3 font-['Cinzel']">
                <p>Niveau : <span class="text-white"><?= $level ?></span></p>
                <p>XP : <span class="text-white"><?= $xp ?> / <?= $xp_max ?></span></p>
                <p>PV : <span class="text-white"><?= (int) ($hero['pv'] ?? 0) ?></span></p>
                <p>Mana : <span class="text-white"><?= (int) ($hero['mana'] ?? 0) ?></span></p>
                <p>Force : <span class="text-white"><?= (int) ($hero['strength'] ?? 0) ?></span></p>
                <p>Initiative : <span class="text-white"><?= (int) ($hero['initiative'] ?? 0) ?></span></p>
            </div>
        </div>

        <div class="<?= $panelClass ?>">
            <h3 class="text-center text-xl font-bold font-['Cinzel'] mb-4 border-b border-[#8f6a1b] pb-2 text-[#f4d67a]">Équipement</h3>
            <div class="space-y-4 font-['Cinzel'] text-sm">
                
                <div class="flex flex-col">
                    <span class="font-bold text-[#f4d67a]">Armure</span>
                    <span class="text-white truncate"><?= !empty($equipment['armor']) ? htmlspecialchars($equipment['armor']['name']) : 'Aucune' ?></span>
                </div>

                <div class="flex flex-col">
                    <span class="font-bold text-[#f4d67a]">Arme principale</span>
                    <span class="text-white truncate"><?= !empty($equipment['primary_weapon']) ? htmlspecialchars($equipment['primary_weapon']['name']) : 'Aucune' ?></span>
                </div>

                <div class="flex flex-col">
                    <span class="font-bold text-[#f4d67a]">Arme secondaire</span>
                    <span class="text-white truncate"><?= !empty($equipment['secondary_weapon']) ? htmlspecialchars($equipment['secondary_weapon']['name']) : 'Aucune' ?></span>
                </div>

                <div class="flex flex-col">
                    <span class="font-bold text-[#f4d67a]">Bouclier</span>
                    <span class="text-white truncate"><?= !empty($equipment['shield']) ? htmlspecialchars($equipment['shield']['name']) : 'Aucun' ?></span>
                </div>
            </div>
        </div>

        <div class="<?= $panelClass ?>">
            <h3 class="text-center text-xl font-bold font-['Cinzel'] mb-4 border-b border-[#8f6a1b] pb-2 text-[#f4d67a]">Inventaire</h3>
            <?php if (empty($inventory)): ?>
                <p class="text-center italic text-gray-500">Vide</p>
            <?php else: ?>
                <div class="space-y-2">
                    <?php foreach ($inventory as $inv): ?>
                        <div class="flex justify-between text-sm border-b border-gray-700 pb-1">
                            <span class="text-white"><?= htmlspecialchars($inv['name']) ?></span>
                            <span class="text-[#f4d67a]">x<?= (int) $inv['quantity'] ?></span>
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