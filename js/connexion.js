document.addEventListener("DOMContentLoaded", function () {
  const formulaire = document.querySelector("form");

  formulaire.addEventListener("submit", function (evenement) {
    evenement.preventDefault(); // Empêche l'envoi du formulaire par défaut

    // Supprimer les anciens messages d'erreur
    document.querySelectorAll(".message-erreur").forEach(element => element.remove());

    let formulaireValide = true;

    // Expressions régulières pour validation
    const expressionEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Fonction pour afficher un message d'erreur
    function afficherMessageErreur(champ, message) {
      const messageErreur = document.createElement("div");
      messageErreur.className = "message-erreur";
      messageErreur.style.color = "red";
      messageErreur.style.fontSize = "0.9em";
      messageErreur.textContent = message;
      champ.parentElement.appendChild(messageErreur);
      formulaireValide = false;
    }

    // Récupération des champs
    const champs = {
      email: document.getElementById("email"),
      motDePasse: document.getElementById("motdepasse")
    };

    // Vérification que tous les champs sont remplis
    for (let champ in champs) {
      if (champs[champ].value.trim() === "") {
        afficherMessageErreur(champs[champ], "Ce champ est obligatoire.");
      }
    }

    // Validation du courriel (si le champ contient un @, alors c'est un email)
    if (champs.telephone.value.includes('@') && !expressionEmail.test(champs.email.value.trim())) {
      afficherMessageErreur(champs.telephone, "Adresse email invalide.");
    }

   

    // Validation du mot de passe
    if (champs.motDePasse.value.length < 2) {
      afficherMessageErreur(champs.motDePasse, "Le mot de passe doit contenir au moins 2 caractères.");
    }

    // Si tout est correct, soumettre le formulaire
    if (formulaireValide) {
      formulaire.submit();
    }
  });
});

// Gestion du mot de passe (affichage/masquage)
document.addEventListener("DOMContentLoaded", function () {
  const champMotDePasse = document.getElementById("motdepasse");
  const iconeOeil = document.getElementById("togglemotdepasse");

  // Lorsque l'on clique sur l'icône, on bascule le mot de passe entre visible et masqué
  iconeOeil.addEventListener("click", function () {
    // Récupérer le type actuel du champ de mot de passe
    const typeActuel = champMotDePasse.getAttribute("type");
    
    // Si le type est "password", on le change en "text", sinon on le remet en "password"
    const nouveauType = typeActuel === "password" ? "text" : "password";
    champMotDePasse.setAttribute("type", nouveauType);

    // Bascule de l'icône entre "œil ouvert" et "œil barré"
    iconeOeil.classList.toggle("fa-eye-slash");  // Oeil barré
    iconeOeil.classList.toggle("fa-eye");        // Oeil ouvert
  });
});
