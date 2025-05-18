document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-profil');
  const msg = document.getElementById('profil-msg');

  const inputFields = form.querySelectorAll('input');
  const editBtn = document.getElementById('edit-btn');
  const saveBtn = document.getElementById('save-btn');
  const cancelBtn = document.getElementById('cancel-btn');

  const originalValues = {};
  inputFields.forEach(input => {
    originalValues[input.name] = input.value;
    input.disabled = true;
  });

  editBtn.addEventListener('click', () => {
    inputFields.forEach(i => i.disabled = false);
    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-block';
    cancelBtn.style.display = 'inline-block';
  });

  cancelBtn.addEventListener('click', () => {
    inputFields.forEach(i => {
      i.value = originalValues[i.name];
      i.disabled = true;
    });
    msg.textContent = '';
    editBtn.style.display = 'inline-block';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const data = new FormData(form);
    fetch('update_profil.php', {
      method: 'POST',
      body: data
    })
    .then(res => res.json())
    .then(response => {
      msg.textContent = response.message;
      msg.style.color = response.success ? 'green' : 'red';

      if (response.success) {
        inputFields.forEach(i => {
          originalValues[i.name] = i.value;
          i.disabled = true;
        });
        editBtn.style.display = 'inline-block';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
      }
    })
    .catch(() => {
      msg.textContent = 'Erreur lors de la requête AJAX';
      msg.style.color = 'red';
    });
  });
});

