document.addEventListener("DOMContentLoaded", () => {

    console.log("SCRIPT CHARGÉ + DOM OK");

    const buttons = document.querySelectorAll('.class-btn');
    console.log("Boutons trouvés :", buttons.length);

    const img = document.getElementById('heroSprite');
    const barPv = document.getElementById('bar-pv');
    const barMana = document.getElementById('bar-mana');
    const barStrength = document.getElementById('bar-strength');
    const selectedClass = document.getElementById('selectedClass');

    if (buttons.length === 0) {
        console.warn("⚠ Aucun bouton de classe trouvé !");
        return; // on arrête tout ici, pas la peine d’aller plus loin
    }

    buttons.forEach(btn => {

        btn.addEventListener('click', () => {

            // Effacer l'ancienne sélection
            buttons.forEach(b => b.classList.remove('selected'));

            // Sélectionner le bouton
            btn.classList.add('selected');

            // Enregistrer l'ID de la classe choisie dans le formulaire
            selectedClass.value = btn.dataset.id;

            // --- Sprite ---
            img.classList.remove("visible"); // animation

            setTimeout(() => {
                img.src = btn.dataset.sprite;
                img.classList.add("visible");
            }, 200);

            // --- Stats ---
            barPv.style.width = btn.dataset.hp + "%";
            barMana.style.width = btn.dataset.atk + "%";
            barStrength.style.width = btn.dataset.def + "%";
        });
    });
});
