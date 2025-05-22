// Sélectionnez le logo burger bouton et la barre de navigation verticale
const logoBurgerButton = document.getElementById("logo-burger-button");
const verticalNavbar = document.getElementById("vertical-navbar");

// Créez une variable pour suivre l'état de la barre de navigation
let isNavbarVisible = false;

// Fonction pour ouvrir la barre de navigation
function openNavbar() {
  verticalNavbar.style.display = "flex"; // Affiche la barre verticale
  isNavbarVisible = true;
}

// Fonction pour fermer la barre de navigation
function closeNavbar() {
  verticalNavbar.style.display = "none"; // Cache la barre verticale
  isNavbarVisible = false;
}

// Sélectionnez le bouton "Fermer"
const closeButton = document.getElementById("close-button");

// Ajoute un gestionnaire d'événement au bouton "Fermer"
closeButton.addEventListener("click", () => {
  closeNavbar();
});

// Ajoute un gestionnaire d'événement au logo burger bouton
logoBurgerButton.addEventListener("click", () => {
  if (!isNavbarVisible) {
    openNavbar();
  } else {
    closeNavbar();
  }
});

// Fonction pour cacher la barre de navigation verticale lorsque la largeur de l'écran est supérieure à 834px
function hideVerticalNavbarOnDesktop() {
  if (window.innerWidth > 834) {
    verticalNavbar.style.display = "none";
  }
}

// Appeler la fonction pour cacher la barre de navigation verticale au chargement de la page
hideVerticalNavbarOnDesktop();

// Écouter les changements de taille de l'écran et cacher la barre de navigation verticale si nécessaire
window.addEventListener("resize", hideVerticalNavbarOnDesktop);

// Envoi du message de manière asynchrone

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("contactForm");

  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Empêche le rechargement de la page

    const data = new FormData(form);

    fetch("/contact", {
      method: "POST",
      body: data,
      headers: {
        "X-Requested-With": "XMLHttpRequest", // indique que c'est une requête AJAX
      },
    })
      .then((res) => res.json())
      .then((res) => {
        const msg = document.getElementById("message-contact");
        msg.style.display = "block";
        msg.style.color = res.success ? "green" : "red";
        msg.textContent = res.message;
      })
      .catch(() => {
        const msg = document.getElementById("message-contact");
        msg.style.display = "block";
        msg.style.color = "red";
        msg.textContent = "Une erreur est survenue. Veuillez réessayer.";
      });
  });
});

// Fonction qui déclenche la bonne modale (3, 4 ou 5) selon la situation
function loadParticipationModal(covoiturageId) {
  fetch(`/covoiturage/participer/${covoiturageId}`)
    .then((response) => response.text())
    .then((html) => {
      // Injecte la modale reçue dans le conteneur
      document.getElementById("dynamicModalContainer").innerHTML = html;

      // Affiche automatiquement la modale avec Bootstrap
      const modalElement = document.querySelector(".modal");
      const modal = new bootstrap.Modal(modalElement);
      modal.show();

      // S'il y a un bouton de confirmation (modale 4), ajoute le listener
      const confirmBtn = document.getElementById("confirmParticipation");
      if (confirmBtn) {
        confirmBtn.addEventListener("click", () => {
          fetch(confirmBtn.dataset.url, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "_token=" + confirmBtn.dataset.token,
          })
            .then((response) => response.text())
            .then((html) => {
              // Remplace la modale 4 par la modale 5 (succès)
              document.getElementById("dynamicModalContainer").innerHTML = html;
              const successModal = new bootstrap.Modal(
                document.querySelector(".modal")
              );
              successModal.show();
            })
            .catch(() => {
              alert("Une erreur est survenue. Merci de réessayer.");
            });
        });
      }
    })
    .catch(() => {
      alert("Impossible de charger la modale. Vérifie ta connexion.");
    });
}

//bouton de connexion

document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.querySelector(".login-toggle");
  const loginMenu = document.querySelector(".login-menu");

  if (toggleBtn && loginMenu) {
    toggleBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      loginMenu.classList.toggle("active");
    });

    document.addEventListener("click", function (e) {
      if (!loginMenu.contains(e.target)) {
        loginMenu.classList.remove("active");
      }
    });
  }
});
