<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DungeonExplorer – 404</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url("/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        @keyframes flicker {
            0% { opacity: 0.6; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.07); }
        }
        .animate-flicker {
            animation: flicker 1.5s infinite alternate ease-in-out;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

<div class="text-center mt-0">
    <div class="inline-block py-2.5 px-[50px] border-[4px] border-[#8f6a1b] rounded-[20px] bg-black/55 text-[#f8e6b8] shadow-[0_0_30px_rgba(0,0,0,0.7),inset_0_0_25px_rgba(255,225,150,0.3)] text-shadow-[0_0_10px_rgba(0,0,0,0.8)]">

        <img src="/R3_01-Dungeon-Explorer/sprites/icon/torch.png" class="w-[150px] animate-flicker mx-auto" alt="Torche">
        <h1 class="text-[70px] font-['Cinzel'] mb-5 text-[#ffe9a3]">404</h1>

        <p class="text-[24px] mb-5">
            Le chemin que vous cherchez semble avoir disparu dans les profondeurs du donjon…
        </p>

        <a href="/R3_01-Dungeon-Explorer/" 
           class="inline-block m-[50px] px-[30px] py-[15px] w-[230px] bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)] border-[3px] border-[#8f6a1b] rounded-[15px] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)] font-['Cinzel'] text-[22px] tracking-[2px] text-[#3b2200] font-bold text-center no-underline cursor-pointer transition duration-200 hover:bg-[linear-gradient(145deg,#ffeb99,#f7d87c,#d1aa3c)] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105">
           Retour à l'accueil
        </a>
    </div>
</div>

</body>
</html>