<?php
// views/equipement/index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Équipement</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-900 text-white p-6">

  <!-- Boutons non superposés -->
  <div class="fixed top-4 left-4 z-50 flex flex-col gap-3">
    <form action="/R3_01-Dungeon-Explorer/chapter/show" method="get">
      <button type="submit"
        class="px-4 py-2 text-[14px] rounded-[15px] border-[3px] border-[#8f6a1b]
               bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
               shadow-[inset_0_0_10px_rgba(255,225,150,0.8),_0_0_15px_rgba(0,0,0,0.4)]
               hover:scale-105 transition-transform duration-200">
        Retour
      </button>
    </form>

    <form action="/R3_01-Dungeon-Explorer/pageUser" method="get">
      <button type="submit"
        class="px-4 py-2 text-[14px] rounded-[15px] border-[3px] border-[#8f6a1b]
               bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
               shadow-[inset_0_0_10px_rgba(255,225,150,0.8),_0_0_15px_rgba(0,0,0,0.4)]
               hover:scale-105 transition-transform duration-200">
        Sauvegarder
      </button>
    </form>
  </div>

  <div class="max-w-6xl mx-auto pt-20">
    <h1 class="text-2xl font-bold mb-6">
      Gestion de l’équipement — <?= htmlspecialchars($hero['name'] ?? 'Héros') ?>
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- EQUIPED -->
      <div class="bg-black/50 border border-gray-700 rounded-xl p-4">
        <h2 class="text-lg font-semibold mb-4">Équipé</h2>

        <?php
        $slots = [
          'armor' => ['label' => 'Armure', 'item' => $equipped['armor']],
          'primary' => ['label' => 'Arme principale', 'item' => $equipped['primary']],
          'secondary' => ['label' => 'Arme secondaire', 'item' => $equipped['secondary']],
          'shield' => ['label' => 'Bouclier', 'item' => $equipped['shield']],
        ];
        ?>

        <div class="space-y-3">
          <?php foreach ($slots as $key => $s): ?>
            <div class="flex items-center justify-between bg-gray-800/60 border border-gray-700 rounded-lg p-3">
              <div>
                <div class="text-sm text-gray-300"><?= htmlspecialchars($s['label']) ?></div>

                <?php if (!empty($s['item'])): ?>
                  <div class="font-semibold"><?= htmlspecialchars($s['item']['name'] ?? 'Objet') ?></div>
                  <div class="text-xs text-gray-400">
                    Type: <?= htmlspecialchars($s['item']['item_type'] ?? '-') ?>
                    <?php if (isset($s['item']['bonus'])): ?> • Bonus: <?= (int)$s['item']['bonus'] ?><?php endif; ?>
                    <?php if (isset($s['item']['value'])): ?> • Valeur: <?= (int)$s['item']['value'] ?><?php endif; ?>
                  </div>
                <?php else: ?>
                  <div class="text-gray-500">Aucun</div>
                <?php endif; ?>
              </div>

              <?php if (!empty($s['item'])): ?>
                <form method="POST" action="/R3_01-Dungeon-Explorer/equipment/unequip">
                  <input type="hidden" name="slot" value="<?= htmlspecialchars($key) ?>">
                  <button class="px-3 py-2 text-xs rounded-lg bg-red-700 hover:bg-red-600 transition">
                    Retirer
                  </button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- INVENTORY -->
      <div class="bg-black/50 border border-gray-700 rounded-xl p-4">
        <h2 class="text-lg font-semibold mb-4">Inventaire</h2>

        <?php if (empty($inventory)): ?>
          <div class="text-gray-400">Inventaire vide.</div>
        <?php else: ?>
          <div class="space-y-3">
            <?php foreach ($inventory as $it): ?>
              <div class="bg-gray-800/60 border border-gray-700 rounded-lg p-3">
                <div class="flex items-start justify-between gap-4">
                  <div class="min-w-0">
                    <div class="font-semibold">
                      <?= htmlspecialchars($it['name'] ?? 'Objet') ?>
                      <span class="text-xs text-gray-400">x<?= (int)($it['quantity'] ?? 0) ?></span>
                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                      Type: <?= htmlspecialchars($it['item_type'] ?? '-') ?>
                      <?php if (isset($it['bonus'])): ?> • Bonus: <?= (int)$it['bonus'] ?><?php endif; ?>
                      <?php if (isset($it['value'])): ?> • Valeur: <?= (int)$it['value'] ?><?php endif; ?>
                    </div>

                    <?php if (!empty($it['description'])): ?>
                      <div class="text-xs text-gray-300 mt-2">
                        <?= htmlspecialchars($it['description']) ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="flex flex-col gap-2 shrink-0">
                    <?php $type = mb_strtolower(trim($it['item_type'] ?? '')); ?>

                    <?php if ($type === 'armure'): ?>
                      <form method="POST" action="/R3_01-Dungeon-Explorer/equipment/equip">
                        <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                        <button class="px-3 py-2 text-xs rounded-lg bg-emerald-700 hover:bg-emerald-600 transition">
                          Équiper
                        </button>
                      </form>

                    <?php elseif ($type === 'arme'): ?>
                      <form method="POST" action="/R3_01-Dungeon-Explorer/equipment/equip">
                        <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                        <input type="hidden" name="slot" value="primary">
                        <button class="px-3 py-2 text-xs rounded-lg bg-emerald-700 hover:bg-emerald-600 transition">
                          Main
                        </button>
                      </form>

                      <form method="POST" action="/R3_01-Dungeon-Explorer/equipment/equip">
                        <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                        <input type="hidden" name="slot" value="secondary">
                        <button class="px-3 py-2 text-xs rounded-lg bg-emerald-800 hover:bg-emerald-700 transition">
                          Secondaire
                        </button>
                      </form>

                    <?php elseif ($type === 'bouclier'): ?>
                      <form method="POST" action="/R3_01-Dungeon-Explorer/equipment/equip">
                        <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                        <button class="px-3 py-2 text-xs rounded-lg bg-emerald-700 hover:bg-emerald-600 transition">
                          Équiper
                        </button>
                      </form>

                    <?php else: ?>
                      <button disabled class="px-3 py-2 text-xs rounded-lg bg-gray-700 text-gray-400 cursor-not-allowed">
                        Non équipable
                      </button>
                    <?php endif; ?>
                  </div>

                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

</body>
</html>
