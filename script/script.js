document.addEventListener("DOMContentLoaded", () => {
    console.log("SCRIPT CHARGÉ + DOM OK");
  
    const buttons = document.querySelectorAll(".class-btn");
    console.log("Boutons trouvés :", buttons.length);
  
    if (buttons.length === 0) {
      console.warn("⚠ Aucun bouton de classe trouvé !");
      return;
    }
  
    const img = document.getElementById("heroSprite");
    const barPv = document.getElementById("bar-pv");
    const barMana = document.getElementById("bar-mana");
    const barStrength = document.getElementById("bar-strength");
    const selectedClass = document.getElementById("selectedClass");
  
    // Valeurs affichées en responsive (mobile)
    const pvMobile = document.getElementById("val-pv-m");
    const manaMobile = document.getElementById("val-mana-m");
    const strengthMobile = document.getElementById("val-strength-m");
  
    buttons.forEach((btn) => {
      btn.addEventListener("click", () => {
        // Effacer l'ancienne sélection
        buttons.forEach((b) => b.classList.remove("selected"));
        btn.classList.add("selected");
  
        // Enregistrer l'ID de la classe choisie dans le formulaire
        if (selectedClass) {
          selectedClass.value = btn.dataset.id || "";
        }
  
        // --- Sprite ---
        if (img) {
          img.classList.remove("visible");
          setTimeout(() => {
            img.src = btn.dataset.sprite || "";
            img.classList.add("visible");
          }, 200);
        }
  
        // --- Stats (desktop : barres) ---
        // (Tes datasets sont des % déjà, on garde pareil)
        if (barPv) barPv.style.width = (btn.dataset.hp || 0) + "%";
        if (barMana) barMana.style.width = (btn.dataset.atk || 0) + "%";
        if (barStrength) barStrength.style.width = (btn.dataset.def || 0) + "%";
  
        // --- Stats (mobile : valeurs) ---
        // Ici on affiche les valeurs brutes, pas des barres
        if (pvMobile) pvMobile.textContent = btn.dataset.hp ?? "—";
        if (manaMobile) manaMobile.textContent = btn.dataset.atk ?? "—";
        if (strengthMobile) strengthMobile.textContent = btn.dataset.def ?? "—";
      });
    });
  });
  