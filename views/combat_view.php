<?php
// views/combat_view.php

$combatData = $_SESSION['combat'] ?? null;
if (!$combatData) {
    echo "Erreur : Données de combat manquantes.";
    exit;
}

$currentHeroPv = (int) $combatData['hero_pv'];
$maxHeroPv = max(1, (int) $combatData['hero_max_pv']); // ✅ évite division par 0

$currentHeroMana = (int) $combatData['hero_mana'];
$maxHeroMana = max(1, (int) $combatData['hero_max_mana']); // ✅ évite division par 0

$currentMonsterPv = max(0, (int) $combatData['monster_pv']);
$maxMonsterPv = max(1, (int) $combatData['monster_max_pv']); // ✅

$isFinished = !empty($combatData['finished']);
$logs = $combatData['logs'] ?? [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Combat - <?php echo htmlspecialchars($chapter->getTitle()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body,
        button {
            font-family: 'Press Start 2P', monospace;
        }

        .pixelated {
            image-rendering: pixelated;
        }

        /* Barre de vie simple CSS */
        .health-bar-bg {
            background-color: #5c4b43;
            height: 20px;
            width: 100%;
            border: 2px solid #2b2116;
        }

        .health-bar-fill {
            height: 100%;
            transition: width 0.3s;
        }

        .hp-high {
            background-color: #4ade80;
        }

        .hp-mid {
            background-color: #facc15;
        }

        .hp-low {
            background-color: #ef4444;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-900 text-white p-4"
    style="background-image: url('<?php echo htmlspecialchars($chapter->getImage()); ?>'); background-size: cover; background-position: center;">
        <form action="/R3_01-Dungeon-Explorer/pageUser" method="get">
        <button type="submit" class="
   fixed top-4 left-4 z-50

   px-4 py-2
   text-[14px]
   text-center
   rounded-[15px]
   border-[3px] border-[#8f6a1b]

   bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
   shadow-[inset_0_0_10px_rgba(255,225,150,0.8),_0_0_15px_rgba(0,0,0,0.4)]

   cursor-pointer
   transition-transform duration-200

   hover:scale-105
   hover:border-[#b78925]
   hover:bg-[linear-gradient(145deg,#ffeb99,#f7d87c,#d1aa3c)]
   hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),_0_0_20px_rgba(255,200,80,0.9)]
   ">
            Quitter le combat
        </button>
    </form>
    <div class="max-w-4xl mx-auto bg-black/80 border-4 border-[#8b4513] p-6 rounded-lg shadow-2xl">

        <h1
            class="text-center text-yellow-500 text-xl mb-6 uppercase tracking-widest border-b-2 border-yellow-500 pb-4">
            <?php echo htmlspecialchars($chapter->getTitle()); ?>
        </h1>

        <div class="flex flex-col md:flex-row justify-between gap-8 mb-8">

            <div class="w-full md:w-1/2 bg-[#2a2a2a] p-4 rounded border-2 border-blue-500 relative">
                <h2 class="text-blue-300 mb-2"><?php echo htmlspecialchars($hero->getName()); ?>
                    (<?php echo htmlspecialchars($hero->getClassName()); ?>)</h2>

                <div class="mb-1 text-xs">PV: <?php echo $currentHeroPv; ?> / <?php echo $maxHeroPv; ?></div>
                <div class="health-bar-bg mb-2">
                    <?php $pct = ($currentHeroPv / $maxHeroPv) * 100; ?>
                    <div class="health-bar-fill <?php echo $pct > 50 ? 'hp-high' : ($pct > 20 ? 'hp-mid' : 'hp-low'); ?>"
                        style="width: <?php echo $pct; ?>%;"></div>
                </div>

                <div class="mb-1 text-xs">Mana: <?php echo $currentHeroMana; ?> / <?php echo $maxHeroMana; ?></div>
                <div class="health-bar-bg border-blue-900 bg-gray-800">
                    <div class="health-bar-fill bg-blue-500"
                        style="width: <?php echo ($currentHeroMana / $maxHeroMana) * 100; ?>%;"></div>
                </div>


                <div class="mt-4 text-[10px] text-gray-400">
                    STR: <?php echo $hero->getStrength(); ?> | INIT: <?php echo $hero->getInitiative(); ?>
                </div>
            </div>

            <div class="hidden md:flex items-center justify-center">
                <span class="text-3xl text-red-600 font-bold animate-pulse">VS</span>
            </div>

            <div class="w-full md:w-1/2 bg-[#2a2a2a] p-4 rounded border-2 border-red-500">
                <h2 class="text-red-300 mb-2"><?php echo htmlspecialchars($monster->getName()); ?></h2>

                <div class="flex justify-center mb-4">
                    <img src="<?php echo htmlspecialchars($monster->getImage()); ?>"
                        class="h-32 object-contain pixelated drop-shadow-[0_0_10px_rgba(255,0,0,0.5)]">
                </div>

                <div class="mb-1 text-xs text-right">PV: <?php echo $currentMonsterPv; ?> / <?php echo $maxMonsterPv; ?>
                </div>
                <div class="health-bar-bg mb-2">
                    <?php $m_pct = ($currentMonsterPv / $maxMonsterPv) * 100; ?>
                    <div class="health-bar-fill <?php echo $m_pct > 50 ? 'hp-high' : ($m_pct > 20 ? 'hp-mid' : 'hp-low'); ?>"
                        style="width: <?php echo $m_pct; ?>%;"></div>
                </div>
            </div>
        </div>

        <div class="bg-[#1a1a1a] p-4 rounded border-t-4 border-[#8b4513]">

            <?php if ($isFinished): ?>
                <div class="text-center py-6">
                    <h3 class="text-2xl mb-4 <?php echo $combatData['win'] ? 'text-green-400' : 'text-red-500'; ?>">
                        <?php echo $combatData['win'] ? 'VICTOIRE !' : 'DÉFAITE...'; ?>
                    </h3>

                    <?php if ($combatData['win'] && !empty($combatData['rewards'])): ?>
                        <div class="mb-4 text-sm text-yellow-300">
                            <div>XP gagnée : <strong><?php echo (int) $combatData['rewards']['xp']; ?></strong></div>
                            <div class="mt-2">Butin :</div>
                            <?php if (count($combatData['rewards']['items']) === 0): ?>
                                <div class="text-gray-400">Aucun butin récupéré.</div>
                            <?php else: ?>
                                <ul class="list-disc list-inside text-left max-w-md mx-auto mt-2 text-xs">
                                    <?php foreach ($combatData['rewards']['items'] as $it): ?>
                                        <li><?php echo htmlspecialchars($it['name']); ?> x<?php echo (int) $it['quantity']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <a href="/R3_01-Dungeon-Explorer/chapter/show?chapter=<?= (int) ($chapter->getId() + 1) ?>"
                        class="bg-yellow-600 hover:bg-yellow-500 text-black px-6 py-3 rounded text-sm uppercase">
                        <?= $combatData['win'] ? "Continuer l'aventure" : "Recommencer"; ?>
                    </a>
                </div>
            <?php else: ?>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="submit" name="action" value="physique"
                        class="bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 text-white py-4 px-2 rounded flex flex-col items-center group">
                        <span class="text-xl mb-2 group-hover:scale-110 transition">⚔️</span>
                        <span>Attaque Physique</span>
                    </button>

                    <?php if ($hero->isMage()): ?>
                        <button type="button" id="toggle-spells"
                            class="bg-blue-900 hover:bg-blue-800 border-b-4 border-blue-950 text-white py-4 px-2 rounded flex flex-col items-center group">
                            <span class="text-xl mb-2 group-hover:scale-110 transition">✨</span>
                            <span>Magie</span>
                        </button>
                    <?php else: ?>
                        <button type="button" disabled
                            class="bg-gray-800 text-gray-600 cursor-not-allowed border-b-4 border-gray-900 py-4 px-2 rounded flex flex-col items-center">
                            <span class="text-xl mb-2">✨</span>
                            <span>Magie (Mage uniq.)</span>
                        </button>
                    <?php endif; ?>



                    <button type="submit" name="action" value="potion"
                        class="bg-green-900 hover:bg-green-800 border-b-4 border-green-950 text-white py-4 px-2 rounded flex flex-col items-center group">
                        <span class="text-xl mb-2 group-hover:scale-110 transition">🧪</span>
                        <span>Potion (+10 PV)</span>
                    </button>
                </form>

                <?php if ($hero->isMage()): ?>
                    <div id="spells-panel" class="hidden mt-4 p-4 bg-gray-800 rounded border border-blue-700 text-sm">
                        <div class="mb-2 text-yellow-300">Vos sorts :</div>
                        <?php $spells = $hero->getSpells(); ?>
                        <?php $hasSpells = false; ?>
                        <?php foreach ($spells as $s): ?>
                            <?php if ($s instanceof \Spell): ?>
                                <?php $hasSpells = true; ?>
                                <div class="mb-3 p-2 bg-gray-900 rounded border border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-bold"><?php echo htmlspecialchars($s->getSpellName()); ?></div>
                                            <div class="text-xs text-gray-400"><?php echo htmlspecialchars($s->getDescription()); ?>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-blue-300">Mana: <?php echo (int) $s->getManaCost(); ?></div>
                                            <div class="text-xs text-yellow-300">Dégâts: <?php echo (int) $s->getDamage(); ?></div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="cast_spell">
                                            <input type="hidden" name="spell_id" value="<?php echo (int) $s->getId(); ?>">
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs" <?php echo ($combatData['hero_mana'] < $s->getManaCost()) ? 'disabled' : ''; ?>>
                                                Lancer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasSpells): ?>
                            <div class="text-gray-400">Aucun sort appris.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var btn = document.getElementById('toggle-spells');
                        var panel = document.getElementById('spells-panel');
                        if (btn && panel) {
                            btn.addEventListener('click', function () {
                                panel.classList.toggle('hidden');
                                panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            });
                        }
                    });
                </script>
            <?php endif; ?>
        </div>

        <div class="mt-6 bg-black border border-gray-700 p-4 h-48 overflow-y-auto font-mono text-xs rounded">
            <div class="text-yellow-500 mb-2 sticky top-0 bg-black border-b border-gray-800">>> Journal de combat</div>
            <?php foreach ($logs as $log): ?>
                <div class="mb-1 border-b border-gray-900 pb-1">
                    <span class="text-green-500">></span> <?php echo htmlspecialchars($log); ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</body>

</html>