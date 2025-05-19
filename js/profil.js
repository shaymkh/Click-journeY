document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-profil');

  document.querySelectorAll('.champ-editable').forEach(div => {
    const input = div.querySelector('input');
    const editBtn = div.querySelector('.edit-btn');
    const validerBtn = div.querySelector('.valider-btn');
    const annulerBtn = div.querySelector('.annuler-btn');

    let ancienneValeur = input.value;

    editBtn.addEventListener('click', () => {
      input.disabled = false;
      ancienneValeur = input.value;
      validerBtn.style.display = 'inline-block';
      annulerBtn.style.display = 'inline-block';
      editBtn.style.display = 'none';
    });

    validerBtn.addEventListener('click', async () => {
      input.disabled = true;
      validerBtn.style.display = 'none';
      annulerBtn.style.display = 'none';
      editBtn.style.display = 'inline-block';

      const formData = new FormData(form);
      const response = await fetch('update_profile.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.success) {
        alert('Modification enregistrée');
      } else {
        input.value = ancienneValeur;
        alert('Erreur : ' + data.message);
      }
    });

    annulerBtn.addEventListener('click', () => {
      input.value = ancienneValeur;
      input.disabled = true;
      validerBtn.style.display = 'none';
      annulerBtn.style.display = 'none';
      editBtn.style.display = 'inline-block';
    });
  });
});
