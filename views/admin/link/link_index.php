<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-[url('/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png')] bg-cover bg-center bg-fixed font-sans text-[#ffe9a3] px-3 py-6">
<div class="mx-auto my-8 md:my-12 w-[92%] max-w-5xl bg-black/55 p-5 md:p-8 border-4 border-[#8f6a1b] rounded-[20px] shadow-[0_0_30px_rgba(0,0,0,0.7),inset_0_0_25px_rgba(255,225,150,0.3)]">
    <h1 class="text-center font-serif text-3xl md:text-5xl mb-5 text-[#ffe9a3] [text-shadow:0_0_12px_black]">Gestion des liens entre chapitres</h1>
</div>
<a href="../../admin" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 mt-4 md:mt-10 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Retour</a>

<a href="create1" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Ajouter un lien</a>

<div class="overflow-x-auto w-[92%] mx-auto my-5">
<table class="w-full min-w-[720px] border-collapse my-5 text-[#ffe9a3] [&_th]:p-3 [&_td]:p-3 [&_th]:border-2 [&_td]:border-2 [&_th]:border-[#8f6a1b] [&_td]:border-[#8f6a1b] [&_td]:bg-black/40 [&_th]:bg-[#8c6e28]/50 [&_th]:font-serif [&_th]:text-lg">
    <thead>
        <tr>
            <th>ID</th>
            <th>Depuis</th>
            <th>Vers</th>
            <th>Texte du choix</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($links as $link): ?>
            <tr>
                <td><?= $link['id'] ?></td>
                <td>Chapitre <?= $link['chapter_id'] ?></td>
                <td>Chapitre <?= $link['next_chapter_id'] ?></td>
                <td><?= htmlspecialchars($link['choice_text']) ?></td>

                <td>
                    <a class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" href="edit?id=<?= $link['id'] ?>">Modifier</a>
                    <a class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" 
                       href="delete?id=<?= $link['id'] ?>"
                       onclick="return confirm('Supprimer ce lien ?');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
