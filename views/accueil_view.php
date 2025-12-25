<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DungeonExplorer – Connexion</title>
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
    </style>
</head>
<body class="flex flex-col items-center justify-start min-h-screen">

<div class="text-center mt-10">

    <img src="/R3_01-Dungeon-Explorer/sprites/icon/Logo.png" class="w-[400px] mb-10 mx-auto" alt="Logo du jeu">

    <div class="flex justify-center flex-wrap">
        <?php $btnClass = "inline-block m-[50px] px-[30px] py-[15px] w-[230px] bg-[linear-gradient(145deg,#c9a43a,#f4d67a,#b58b2a)] border-[3px] border-[#8f6a1b] rounded-[15px] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_15px_rgba(0,0,0,0.4)] font-['Cinzel'] text-[22px] tracking-[2px] text-[#3b2200] font-bold text-center no-underline cursor-pointer transition duration-200 hover:bg-[linear-gradient(145deg,#ffeb99,#f7d87c,#d1aa3c)] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105"; ?>
        
        <a href="connexion" class="<?= $btnClass ?>">Se connecter</a>
        <a href="inscriptions" class="<?= $btnClass ?>">S'inscrire</a>
    </div>

</div>

</body>
</html>