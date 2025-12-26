<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-[url('/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png')] bg-cover bg-center bg-fixed font-sans text-[#ffe9a3] px-3 py-6">
<a href="index" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 mt-4 md:mt-10 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Retour</a>

<div class="mx-auto my-8 md:my-12 w-[92%] max-w-5xl bg-black/55 p-5 md:p-8 border-4 border-[#8f6a1b] rounded-[20px] shadow-[0_0_30px_rgba(0,0,0,0.7),inset_0_0_25px_rgba(255,225,150,0.3)]">
    <h1 class="text-center font-serif text-3xl md:text-5xl mb-5 text-[#ffe9a3] [text-shadow:0_0_12px_black]">Modifier le lien #<?= $link['id'] ?></h1>
</div>

<form method="POST">

    <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="hidden" name="id" value="<?= $link['id'] ?>">

    <label class="block font-serif text-lg mb-1">Chapitre de départ :</label>
    <input type="text" class="admin-input" value="<?= $link['chapter_id'] ?>" disabled>

    <label class="block font-serif text-lg mb-1">Choisir le chapitre cible :</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
        <?php foreach ($chapters as $c): ?>
            <label class="bg-black/50 border-[3px] border-[#8f6a1b] rounded-xl p-5 text-center text-[#ffe9a3] no-underline transition hover:scale-[1.02] hover:shadow-[0_0_18px_rgba(255,200,80,0.4)] cursor-pointer">
                <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="radio" name="next_id"
                       value="<?= $c['id'] ?>"
                       <?= $c['id'] == $link['next_chapter_id'] ? 'checked' : '' ?>>
                <h3><?= htmlspecialchars($c['title']) ?></h3>
                <p>ID : <?= $c['id'] ?></p>
            </label>
        <?php endforeach; ?>
    </div>

    <label class="block font-serif text-lg mb-1">Texte du choix :</label>
    <textarea class="w-full min-h-[120px] p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]" name="choice_text" required>
<?= htmlspecialchars($link['choice_text']) ?>
    </textarea>

    <button type="submit" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Modifier le lien</button>
</form>
</div>
