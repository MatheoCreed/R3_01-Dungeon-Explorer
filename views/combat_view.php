<?php
// combat_view.php

// Si la vue est incluse via le controller, $monster sera fourni.
// Sinon, on tente d'obtenir le chapitre via $chapterController/$chapterId si présent,
// ou on crée un fallback d'aperçu pour éviter les erreurs "undefined variable".
if (!isset($monster)) {
    if (isset($combatController) && method_exists($combatController, 'getMonster')) {
        if (isset($chapter) && is_object($chapter)) {
            $monster = $combatController->getMonster($chapter);
        } elseif (isset($chapterId)) {
            // try with id if controller expects an id
            $monster = $combatController->getMonster($chapterId);
        }
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
        .chapter-bg { background-repeat: no-repeat; background-size: cover; background-position: center; }
        body, input, button { font-family: 'Press Start 2P', monospace; }
        .pixelated { image-rendering: pixelated; }
    </style>
</head>
<body class="min-h-screen chapter-bg pixelated" style="background-image: url('<?php echo $chapter->getImage(); ?>');">
    <div class="min-h-screen bg-[rgba(0,0,0,0)]">
        <div class="max-w-4xl mx-auto my-10 px-4">
            <h1 class="text-yellow-900 text-[14px] mb-4"><?php echo htmlspecialchars($chapter->getTitle() ?? 'Combat'); ?></h1>

            <div class="w-full bg-gradient-to-b from-[#f4e7c8] via-[#e6cf97] to-[#ddbf74] border-8 border-[#2b2116] p-4 shadow-[6px_6px_0_0_#1b150f,-6px_-6px_0_0_#3a2f24] rounded-md">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Monster image -->
                    <div class="w-full md:w-1/3 flex justify-center">
                        <div class="w-48 h-48 bg-white/30 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="<?php echo htmlspecialchars($monster->getImage()); ?>" alt="<?php echo htmlspecialchars($monster->getName()); ?>" class="max-w-full max-h-full">
                        </div>
                    </div>

                    <!-- Stats and loot -->
                    <div class="w-full md:w-2/3">
                        <h2 class="text-2xl font-bold text-[#2b2116] mb-2"><?php echo htmlspecialchars($monster->getName()); ?></h2>
                        <div class="grid grid-cols-2 gap-3 mb-4 text-[12px]">
                            <div class="bg-[#fff8e6] border-4 border-[#2b2116] p-2">PV: <strong><?php echo (int)$monster->getHealth(); ?></strong></div>
                            <div class="bg-[#fff8e6] border-4 border-[#2b2116] p-2">Mana: <strong><?php echo (int)$monster->getMana(); ?></strong></div>
                            <div class="bg-[#fff8e6] border-4 border-[#2b2116] p-2">Force: <strong><?php echo (int)$monster->getStrength(); ?></strong></div>
                            <div class="bg-[#fff8e6] border-4 border-[#2b2116] p-2">Initiative: <strong><?php echo (int)$monster->getInitiative(); ?></strong></div>
                        </div>

                        <div class="text-[#2b2116] text-[10px] mb-2">Loot possible</div>
                        <div class="bg-[#fff8e6] border-4 border-[#2b2116] p-3 text-[12px]">
                            <?php $treasures = is_array($monster->getTreasure()) ? $monster->getTreasure() : []; ?>
                            <?php if (empty($treasures)): ?>
                                <div class="text-[#6b5341]">Aucun butin prévu.</div>
                            <?php else: ?>
                                <ul>
                                    <?php foreach ($treasures as $name => $qty): ?>
                                        <li><?php echo htmlspecialchars($name); ?> &times; <?php echo (int)$qty; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
