document.addEventListener('DOMContentLoaded', () => {
    let modifValidee = false;

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

        validerBtn.addEventListener('click', () => {
            input.disabled = true;
            validerBtn.style.display = 'none';
            annulerBtn.style.display = 'none';
            editBtn.style.display = 'inline-block';
            modifValidee = true;
            document.getElementById('form-actions').style.display = 'block';
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
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-profil');
    form.addEventListener('submit', (e) => {
        if (!modifValidee) {
            e.preventDefault();
            alert("Vous devez valider au moins une modification avant d'envoyer le formulaire.");
        }
    });
});
