document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.btn-action').forEach(button => {
    button.addEventListener('click', async () => {
      const login = button.dataset.login;
      const type = button.dataset.type;

      const row = button.closest('tr');
      const loader = row.querySelector('.loader');
      const statusCell = row.querySelector(`td:nth-child(${type === 'vip' ? 3 : 4}) > .status`);

      loader.style.display = 'inline';
      button.disabled = true;

      try {
        const res = await fetch('admin_action.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `login=${encodeURIComponent(login)}&type=${encodeURIComponent(type)}`
        });

        const data = await res.json();
        if (data.success) {
          const newVal = data.newValue;
          statusCell.textContent = newVal ? 'Oui' : 'Non';
          statusCell.classList.remove('yes', 'no');
          statusCell.classList.add(newVal ? 'yes' : 'no');
        } else {
          alert('Erreur : ' + (data.error || 'inconnue'));
        }
      } catch (e) {
        alert('Erreur de requête');
      } finally {
        loader.style.display = 'none';
        button.disabled = false;
      }
    });
  });
});
