<?php
// view_chapter.php

// Si la vue est incluse via le controller, $chapter sera fourni.
// Sinon, on tente d'obtenir le chapitre via $chapterController/$chapterId si présent,
// ou on crée un fallback d'aperçu pour éviter les erreurs "undefined variable".
if (!isset($chapter)) {
    if (isset($chapterController) && isset($chapterId) && method_exists($chapterController, 'getChapter')) {
        $chapter = $chapterController->getChapter($chapterId);
    }
}

// Vérifier s'il y a une rencontre pour ce chapitre
$hasEncounter = false;
$encounterMonsterId = null;
// Si un CombatController est disponible, essaye d'obtenir le monstre
if (isset($combatController) && method_exists($combatController, 'getMonster')) {
    // try by chapter object or id
    if (isset($chapter) && is_object($chapter)) {
        $m = $combatController->getMonster($chapter->getId());
    } elseif (isset($chapterId)) {
        $m = $combatController->getMonster($chapterId);
    } else {
        $m = null;
    }
    if ($m) {
        $hasEncounter = true;
        // try to get monster id if object exposes it
        if (method_exists($m, 'getId')) {
            $encounterMonsterId = $m->getId();
        }
    }
}

// Sinon, si une connexion DB globale $db existe, interroger la table Encounter
if (!$hasEncounter && isset($GLOBALS['db']) && $GLOBALS['db'] instanceof PDO) {
    try {
        $stmtEnc = $GLOBALS['db']->prepare('SELECT monster_id FROM Encounter WHERE chapter_id = ? LIMIT 1');
        $stmtEnc->execute([isset($chapter) && is_object($chapter) ? $chapter->getId() : ($chapterId ?? 0)]);
        $erow = $stmtEnc->fetch(PDO::FETCH_ASSOC);
        if ($erow && !empty($erow['monster_id'])) {
            $hasEncounter = true;
            $encounterMonsterId = (int) $erow['monster_id'];
        }
    } catch (Exception $e) {
        // ignore DB errors in view
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($chapter->getTitle()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        .chapter-bg {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        body,
        input,
        button {
            font-family: 'Press Start 2P', monospace;
        }

        .pixelated {
            image-rendering: pixelated;
        }
    </style>
</head>

<body class="min-h-screen chapter-bg pixelated" style="background-image: url('<?php echo $chapter->getImage(); ?>');">
    <form action="/R3_01-Dungeon-Explorer/pageUser" method="get">
    <button type="submit"
        class="px-4 py-2
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
    hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),_0_0_20px_rgba(255,200,80,0.9)];">
        Sauvegarder
    </button>
</form>

<form action="/R3_01-Dungeon-Explorer/equipment" method="get">
    <button type="submit"
        class="px-4 py-2
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
    hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),_0_0_20px_rgba(255,200,80,0.9)];">
        Inventaire
    </button>
</form>
<form action="/R3_01-Dungeon-Explorer/merchant" method="get">
    <button type="submit" class="px-4 py-2
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
    hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),_0_0_20px_rgba(255,200,80,0.9)];">Marchand</button>
  </form>


    <div class="min-h-screen bg-[rgba(0,0,0,0)]">
        <div class="max-w-2xl mx-auto my-10 px-4">
            <h1 class="text-yellow-900 text-[14px] mb-4"><?php echo htmlspecialchars($chapter->getTitle()); ?></h1>

            <div class="max-w-md mx-auto
                        bg-gradient-to-b from-[#f4e7c8] via-[#e6cf97] to-[#ddbf74]
                        border-8 border-[#2b2116] p-5
                        shadow-[6px_6px_0_0_#1b150f,-6px_-6px_0_0_#3a2f24]">

                <div class="text-[#2b2116] text-[10px] mb-3">Description</div>
                <div class="bg-[#fff8e6] border-4 border-[#2b2116] p-3 mb-4 text-[10px] text-[#6b5341]">
                    <?php echo nl2br(htmlspecialchars($chapter->getDescription())); ?>
                </div>

                <div class="h-2 my-4 bg-[repeating-linear-gradient(90deg,#2b2116_0_4px,#5b452f_4px_8px)]"></div>

                <div class="grid gap-3">
                    <?php if ($hasEncounter): ?>
                        <form method="post"
                            action="/R3_01-Dungeon-Explorer/combat/show?chapter=<?= (int) ($chapter->getId() ?? $chapterId ?? 0); ?>">
                            <input type="hidden" name="action" value="fight">
                            <button type="submit"
                                class="w-full text-center py-3 px-4 rounded-lg bg-gradient-to-b from-[#c53030] to-[#8b0000] border-[5px] border-[#2b2116] text-white text-[12px] hover:brightness-95 transition">
                                Combattre
                            </button>
                        </form>
                    <?php else: ?>
                        <?php foreach ($chapter->getChoices() as $choice): ?>
                            <form method="post" action="index.php?url=next">
                                <input type="hidden" name="action" value="choose">
                                <input type="hidden" name="next_chapter_id" value="<?php echo (int) $choice['chapter']; ?>">
                                <button type="submit"
                                    class="w-full text-center py-3 px-4 rounded-lg bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05] border-[5px] border-[#2b2116] text-white text-[12px] hover:brightness-95 transition">
                                    <?php echo htmlspecialchars($choice['text']); ?>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</body>

</html>