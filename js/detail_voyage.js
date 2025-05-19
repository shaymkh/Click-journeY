document.addEventListener('DOMContentLoaded', async () => {
  const id = new URLSearchParams(location.search).get('id');
  const container = document.getElementById('options-container');
  const prixSpan = document.getElementById('prix-estime');
  const nbInput = document.getElementById('nb-personnes');
  const loader = document.getElementById('calcul-loader');

  if (!id || !container) return;

  const res = await fetch(`get_options.php?id=${id}`);
  const data = await res.json();

  // Génération dynamique des options
  data.etapes.forEach((etape, i) => {
    const fieldset = document.createElement('fieldset');
    const legend = document.createElement('legend');
    legend.textContent = etape.titre;
    fieldset.appendChild(legend);

    for (const optName in etape.options) {
      const label = document.createElement('label');
      label.textContent = optName;

      const select = document.createElement('select');
      select.name = `options[${i}][${optName}]`;
      select.dataset.etape = i;
      select.dataset.nom = optName;

      for (const val in etape.options[optName]) {
        const option = document.createElement('option');
        option.value = val;
        option.textContent = `${val} (+${etape.options[optName][val]} €)`;

        if (etape.options_defaut?.[optName] === val) {
          option.selected = true;
        }

        select.appendChild(option);
      }

      label.appendChild(select);
      fieldset.appendChild(label);
    }

    container.appendChild(fieldset);
  });

  // Bind les événements *après* génération dynamique
  container.addEventListener('change', updateTotal);
  nbInput.addEventListener('input', updateTotal);

  // Lancer un premier calcul
  updateTotal();

  function getSelections() {
    const selects = container.querySelectorAll('select');
    const options = {};

    selects.forEach(sel => {
      const i = sel.dataset.etape;
      const name = sel.dataset.nom;
      const value = sel.value;
      if (!options[i]) options[i] = {};
      options[i][name] = value;
    });

    return options;
  }

  async function updateTotal() {
    const nb = parseInt(nbInput.value || '1', 10);
    const options = getSelections();

    loader.style.display = 'inline';

    try {
      const response = await fetch('calc_total.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, options, nb_personnes: nb })
      });

      const data = await response.json();
      prixSpan.textContent = data.total ?? 'Erreur';
    } catch (e) {
      prixSpan.textContent = 'Erreur';
    } finally {
      loader.style.display = 'none';
    }
  }
});
