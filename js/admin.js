// admin.js
document.addEventListener('DOMContentLoaded', () => {
  // Sélectionne tous les formulaires d'action dans le tableau
  document.querySelectorAll('.users-table form').forEach(form => {
    form.addEventListener('submit', evt => {
      evt.preventDefault();
      const btn    = form.querySelector('button[type="submit"]');
      const action = btn.value; // "toggle_vip" ou "toggle_ban"
      const row    = form.closest('tr');

      // Désactive le bouton et ajoute un style de chargement
      btn.disabled = true;
      btn.classList.add('loading');

      // Temporisation pour simuler l'attente
      setTimeout(() => {
        // On récupère le <span class="status"> de la colonne correspondante
        let statusSpan;
        if (action === 'toggle_vip') {
          statusSpan = row.querySelector('td:nth-child(3) .status');
        } else { // toggle_ban
          statusSpan = row.querySelector('td:nth-child(4) .status');
        }

        // On inverse la classe et le texte
        if (statusSpan) {
          const estOui = statusSpan.classList.contains('yes');
          statusSpan.classList.toggle('yes', !estOui);
          statusSpan.classList.toggle('no',  estOui);
          statusSpan.textContent = estOui ? 'Non' : 'Oui';
        }

        // Réactive le bouton et enlève le style de chargement
        btn.disabled = false;
        btn.classList.remove('loading');

        // Si vous aviez une action backend fonctionnelle,
        // c'est ici que vous pourriez appeler form.submit()
        // pour envoyer la vraie requête après le délai.

      }, 2000); // 2 secondes de simulation
    });
  });
});
