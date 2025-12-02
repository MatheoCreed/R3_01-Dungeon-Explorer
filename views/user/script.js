// Use data from PHP if provided via `window.USER_DATA`, otherwise fall back to defaults
const userData = window.USER_DATA || null;
let xp = userData ? Number(userData.xp) : 600;
let xp_max = userData ? Number(userData.xp_max) : 1000;
let level = userData ? Number(userData.level) : 5;

let percent = xp_max > 0 ? (xp / xp_max) * 100 : 0;

const xpFill = document.getElementById("xp-fill");
const xpText = document.getElementById("xp-text");
const levelText = document.getElementById("level-text");

if (xpFill) xpFill.style.width = percent + "%";
if (xpText) xpText.innerText = xp + " / " + xp_max + " XP";
if (levelText) levelText.innerText = "Niveau " + level;