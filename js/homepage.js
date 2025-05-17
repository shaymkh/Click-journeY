// Changement de thème clair/sombre
const boutonTheme = document.getElementById('theme');
boutonTheme.addEventListener('click', () => {
  document.documentElement.toggleAttribute('data-theme', 'dark');
  // 🌙 pour sombre, ☀️ pour clair
  boutonTheme.textContent = document.documentElement.hasAttribute('data-theme')
    ? '🌙'
    : '☀️';
});




