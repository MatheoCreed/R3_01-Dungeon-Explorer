let xp = 600;     // XP actuelle
let xp_max = 1000; // XP max
let level = 5;   

let percent = (xp / xp_max) * 100;

document.getElementById("xp-fill").style.width = percent + "%";
document.getElementById("xp-text").innerText = xp + " / " + xp_max + " XP";
document.getElementById("level-text").innerText = "Niveau " + level;