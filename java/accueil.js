// Appliquer un thème et le sauvegarder
function setTheme(theme) {
  if (theme === 'reset') {
    document.body.className = 'theme-light'; // Thème par défaut
    localStorage.removeItem('selectedTheme');
  } else {
    document.body.className = 'theme-' + theme;
    localStorage.setItem('selectedTheme', theme);
  }
}

// Appliquer le thème sauvegardé au chargement
window.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('selectedTheme');
  if (savedTheme) {
    setTheme(savedTheme);
  }
});
