document.addEventListener("DOMContentLoaded", () => {

    const userData = window.USER_DATA || {};
    const xp = Number(userData.xp ?? 0);
    const xp_max = Number(userData.xp_max ?? 1000);
    const level = Number(userData.level ?? 1);

    const percent = xp_max > 0 ? Math.min((xp / xp_max) * 100, 100) : 0;

    const xpFill = document.getElementById("xp-fill");
    const xpText = document.getElementById("xp-text");
    const levelText = document.getElementById("level-text");

    function animateXP(target) {
        let width = 0;
        const step = target / 60;

        function frame() {
            width += step;
            if (width >= target) width = target;
            xpFill.style.width = width + "%";
            if (width < target) requestAnimationFrame(frame);
        }

        requestAnimationFrame(frame);
    }

    animateXP(percent);
    xpText.innerText = `${xp} / ${xp_max} XP`;
    levelText.innerText = "Niveau " + level;

});
