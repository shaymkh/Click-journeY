document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  const selects = form.querySelectorAll('select');
  const nbInput = document.getElementById('nb-personnes');
  const prixBase = parseFloat(document.body.dataset.prixBase);
  const output = document.getElementById('prix-estime');

  function getSelectedOptionsTotal() {
    let total = 0;
    selects.forEach(select => {
      const selected = select.options[select.selectedIndex];
      const match = selected.textContent.match(/\+([\d,\s]+) €/);
      if (match) {
        total += parseFloat(match[1].replace(',', '.').replace(/\s/g, ''));
      }
    });
    return total;
  }

  function updatePrix() {
    const nb = parseInt(nbInput.value) || 1;
    const total = (prixBase + getSelectedOptionsTotal()) * nb;
    output.textContent = total.toFixed(2).replace('.', ',');
  }

  selects.forEach(select => select.addEventListener('change', updatePrix));
  nbInput.addEventListener('input', updatePrix);
  updatePrix();
});