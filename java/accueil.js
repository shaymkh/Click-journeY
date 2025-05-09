const validThemes = ['clair', 'sombre', 'contrast', 'malvoyant'];

// Fonction pour définir un cookie
function setCookie(name, value, days) {
  const expires = new Date(Date.now() + days * 864e5).toUTCString();
  document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
}

// Fonction pour lire un cookie
function getCookie(name) {
  return document.cookie
    .split('; ')
    .find(row => row.startsWith(name + '='))
    ?.split('=')[1];
}

// Appliquer un thème et le sauvegarder dans le cookie
function setTheme(theme) {
  if (!validThemes.includes(theme)) {
    theme = 'clair'; // Valeur par défaut
  }

  document.body.className = 'theme-' + theme;
  setCookie('selectedTheme', theme, 30); // Expire dans 30 jours
}

// Appliquer le thème au chargement
window.addEventListener('DOMContentLoaded', () => {
  const theme = getCookie('selectedTheme');
  if (validThemes.includes(theme)) {
    setTheme(theme);
  } else {
    setTheme('clair'); // Thème par défaut
  }
