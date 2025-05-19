// form-validation.js
document.addEventListener("DOMContentLoaded", function () {
  const formulaire = document.querySelector("form");

  formulaire.addEventListener("submit", function (evenement) {
    evenement.preventDefault();
    document.querySelectorAll(".message-erreur").forEach(e => e.remove());
    let valide = true;

    const champs = {
      username: document.getElementById("username"),
      email:    document.getElementById("email"),
      password: document.getElementById("password"),
      confirm:  document.getElementById("confirm-password")
    };

    const reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function showError(champ, msg) {
      const div = document.createElement("div");
      div.className = "message-erreur";
      div.style.color = "red";
      div.textContent = msg;
      champ.parentElement.appendChild(div);
      valide = false;
    }

    // Champs obligatoires
    for (let key of ["username","email","password","confirm"]) {
      if (champs[key].value.trim()==="") {
        showError(champs[key], "Ce champ est obligatoire.");
      }
    }

    // Email
    if (champs.email.value.trim() !== "" && !reEmail.test(champs.email.value.trim())) {
      showError(champs.email, "Adresse email invalide.");
    }

    // Mot de passe min 2
    if (champs.password.value.length < 2) {
      showError(champs.password, "Le mot de passe doit contenir au moins 2 caractères.");
    }
    // Confirmation
    if (champs.password.value !== champs.confirm.value) {
      showError(champs.confirm, "Les mots de passe ne correspondent pas.");
    }

    if (valide) formulaire.submit();
  });

  // toggle visibilité mot de passe
  const mdp = document.getElementById("password");
  const toggle = document.getElementById("togglePassword");
  if (mdp && toggle) {
    toggle.addEventListener("click", () => {
      const type = mdp.getAttribute("type") === "password" ? "text" : "password";
      mdp.setAttribute("type", type);
      toggle.classList.toggle("fa-eye-slash");
      toggle.classList.toggle("fa-eye");
    });
  }
});

