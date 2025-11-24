<?php
// view_chapter.php

// Si la vue est incluse via le controller, $chapter sera fourni.
// Sinon, on tente d'obtenir le chapitre via $chapterController/$chapterId si présent,
// ou on crée un fallback d'aperçu pour éviter les erreurs "undefined variable".
if (!isset($chapter)) {
    if (isset($chapterController) && isset($chapterId) && method_exists($chapterController, 'getChapter')) {
        $chapter = $chapterController->getChapter($chapterId);
    } elseif (class_exists('Chapter')) {
        // Crée un chapitre d'exemple pour l'aperçu
        $chapter = new Chapter(
            0,
            'Aperçu — Chapitre',
            'Contenu de démonstration pour la vue de chapitre.',
            'sprites/background/foret1.png',
            [
                ['text' => 'Option A', 'chapter' => 1],
                ['text' => 'Option B', 'chapter' => 2]
            ]
        );
    } else {
        // Fallback minimal si la classe Chapter n'est pas disponible
        $chapter = new class {
            public function getTitle() { return 'Aperçu — Chapitre'; }
            public function getImage() { return ''; }
            public function getDescription() { return 'Contenu de démonstration pour la vue de chapitre.'; }
            public function getChoices() { return []; }
        };
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
                    <?php foreach ($chapter->getChoices() as $choice): ?>
                        <a href="index.php?chapter=<?php echo (int)$choice['chapter']; ?>"
                           class="block text-center py-3 px-4 rounded-lg bg-gradient-to-b from-[#8b3b0f] to-[#5b1f05] border-[5px] border-[#2b2116] text-white text-[12px] hover:brightness-95 transition">
                            <?php echo htmlspecialchars($choice['text']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
