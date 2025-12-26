<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Création de personnage</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { cinzel: ['Cinzel', 'serif'] }
        }
      }
    }
  </script>

  <style>
    body {
      background: url("../sprites/background/ChoixHeros.png") no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    /* État géré par ton JS */
    .class-btn.selected {
      transform: scale(1.06);
      background: linear-gradient(145deg, #ffeb99, #f7d87c, #d1aa3c) !important;
      border-color: #ffd24a !important;
      box-shadow: inset 0 0 20px rgba(255,240,190,1), 0 0 25px rgba(255,200,80,1) !important;
    }

    /* Animation sprite gérée par ton JS (.visible) */
    #heroSprite {
      opacity: 0;
      transform: scale(0.85);
      transition: opacity .35s ease-out, transform .35s ease-out;
    }
    #heroSprite.visible {
      opacity: 1;
      transform: scale(1);
    }
  </style>
</head>

<body class="min-h-screen">
  <!-- Barre haute : Retour dans le flux (ne chevauche pas l'aperçu) -->
  <div class="max-w-6xl mx-auto px-3 sm:px-6 pt-4">
    <a href="../page-user"
       class="inline-flex items-center justify-center
              w-28 px-4 py-3 rounded-xl border-2 border-[#8f6a1b]
              bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
              shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)]
              font-cinzel font-bold text-[#3b2200] tracking-wide
              transition hover:scale-105">
      Retour
    </a>
  </div>

  <!-- Layout principal -->
  <div class="mx-auto w-full max-w-6xl px-3 sm:px-6 pt-4 sm:pt-6 pb-[240px]">
    <!--
      Mobile: flex-col (ordre stable : classes puis aperçu)
      Desktop: grid 3 colonnes (classes / aperçu / stats)
    -->
    <div class="flex flex-col gap-4 md:grid md:grid-cols-3 md:gap-6 items-start">

      <!-- COLONNE GAUCHE : classes en colonne -->
      <section class="md:col-span-1 w-full">
        <div class="rounded-2xl border-2 border-[#8f6a1b] bg-black/45 backdrop-blur-sm p-3 sm:p-4">
          <h2 class="font-cinzel text-[#ffe9a3] text-center text-base sm:text-lg mb-3">
            Choisis ta classe
          </h2>

          <div class="flex flex-col gap-2 max-h-[55vh] md:max-h-[62vh] overflow-auto pr-1">
            <?php foreach ($classes as $class): ?>
              <button
                type="button"
                class="class-btn w-full
                       px-3 py-2 text-sm sm:text-base
                       md:px-2 md:py-1.5 md:text-xs
                       lg:px-2 lg:py-1.5 lg:text-xs
                       rounded-xl border-2 border-[#8f6a1b]
                       bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
                       shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.35)]
                       font-cinzel font-bold text-[#3b2200] text-center
                       transition hover:scale-[1.02]"
                data-id="<?= $class->getId(); ?>"
                data-sprite="<?= $class->getImage(); ?>"
                data-hp="<?= $class->getBasePv(); ?>"
                data-atk="<?= $class->getBaseMana(); ?>"
                data-def="<?= $class->getStrength(); ?>"
                data-init="<?= $class->getInitiative(); ?>"
                data-items="<?= $class->getMaxItems(); ?>"
              >
                <?= htmlspecialchars($class->getName()); ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- COLONNE CENTRE : aperçu -->
      <section class="md:col-span-1 w-full flex flex-col items-center">
        <div class="w-full rounded-2xl border-2 border-[#8f6a1b] bg-black/30 backdrop-blur-sm p-3 sm:p-4">
          <h2 class="font-cinzel text-[#ffe9a3] text-center text-base sm:text-lg mb-3">
            Aperçu
          </h2>

          <div class="flex items-start justify-center w-full">
            <div class="w-full max-w-[280px] sm:max-w-[360px] md:max-w-[420px]
                        aspect-square flex items-center justify-center">
              <img id="heroSprite" src="" alt="" class="max-w-[92%] max-h-[92%] object-contain">
            </div>
          </div>
        </div>
      </section>

      <!-- COLONNE DROITE : stats en barres (desktop uniquement) -->
      <aside class="hidden md:block md:col-span-1 w-full">
        <div class="rounded-2xl border-2 border-[#8f6a1b] bg-black/55 backdrop-blur-sm p-3 sm:p-4 text-[#ffe9a3]
                    shadow-[inset_0_0_20px_rgba(0,0,0,0.6),0_0_15px_rgba(0,0,0,0.6)]">
          <h2 class="text-center font-cinzel text-base sm:text-lg mb-3">Statistiques</h2>

          <!-- PV -->
          <div class="flex items-center gap-3 mb-3">
            <span class="w-16 lg:w-20 text-sm lg:text-base">PV</span>
            <div class="flex-1 h-4 rounded-full overflow-hidden bg-white/15 border border-black/40">
              <div id="bar-pv"
                   class="h-full w-0 bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)]
                          transition-[width] duration-300 ease-out"></div>
            </div>
          </div>

          <!-- Mana -->
          <div class="flex items-center gap-3 mb-3">
            <span class="w-16 lg:w-20 text-sm lg:text-base">Mana</span>
            <div class="flex-1 h-4 rounded-full overflow-hidden bg-white/15 border border-black/40">
              <div id="bar-mana"
                   class="h-full w-0 bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)]
                          transition-[width] duration-300 ease-out"></div>
            </div>
          </div>

          <!-- Force -->
          <div class="flex items-center gap-3">
            <span class="w-16 lg:w-20 text-sm lg:text-base">Force</span>
            <div class="flex-1 h-4 rounded-full overflow-hidden bg-white/15 border border-black/40">
              <div id="bar-strength"
                   class="h-full w-0 bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)]
                          transition-[width] duration-300 ease-out"></div>
            </div>
          </div>
        </div>
      </aside>

    </div>
  </div>

  <!-- BAS : stats (valeurs en mobile) + nom + bouton -->
  <form action="/R3_01-Dungeon-Explorer/hero/insert" method="POST"
        class="fixed bottom-0 left-0 right-0 z-40">
    <input type="hidden" name="class_id" id="selectedClass">

    <div class="mx-auto w-full max-w-4xl px-3 sm:px-6 pb-4">
      <div class="rounded-2xl border-2 border-[#8f6a1b] bg-black/60 backdrop-blur-md p-3 sm:p-4">

        <!-- Stats MOBILE (valeurs uniquement) -->
        <div class="md:hidden mb-3">
          <div class="rounded-xl border border-[#8f6a1b]/70 bg-black/40 p-3">
            <div class="grid grid-cols-3 gap-2 text-[#ffe9a3]">
              <div class="text-center">
                <div class="text-xs font-cinzel opacity-90">PV</div>
                <div id="val-pv-m" class="text-lg font-bold tabular-nums">—</div>
              </div>
              <div class="text-center">
                <div class="text-xs font-cinzel opacity-90">Mana</div>
                <div id="val-mana-m" class="text-lg font-bold tabular-nums">—</div>
              </div>
              <div class="text-center">
                <div class="text-xs font-cinzel opacity-90">Force</div>
                <div id="val-strength-m" class="text-lg font-bold tabular-nums">—</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Nom + bouton -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 justify-center">
          <input
            type="text"
            name="name"
            id="heroName"
            placeholder="Nom du héros"
            required
            class="w-full sm:w-[360px] px-4 py-3 text-base sm:text-lg rounded-xl
                   border-2 border-[#8f6a1b]
                   bg-[rgba(255,245,200,0.9)] text-center outline-none"
          >

          <button type="submit"
                  class="w-full sm:w-auto px-6 py-3 rounded-xl
                         border-2 border-[#8f6a1b]
                         bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
                         shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.35)]
                         font-cinzel font-bold text-[#3b2200]
                         transition hover:scale-105">
            Créer mon personnage
          </button>
        </div>

        <p class="text-center text-xs sm:text-sm text-[#ffe9a3]/80 mt-2">
          Sélectionne une classe puis choisis un nom.
        </p>
      </div>
    </div>
  </form>

  <script src="/R3_01-Dungeon-Explorer/script/script.js"></script>
</body>
</html>
