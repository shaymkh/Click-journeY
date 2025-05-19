// login.js
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".login-form");
  if (!form) return;

  // Validation à la soumission
  form.addEventListener("submit", evt => {
    evt.preventDefault();
    // Supprime d’abord d’anciens messages
    form.querySelectorAll(".message-erreur").forEach(el => el.remove());
    let ok = true;

    // Champs
    const emailField = document.getElementById("email");
    const pwdField   = document.getElementById("password");

    const reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function showError(champ, msg) {
      const div = document.createElement("div");
      div.className = "message-erreur";
      div.style.color = "red";
      div.style.fontSize = "0.9em";
      div.textContent = msg;
      champ.parentElement.appendChild(div);
      ok = false;
    }

    // Email requis + format
    if (emailField.value.trim() === "") {
      showError(emailField, "Ce champ est obligatoire.");
    } else if (!reEmail.test(emailField.value.trim())) {
      showError(emailField, "Adresse e-mail invalide.");
    }

    // Mot de passe requis + longueur min
    if (pwdField.value === "") {
      showError(pwdField, "Ce champ est obligatoire.");
    } else if (pwdField.value.length < 2) {
      showError(pwdField, "Le mot de passe doit contenir au moins 2 caractères.");
    }

    if (ok) form.submit();
  });

  // Bascule visibilité mot de passe
  const toggle = document.getElementById("togglePassword");
  if (toggle && pwdField) {
    toggle.addEventListener("click", () => {
      const type = pwdField.getAttribute("type") === "password" ? "text" : "password";
      pwdField.setAttribute("type", type);
      toggle.classList.toggle("fa-eye-slash");
      toggle.classList.toggle("fa-eye");
    });
  }
});
