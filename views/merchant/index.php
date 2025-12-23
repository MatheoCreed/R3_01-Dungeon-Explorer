<?php
// views/marchand_view.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($marchand['shop_name'] ?? 'Marchand') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <style>
    body, button, input { font-family: 'Press Start 2P', monospace; }
  </style>
</head>

<body class="min-h-screen bg-gray-900 text-white p-4">
  <div class="max-w-5xl mx-auto bg-black/80 border-4 border-[#8b4513] p-6 rounded-lg shadow-2xl">

    <!-- Top bar -->
    <div class="flex items-center justify-between gap-4 mb-6">
      <a href="/R3_01-Dungeon-Explorer/chapter/show"
         class="px-4 py-2 rounded-[15px] border-[3px] border-[#8f6a1b]
                bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
                shadow-[inset_0_0_10px_rgba(255,225,150,0.8),_0_0_15px_rgba(0,0,0,0.4)]
                hover:scale-105 transition-transform">
        Retour
      </a>

      <div class="text-xs text-yellow-300">
        Pièces : <span class="font-bold"><?= (int)$coins ?></span>
      </div>
    </div>

    <?php if (!empty($flash)): ?>
      <div class="mb-4 p-3 rounded border text-xs
        <?= $flash['type'] === 'ok' ? 'border-green-500 text-green-300 bg-green-900/30' : 'border-red-500 text-red-300 bg-red-900/30' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-6 mb-6">
      <div class="md:w-1/3 bg-[#1a1a1a] border border-gray-700 rounded p-4">
        <div class="text-yellow-400 text-sm mb-2"><?= htmlspecialchars($marchand['pnj_name']) ?></div>
        <div class="text-[10px] text-gray-300 mb-3"><?= htmlspecialchars($marchand['description'] ?? '') ?></div>
      </div>

      <div class="md:w-2/3 bg-[#1a1a1a] border border-gray-700 rounded p-4">
        <div class="text-yellow-400 text-sm mb-3">Objets en vente</div>

        <?php if (empty($stock)): ?>
          <div class="text-gray-400 text-xs">Le marchand n’a rien à vendre.</div>
        <?php else: ?>
          <div class="space-y-3">
            <?php foreach ($stock as $s): ?>
              <div class="border border-gray-700 rounded p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                  <div class="text-sm"><?= htmlspecialchars($s['name']) ?></div>
                  <div class="text-[10px] text-gray-400">
                    Type: <?= htmlspecialchars($s['item_type']) ?> • Bonus: <?= (int)$s['bonus'] ?> • Stock: <?= (int)$s['quantity'] ?>
                  </div>
                  <?php if (!empty($s['description'])): ?>
                    <div class="text-[10px] text-gray-500 mt-1"><?= htmlspecialchars($s['description']) ?></div>
                  <?php endif; ?>
                </div>

                <div class="flex items-center gap-3 justify-end">
                  <div class="text-xs text-yellow-300">
                    Prix: <span class="font-bold"><?= (int)round((float)$s['price']) ?></span> pièces
                  </div>

                  <form method="POST" action="/R3_01-Dungeon-Explorer/merchant/buy" class="flex items-center gap-2">
                    <input type="hidden" name="pnj_id" value="<?= (int)$marchand['pnj_id'] ?>">
                    <input type="hidden" name="item_id" value="<?= (int)$s['item_id'] ?>">

                    <input name="qty" type="number" min="1" max="<?= (int)$s['quantity'] ?>"
                           value="1"
                           class="w-20 bg-black border border-gray-600 rounded px-2 py-1 text-xs">

                    <button type="submit"
                      class="px-4 py-2 rounded border-2 border-[#2b2116]
                             bg-red-700 hover:bg-red-600 text-xs transition
                             disabled:opacity-40"
                      <?= ((int)$s['quantity'] <= 0) ? 'disabled' : '' ?>>
                      Acheter
                    </button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="bg-[#1a1a1a] border border-gray-700 rounded p-4">
      <div class="text-yellow-400 text-sm mb-3">Votre inventaire</div>

      <?php if (empty($heroInventory)): ?>
        <div class="text-gray-400 text-xs">Inventaire vide.</div>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <?php foreach ($heroInventory as $inv): ?>
            <div class="border border-gray-800 rounded p-3">
              <div class="text-xs"><?= htmlspecialchars($inv['name']) ?> x<?= (int)$inv['quantity'] ?></div>
              <div class="text-[10px] text-gray-400">
                <?= htmlspecialchars($inv['item_type']) ?> • Bonus: <?= (int)$inv['bonus'] ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
