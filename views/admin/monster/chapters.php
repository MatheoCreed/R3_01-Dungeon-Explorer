<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-[url('/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png')] bg-cover bg-center bg-fixed font-sans text-[#ffe9a3] px-3 py-6">
<div class="mx-auto my-8 md:my-12 w-[92%] max-w-5xl bg-black/55 p-5 md:p-8 border-4 border-[#8f6a1b] rounded-[20px] shadow-[0_0_30px_rgba(0,0,0,0.7),inset_0_0_25px_rgba(255,225,150,0.3)]">
    <h1 class="text-center font-serif text-3xl md:text-5xl mb-5 text-[#ffe9a3] [text-shadow:0_0_12px_black]">
        Apparitions — <?= htmlspecialchars($monster['name']) ?> (#<?= (int)$monster['id'] ?>)
    </h1>
</div>

<div class="admin-card">
    <div style="display:flex; gap:10px; margin-bottom:14px;">
        <a class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" href="index.php?url=admin/monster/index"> Retour monstres</a>
        <a class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" href="index.php?url=admin/monster/edit&id=<?= (int)$monster['id'] ?>"> Modifier monstre</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="admin-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h3 style="margin-top:0;">Ajouter ce monstre à un chapitre</h3>

    <form class="w-full max-w-2xl mx-auto my-8 bg-black/55 p-5 md:p-6 rounded-[15px] border-[3px] border-[#8f6a1b] shadow-[inset_0_0_20px_rgba(0,0,0,0.6),0_0_15px_rgba(0,0,0,0.7)]" method="POST"
          action="index.php?url=admin/monster/chapters/add&id=<?= (int)$monster['id'] ?>">

        <label class="block font-serif text-lg mb-1">Chapitre</label>
        <select name="chapter_id" class="admin-input" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($chapters as $c): ?>
                <option value="<?= (int)$c['id'] ?>">
                    #<?= (int)$c['id'] ?> — <?= htmlspecialchars($c['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" type="submit">Ajouter</button>
    </form>

    <hr style="margin:18px 0;">

    <h3>Chapitres liés</h3>

    <?php if (empty($linkedChapters)): ?>
        <p>Aucun chapitre lié pour ce monstre.</p>
    <?php else: ?>
        <table class="w-full min-w-[720px] border-collapse my-5 text-[#ffe9a3] [&_th]:p-3 [&_td]:p-3 [&_th]:border-2 [&_td]:border-2 [&_th]:border-[#8f6a1b] [&_td]:border-[#8f6a1b] [&_td]:bg-black/40 [&_th]:bg-[#8c6e28]/50 [&_th]:font-serif [&_th]:text-lg">
            <thead>
            <tr>
                <th>Chapitre</th>
                <th>Titre</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($linkedChapters as $lc): ?>
                <tr>
                    <td>#<?= (int)$lc['chapter_id'] ?></td>
                    <td><?= htmlspecialchars($lc['title']) ?></td>
                    <td>
                        <form method="GET" action="index.php"
                              style="display:inline"
                              onsubmit="return confirm('Retirer ce monstre de ce chapitre ?');">
                            <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="hidden" name="url" value="admin/monster/chapters/delete">
                            <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="hidden" name="id" value="<?= (int)$monster['id'] ?>">
                            <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="hidden" name="encounter_id" value="<?= (int)$lc['encounter_id'] ?>">
                            <button class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</div>
