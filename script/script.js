const buttons = document.querySelectorAll(".class-btn");

buttons.forEach(btn => {
    btn.addEventListener("click", () => {

        document.getElementById("className").textContent = btn.dataset.name;
        document.getElementById("classDesc").textContent = btn.dataset.desc;

        setStat("pv", btn.dataset.pv);
        setStat("mana", btn.dataset.mana);
        setStat("str", btn.dataset.str);
        setStat("ini", btn.dataset.ini);

        document.getElementById("maxVal").textContent = btn.dataset.max;

        document.getElementById("classImg").src = btn.dataset.img;
    });
});

function setStat(name, value) {
    document.getElementById(name+"Val").textContent = value;
    document.getElementById(name+"Bar").style.width = value+"px";
}

const img = document.getElementById("classImg");

buttons.forEach(btn => {
    btn.addEventListener("click", () => {

        // retire sélection
        buttons.forEach(b => b.classList.remove("selected"));

        // ajoute sélection
        btn.classList.add("selected");

        document.getElementById("className").textContent = btn.dataset.name;
        document.getElementById("classDesc").textContent = btn.dataset.desc;

        setStat("pv", btn.dataset.pv);
        setStat("mana", btn.dataset.mana);
        setStat("str", btn.dataset.str);
        setStat("ini", btn.dataset.ini);

        document.getElementById("maxVal").textContent = btn.dataset.max;

        // ANIMATION IMAGE
        img.classList.remove("visible");
        img.src = btn.dataset.img;

        setTimeout(() => {
            img.classList.add("visible");
        }, 50);
    });
});
