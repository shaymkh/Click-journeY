// homepage.js
document.addEventListener('DOMContentLoaded', () => {
  const btn       = document.getElementById('theme');
  const linkTheme = document.getElementById('theme-css');

  if (!btn || !linkTheme) {
    console.error('Manque #theme ou #theme-css');
    return;
  }

  // Lit un cookie par nom
  function getCookie(name) {
    return document.cookie.split('; ').reduce((r, v) => {
      const [k, val] = v.split('=');
      return k === name ? decodeURIComponent(val) : r;
    }, '');
  }

  // Écrit un cookie (jours)
  function setCookie(name, value, days) {
    const d = new Date(Date.now() + days * 864e5);
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${d.toUTCString()}; path=/`;
  }

  // Applique le thème choisi
  function applyTheme(theme) {
    if (theme === 'dark') {
      linkTheme.href = 'sombre.css';
      btn.textContent = '☀️';
    } else {
      linkTheme.href = 'clair.css';
      btn.textContent = '🌙';
    }
  }

  // Au chargement, détermine le thème
  let theme = getCookie('theme');
  if (theme !== 'dark' && theme !== 'light') theme = 'light';
  applyTheme(theme);

  // Au clic, on inverse et on sauvegarde
  btn.addEventListener('click', () => {
    theme = theme === 'light' ? 'dark' : 'light';
    applyTheme(theme);
    setCookie('theme', theme, 365);
  });
});

