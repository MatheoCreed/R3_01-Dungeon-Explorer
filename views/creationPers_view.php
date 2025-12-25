<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de personnage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url("../sprites/background/ChoixHeros.png") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0; 
            padding: 0;
        }

        /* Classes d'état gérées par le script JS externe */
        .class-btn.selected {
            transform: scale(1.1);
            background: linear-gradient(145deg, #ffeb99, #f7d87c, #d1aa3c) !important;
            border-color: #ffd24a !important;
            box-shadow: inset 0 0 20px rgba(255,240,190,1), 0 0 25px rgba(255,200,80,1) !important;
        }

        .image img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            opacity: 0;
            transform: scale(0.8);
            transition: opacity 0.4s ease-out, transform 0.4s ease-out;
        }
        
        .image img.visible {
            opacity: 1;
            transform: scale(1);
        }
        
        @keyframes goldGlow {
            0% { border-color: rgba(255,220,130,0.3); }
            100% { border-color: rgba(255,220,130,1); }
        }
        
        /* Animation cadre si nécessaire par le JS */
        .image {
            position: relative;
            overflow: hidden;
            /* Si le JS ajoute une classe d'animation, elle s'appliquera ici */
        }
    </style>
</head>

<body>

<div class="flex justify-center items-start gap-10 mt-[50px] flex-wrap">
    
    <a href="../page-user" 
       class="fixed top-5 right-5 z-50 inline-block px-[30px] py-[15px] w-[100px] bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)] border-[3px] border-[#8f6a1b] rounded-[15px] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)] font-['Cinzel'] text-[22px] tracking-[2px] text-[#3b2200] font-bold text-center no-underline cursor-pointer transition duration-200 hover:bg-[linear-gradient(145deg,#ffeb99,#f7d87c,#d1aa3c)] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105">
       Retour
    </a>

    <div class="class-list grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-2.5 w-[400px] max-h-[300px] p-2.5">
        <?php foreach ($classes as $class): ?>
            <button 
                type="button"
                class="class-btn w-full p-2.5 text-base text-center cursor-pointer transition-transform duration-200 bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)] border-[3px] border-[#8f6a1b] rounded-[15px] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)] hover:bg-[linear-gradient(145deg,#ffeb99,#f7d87c,#d1aa3c)] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105"
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

    <div class="image w-[500px] h-[500px] flex mt-12 ml-[100px]">
        <img id="heroSprite" src="" alt="" class="max-w-[90%] max-h-[90%] object-contain opacity-0 scale-80 transition-all duration-400 ease-out">
    </div>

    <div class="stats bg-black/55 p-[20px_30px] border-[4px] border-[#8f6a1b] rounded-[15px] text-[#ffe9a3] w-[300px] text-left shadow-[inset_0_0_20px_rgba(0,0,0,0.6),0_0_15px_rgba(0,0,0,0.7)] mt-[100px] mr-[100px]">
        <h2 class="text-center font-['Cinzel'] mb-2.5">Statistiques</h2>

        <div class="stat flex items-center mb-2.5 gap-2.5">
            <span class="inline-block w-20">PV</span>
            <div class="bar bg-white/20 w-[150px] h-[15px] rounded-[10px] overflow-hidden border border-black/50">
                <div id="bar-pv" class="h-full bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)] w-0 transition-[width] duration-400 ease-out shadow-[inset_0_0_5px_rgba(255,240,190,0.5)]"></div>
            </div>
        </div>

        <div class="stat flex items-center mb-2.5 gap-2.5">
            <span class="inline-block w-20">Mana</span>
            <div class="bar bg-white/20 w-[150px] h-[15px] rounded-[10px] overflow-hidden border border-black/50">
                <div id="bar-mana" class="h-full bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)] w-0 transition-[width] duration-400 ease-out shadow-[inset_0_0_5px_rgba(255,240,190,0.5)]"></div>
            </div>
        </div>

        <div class="stat flex items-center mb-2.5 gap-2.5">
            <span class="inline-block w-20">Force</span>
            <div class="bar bg-white/20 w-[150px] h-[15px] rounded-[10px] overflow-hidden border border-black/50">
                <div id="bar-strength" class="h-full bg-[linear-gradient(90deg,#ffeb99,#d1aa3c)] w-0 transition-[width] duration-400 ease-out shadow-[inset_0_0_5px_rgba(255,240,190,0.5)]"></div>
            </div>
        </div>

    </div>
</div>

<form action="/R3_01-Dungeon-Explorer/hero/insert" method="POST">
    <div class="name-container w-full flex justify-center">
        <input type="text" name="name" id="heroName" placeholder="Nom du héros" required 
               class="w-[300px] p-2.5 text-lg rounded-[10px] border-[3px] border-[#8f6a1b] bg-[rgba(255,245,200,0.8)] text-center outline-none">
    </div>

    <input type="hidden" name="class_id" id="selectedClass">

    <div class="create-container w-full flex justify-center mt-0">
        <button type="submit" 
                class="create-btn block w-[300px] mx-auto mt-0 p-3 text-lg text-center cursor-pointer transition-transform duration-200 text-black bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)] border-[3px] border-[#8f6a1b] rounded-[15px] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)] hover:bg-[linear-gradient(145deg,#ffeb99,#f7d87c,#d1aa3c)] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105">
            Créer mon personnage
        </button>
    </div>
</form>

<script src="/R3_01-Dungeon-Explorer/script/script.js"></script>

</body>
</html>