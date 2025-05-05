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

// Sélection du bouton de déclenchement et du formulaire
const adminToggle = document.getElementById("admin");
const loginForm = document.querySelector(".login-form");

// Affiche ou cache le formulaire lors du clic
adminToggle.addEventListener("click", function (event) {
  event.preventDefault();

  // Si le formulaire est visible, on le cache, sinon on l'affiche
  if (loginForm.style.display === "block") {
    loginForm.style.display = "none";
  } else {
    loginForm.style.display = "block";
  }
});
