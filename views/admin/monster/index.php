<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-[url('/R3_01-Dungeon-Explorer/sprites/background/pagePrincipale.png')] bg-cover bg-center bg-fixed font-sans text-[#ffe9a3] px-3 py-6">
<div class="mx-auto my-8 md:my-12 w-[92%] max-w-5xl bg-black/55 p-5 md:p-8 border-4 border-[#8f6a1b] rounded-[20px] shadow-[0_0_30px_rgba(0,0,0,0.7),inset_0_0_25px_rgba(255,225,150,0.3)]">
    <h1 class="text-center font-serif text-3xl md:text-5xl mb-5 text-[#ffe9a3] [text-shadow:0_0_12px_black]">Administration — Monstres</h1>
</div>

<div class="admin-card">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px;">
        <a href="../../admin" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">⬅ Retour admin</a>
        <a href="create" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">+ Créer un monstre</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="admin-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <table class="w-full min-w-[720px] border-collapse my-5 text-[#ffe9a3] [&_th]:p-3 [&_td]:p-3 [&_th]:border-2 [&_td]:border-2 [&_th]:border-[#8f6a1b] [&_td]:border-[#8f6a1b] [&_td]:bg-black/40 [&_th]:bg-[#8c6e28]/50 [&_th]:font-serif [&_th]:text-lg">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>PV</th>
                <th>Mana</th>
                <th>Init</th>
                <th>Force</th>
                <th>XP</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($monsters as $m): ?>
                <tr>
                    <td><?= (int) $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= (int) $m['pv'] ?></td>
                    <td><?= $m['mana'] === null ? '-' : (int) $m['mana'] ?></td>
                    <td><?= (int) $m['initiative'] ?></td>
                    <td><?= (int) $m['strength'] ?></td>
                    <td><?= (int) $m['xp'] ?></td>
                    <td>
                        <?php if (!empty($m['image'])): ?>
                            <img src="<?= htmlspecialchars($m['image']) ?>" alt="Monstre"
                                style="max-height:55px; border-radius:10px;">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" href="chapters&id=<?= (int) $m['id'] ?>">
                            Apparitions
                        </a>

                        <a class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition" href="edit&id=<?= (int) $m['id'] ?>">
                            Modifier
                        </a>

                        <form action="index.php" method="get" style="display:inline"
                            onsubmit="return confirm('Supprimer ce monstre ?');">
                            <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="hidden" name="url" value="admin/monster/delete">
                            <input class="w-full p-3 rounded-lg border-2 border-[#8f6a1b] bg-black/40 text-[#ffe9a3] focus:outline-none focus:ring-2 focus:ring-[#f4d67a]"  type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                            <button type="submit" class="inline-block w-fit px-6 py-3 mb-4 md:mb-0 md:ml-6 rounded-xl border-[3px] border-[#8f6a1b] bg-gradient-to-br from-[#c9a43a] via-[#f4d67a] to-[#b58b2a] shadow-[inset_0_0_10px_rgba(255,225,150,0.8),0_0_12px_rgba(0,0,0,0.4)] font-serif text-base md:text-lg font-bold tracking-wide text-[#3b2200] no-underline hover:from-[#ffeb99] hover:via-[#f7d87c] hover:to-[#d1aa3c] hover:border-[#b78925] hover:shadow-[inset_0_0_15px_rgba(255,240,190,1),0_0_20px_rgba(255,200,80,0.9)] hover:scale-105 transition">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
</div>
