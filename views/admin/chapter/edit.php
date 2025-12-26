<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-[url('/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png')] bg-cover bg-center bg-fixed font-sans text-[#ffe9a3] px-3 py-6">
<div class="mx-auto my-8 md:my-12 w-[92%] max-w-5xl bg-black/55 p-5 md:p-8 border-4 border-[#8f6a1b] rounded-[20px] shadow-[0_0_30px_rgba(0,0,0,0.7),inset_0_0_25px_rgba(255,225,150,0.3)]">
    <h1 class="text-center font-serif text-3xl md:text-5xl mb-5 text-[#ffe9a3] [text-shadow:0_0_12px_black]">Modifier le chapitre</h1>
</div>

<div class="admin-card">

    <a href="index" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Retour</a>

    <form method="POST" enctype="multipart/form-data" class="w-full max-w-2xl mx-auto my-8 bg-black/55 p-5 md:p-6 rounded-[15px] border-[3px] border-[#8f6a1b] shadow-[inset_0_0_20px_rgba(0,0,0,0.6),0_0_15px_rgba(0,0,0,0.7)]">

        <label class="block font-serif text-lg mb-1">Titre</label>
        <input type="text" name="title" class="admin-input" value="<?= htmlspecialchars($chapter['title']) ?>" required>

        <label class="block font-serif text-lg mb-1">Contenu</label>
        <textarea name="content" class="w-full min-h-[120px] p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]" required><?= htmlspecialchars($chapter['content']) ?></textarea>

        <label class="block font-serif text-lg mb-1">Image actuelle</label>
        <img src="<?= htmlspecialchars($chapter['image']) ?>" alt="chap image"
            style="max-width:200px;border-radius:8px;margin-bottom:10px;">

        <label class="block font-serif text-lg mb-1">Nouvelle image (optionnel)</label>
        <input type="file" name="image_file" class="admin-input" accept="image/*">


        <button type="submit" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Mettre à jour</button>

    </form>
</div>
</div>
