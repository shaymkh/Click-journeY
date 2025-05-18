// Fonction de basculement entre le thème clair et sombre, chagement du css du a cette regle
// le fichier devrait plutot s'appeler  interface.js 
function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute("data-theme");
  const newTheme = currentTheme === "dark" ? "light" : "dark";

  // sauvegarder le nouveau thème dans localStorage
  localStorage.setItem("theme", newTheme);

  // appliquer le thème à l'élément <html>
  document.documentElement.setAttribute("data-theme", newTheme);

  // mise a jour texte du bouton de changement de thème
  updateThemeButton();
}


function updateThemeButton() {
  const button = document.querySelector("[data-theme-toggle]");
  if (!button) return;

  const isDark = document.documentElement.getAttribute("data-theme") === "dark";
  const label = isDark ? "Thème Clair" : "Thème Sombre";
  button.setAttribute("aria-label", label);
  button.textContent = label;
}

// Appliquer le thème lors du chargement de la page
const theme = localStorage.getItem("theme");
if (theme) {
  document.documentElement.setAttribute("data-theme", theme);
} else {
  // Appliquer le thème clair par défaut
  document.documentElement.setAttribute("data-theme", "light");
}


document.addEventListener("DOMContentLoaded", () => {
  updateThemeButton(); // Mettre à jour le bouton dès que le DOM est chargé
  const button = document.querySelector("[data-theme-toggle]");
  if (button) {
    button.addEventListener("click", toggleTheme);
  }
});

  // Active le mode malvoyant depuis le localStorage au chargement
  if (localStorage.getItem("malvoyant") === "true") {
    document.documentElement.classList.add("malvoyant");
  }

  // Bouton pour activer/désactiver le mode malvoyant
  const btnMalvoyant = document.getElementById("btn-accessibilite");
  if (btnMalvoyant) {
    btnMalvoyant.addEventListener("click", () => {
      document.documentElement.classList.toggle("malvoyant");
      const isActive = document.documentElement.classList.contains("malvoyant");
      localStorage.setItem("malvoyant", isActive);
    });
  }
