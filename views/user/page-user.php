<?php
// Récupération des données
$displayName = htmlspecialchars($user['username'] ?? 'Invité');
$xp = (int) ($hero['xp'] ?? 0);
$xp_max = (int) ($xp_max ?? 0);
$level = max(1, (int) ($hero['current_level'] ?? 1));
$heroName = $hero['name'] ?? 'Aucun héros';
$heroImage = $hero['image'] ?? null;

// Calcul pourcentage XP
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

// --- STYLES TAILWIND ---

// Boutons dorés (Menu gauche)
$btnGold = "block w-[220px] py-3 px-4 mb-5 text-center
            bg-[linear-gradient(180deg,#e8c85c,#c99b26)] 
            border-[3px] border-[#6b4c1e] rounded-[15px] 
            shadow-[0_4px_4px_rgba(0,0,0,0.6)] 
            font-['Cinzel'] text-lg text-[#3b2200] font-bold 
            hover:brightness-110 transition-all active:scale-95 cursor-pointer";

// Panneaux droits (Fond noir, bordure dorée)
$panelRight = "bg-black/85 border-[2px] border-[#c99b26] rounded-[20px] p-5 mb-4 text-[#f4d67a] font-['Cinzel'] shadow-lg";

// Boutons Navigation (Noir et Or)
$navBtn = "px-4 py-2 bg-black border-[2px] border-[#c99b26] rounded-[10px] text-[#f4d67a] font-['Cinzel'] text-base hover:bg-[#c99b26] hover:text-black transition-colors cursor-pointer flex items-center gap-2";

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
            /* Empêche le scroll sur la page principale, sauf dans la zone de droite */
            overflow: hidden; 
        }
        /* Scrollbar custom pour la colonne de droite */
        .custom-scroll::-webkit-scrollbar { width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.5); }
        .custom-scroll::-webkit-scrollbar-thumb { background: #fff; border-radius: 4px; }
    </style>
</head>

<body class="flex h-screen w-full font-sans text-white p-6 justify-between">

    <div class="flex flex-col items-start pt-4 pl-8">
        <div onclick="location.href='/R3_01-Dungeon-Explorer/gestionCompte'" class="<?= $btnGold ?>">
            Profil
        </div>

        <div class="h-12"></div> <button onclick="location.href='chapter/show'" class="<?= $btnGold ?>">
            Continuer
        </button>

        <button onclick="location.href='/R3_01-Dungeon-Explorer/hero/create'" class="<?= $btnGold ?>">
            Nouvelle partie
        </button>

        <form action="/R3_01-Dungeon-Explorer/hero/delete" method="post" onsubmit="return confirm('Supprimer ?');">
            <input type="hidden" name="hero_id" value="<?= (int) ($hero['id'] ?? 0) ?>">
            <button type="submit" class="<?= $btnGold ?> leading-tight py-2">
                Supprimer<br>Sauvegarde
            </button>
        </form>

        <a href="connexion" class="<?= $btnGold ?> flex items-center justify-center">
            Se déconnecter
        </a>
    </div>

    <div class="flex flex-col items-center justify-between py-4 w-1/3">
        
        <div class="w-full flex flex-col items-center">
            <h2 class="font-bold font-['Cinzel'] text-white text-lg mb-1 drop-shadow-md">Niveau <?= $level ?></h2>
            
            <div class="w-[300px] h-5 bg-[#333] border border-black rounded-sm relative overflow-hidden mb-1">
                <div class="h-full bg-[#2ecc71]" style="width: <?= $xpPercent ?>%;"></div>
            </div>
            
            <p class="text-white text-xs font-bold drop-shadow-md mb-6"><?= $xp ?> / <?= $xp_max ?> XP</p>

            <div class="bg-black px-6 py-1 rounded-[5px] border border-gray-700 shadow-lg">
                <span class="text-white font-bold text-base"><?= $displayName ?> — <?= htmlspecialchars($heroName) ?></span>
            </div>
        </div>

        <div class="flex items-center justify-center gap-6 mb-16 relative">
            
            <?php if ($prevId !== null): ?>
                <a href="/R3_01-Dungeon-Explorer/pageUser?hero=<?= (int) $prevId ?>" class="<?= $navBtn ?>">
                    ← Précédent
                </a>
            <?php else: ?>
                <div class="<?= $navBtn ?> opacity-50 cursor-not-allowed">← Précédent</div>
            <?php endif; ?>

            <div class="relative w-[120px] h-[150px] flex items-end justify-center">
                 <?php if (!empty($heroImage)): ?>
                    <img src="<?= htmlspecialchars($heroImage) ?>" alt="Héros" class="max-w-[150%] max-h-[150%] object-contain drop-shadow-[0_5px_10px_rgba(0,0,0,0.8)] z-10">
                <?php else: ?>
                    <span class="text-white text-xs">No Image</span>
                <?php endif; ?>
            </div>

            <?php if ($nextId !== null): ?>
                <a href="/R3_01-Dungeon-Explorer/pageUser?hero=<?= (int) $nextId ?>" class="<?= $navBtn ?>">
                    Suivant →
                </a>
            <?php else: ?>
                <div class="<?= $navBtn ?> opacity-50 cursor-not-allowed">Suivant →</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="w-[300px] h-full overflow-y-auto custom-scroll pr-2 pt-4">
        
        <div class="<?= $panelRight ?>">
            <h3 class="text-center text-lg font-bold mb-4 border-b border-[#c99b26] pb-1">Statistiques</h3>
            <div class="space-y-3 text-sm">
                <p>Niveau : <span class="text-white"><?= $level ?></span></p>
                <p>XP : <span class="text-white"><?= $xp ?> / <?= $xp_max ?></span></p>
                <p>PV : <span class="text-white"><?= (int) ($hero['pv'] ?? 0) ?></span></p>
                <p>Mana : <span class="text-white"><?= (int) ($hero['mana'] ?? 0) ?></span></p>
                <p>Force : <span class="text-white"><?= (int) ($hero['strength'] ?? 0) ?></span></p>
                <p>Initiative : <span class="text-white"><?= (int) ($hero['initiative'] ?? 0) ?></span></p>
            </div>
        </div>

        <div class="<?= $panelRight ?>">
            <h3 class="text-center text-lg font-bold mb-4 border-b border-[#c99b26] pb-1">Équipement</h3>
            <div class="space-y-3 text-sm">
                <div class="flex flex-col">
                    <span class="font-bold text-[#c99b26]">Armure</span>
                    <span class="text-white truncate"><?= !empty($equipment['armor']) ? htmlspecialchars($equipment['armor']['name']) : 'Aucune' ?></span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-[#c99b26]">Arme principale</span>
                    <span class="text-white truncate"><?= !empty($equipment['primary_weapon']) ? htmlspecialchars($equipment['primary_weapon']['name']) : 'Aucune' ?></span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-[#c99b26]">Arme secondaire</span>
                    <span class="text-white truncate"><?= !empty($equipment['secondary_weapon']) ? htmlspecialchars($equipment['secondary_weapon']['name']) : 'Aucune' ?></span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-[#c99b26]">Bouclier</span>
                    <span class="text-white truncate"><?= !empty($equipment['shield']) ? htmlspecialchars($equipment['shield']['name']) : 'Aucun' ?></span>
                </div>
            </div>
        </div>

        <div class="<?= $panelRight ?>">
            <h3 class="text-center text-lg font-bold mb-4 border-b border-[#c99b26] pb-1">Inventaire</h3>
            <?php if (empty($inventory)): ?>
                <p class="text-center italic text-gray-500 text-sm">Vide</p>
            <?php else: ?>
                <div class="space-y-2 text-sm">
                    <?php foreach ($inventory as $inv): ?>
                        <div class="flex justify-between border-b border-gray-700 pb-1">
                            <span class="text-white truncate w-3/4"><?= htmlspecialchars($inv['name']) ?></span>
                            <span class="text-[#c99b26]">x<?= (int) $inv['quantity'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>