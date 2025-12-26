<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Création de personnage</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">

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

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            cinzel: ['Cinzel', 'serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="min-h-screen">
  <!-- Bouton retour -->
  <a href="../page-user"
     class="fixed top-4 right-4 z-50 inline-flex items-center justify-center
            w-28 px-4 py-3 rounded-xl border-2 border-[#8f6a1b]
            bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
            shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)]
            font-cinzel font-bold text-[#3b2200] tracking-wide
            transition hover:scale-105">
    Retour
  </a>

  <!-- Layout principal -->
  <div class="mx-auto w-full max-w-6xl px-3 sm:px-6 pt-6 sm:pt-10 pb-40">
    <!-- Grille responsive : gauche (classes) / centre (image) / droite (stats) -->
    <div class="grid grid-cols-3 gap-3 sm:gap-6 items-start">
      <!-- COLONNE GAUCHE : classes en colonne -->
      <section class="col-span-1">
        <div class="rounded-2xl border-2 border-[#8f6a1b] bg-black/45 backdrop-blur-sm p-3 sm:p-4">
          <h2 class="font-cinzel text-[#ffe9a3] text-center text-base sm:text-lg mb-3">
            Choisis ta classe
          </h2>

          <div class="flex flex-col gap-2 max-h-[60vh] overflow-auto pr-1">
            <?php foreach ($classes as $class): ?>
              <button
                type="button"
                class="class-btn w-full md:max-w-[220px] lg:max-w-[200px]
       px-3 py-2 md:px-2 md:py-1.5 lg:px-2 lg:py-1.5
       text-sm md:text-xs lg:text-xs text-center
       rounded-xl border-2 border-[#8f6a1b]
       bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)]
       shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.35)]
       font-cinzel font-bold text-[#3b2200]
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

      <!-- COLONNE CENTRE : image du personnage au milieu en haut -->
      <section class="col-span-1 flex flex-col items-center">
        <div class="w-full rounded-2xl border-2 border-[#8f6a1b] bg-black/30 backdrop-blur-sm p-3 sm:p-4">
          <h2 class="font-cinzel text-[#ffe9a3] text-center text-base sm:text-lg mb-3">
            Aperçu
          </h2>

          <div class="flex items-start justify-center w-full">
            <!-- Taille responsive : carré qui s’adapte -->
            <div class="w-full max-w-[320px] sm:max-w-[420px] aspect-square flex items-center justify-center">
              <img id="heroSprite" src="" alt="" class="max-w-[92%] max-h-[92%] object-contain">
            </div>
          </div>
        </div>
      </section>

      <!-- COLONNE DROITE : stats -->
      <aside class="col-span-1">
        <div class="rounded-2xl border-2 border-[#8f6a1b] bg-black/55 backdrop-blur-sm p-3 sm:p-4 text-[#ffe9a3]
                    shadow-[inset_0_0_20px_rgba(0,0,0,0.6),0_0_15px_rgba(0,0,0,0.6)]">
          <h2 class="text-center font-cinzel text-base sm:text-lg mb-3">Statistiques</h2>

          <!-- PV -->
          <div class="flex items-center gap-3 mb-3">
            <span class="w-16 sm:w-20 text-sm sm:text-base">PV</span>
            <div class="flex-1 h-3 sm:h-4 rounded-full overflow-hidden bg-white/15 border border-black/40">
              <div id="bar-pv"
                   class="h-full w-0 bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)]
                          transition-[width] duration-300 ease-out"></div>
            </div>
          </div>

          <!-- Mana -->
          <div class="flex items-center gap-3 mb-3">
            <span class="w-16 sm:w-20 text-sm sm:text-base">Mana</span>
            <div class="flex-1 h-3 sm:h-4 rounded-full overflow-hidden bg-white/15 border border-black/40">
              <div id="bar-mana"
                   class="h-full w-0 bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)]
                          transition-[width] duration-300 ease-out"></div>
            </div>
          </div>

          <!-- Force -->
          <div class="flex items-center gap-3">
            <span class="w-16 sm:w-20 text-sm sm:text-base">Force</span>
            <div class="flex-1 h-3 sm:h-4 rounded-full overflow-hidden bg-white/15 border border-black/40">
              <div id="bar-strength"
                   class="h-full w-0 bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)]
                          transition-[width] duration-300 ease-out"></div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <!-- BAS : nom centré + bouton créer -->
  <form action="/R3_01-Dungeon-Explorer/hero/insert" method="POST"
        class="fixed bottom-0 left-0 right-0 z-40">
    <input type="hidden" name="class_id" id="selectedClass">

    <div class="mx-auto w-full max-w-4xl px-3 sm:px-6 pb-4">
      <div class="rounded-2xl border-2 border-[#8f6a1b] bg-black/60 backdrop-blur-md p-3 sm:p-4">
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
