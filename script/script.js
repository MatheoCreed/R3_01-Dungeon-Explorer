document.addEventListener("DOMContentLoaded", () => {

    if (!Array.isArray(classes)) {
        console.error("classes n'est pas un tableau", classes);
        return;
    }

    const classList = document.getElementById("classList");
    const img = document.getElementById("classImg");

    function setStat(name, value) {
        document.getElementById(name+"Val").textContent = value;
        document.getElementById(name+"Bar").style.width = value+"px";
    }

    function selectClass(btn) {

        document.getElementById("className").textContent = btn.dataset.name;
        document.getElementById("classDesc").textContent = btn.dataset.desc;

        setStat("pv", btn.dataset.pv);
        setStat("mana", btn.dataset.mana);
        setStat("str", btn.dataset.str);
        setStat("ini", btn.dataset.ini);

        document.getElementById("maxVal").textContent = btn.dataset.max;

        img.classList.remove("visible");
        img.src = btn.dataset.img;

        setTimeout(() => {
            img.classList.add("visible");
        }, 50);

        document
            .querySelectorAll(".class-btn")
            .forEach(b => b.classList.remove("selected"));

        btn.classList.add("selected");
    }

    function makeButton(c) {
        const btn = document.createElement("button");
        btn.className = "class-btn";

        btn.dataset.name = c.name;
        btn.dataset.desc = c.desc;
        btn.dataset.pv = c.pv;
        btn.dataset.mana = c.mana;
        btn.dataset.str = c.str;
        btn.dataset.ini = c.ini;
        btn.dataset.max = c.max;
        btn.dataset.img = c.img;

        btn.textContent = c.name;

        btn.addEventListener("click", () => selectClass(btn));

        return btn;
    }

    classes.forEach(c => classList.appendChild(makeButton(c)));
});

