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

// ===============================================
// ========== Incrémentation MongoDB - Charts =====
// ===============================================

document.addEventListener("DOMContentLoaded", function () {
  // Vérification si la variable statistiquesData est bien injectée depuis Twig
  if (
    typeof statistiquesData !== "undefined" &&
    Array.isArray(statistiquesData)
  ) {
    // 🔄 Extraction des données JSON injectées depuis le contrôleur Symfony
    const donnees = statistiquesData;
    const labels = donnees.map((d) => d.date); // Dates (labels horizontaux)
    const trajets = donnees.map((d) => d.covoiturages); // Nombre de covoiturages
    const credits = donnees.map((d) => d.credits_gagnes); // Crédits gagnés

    // ===============================
    //  Graphique : Covoiturages / jour
    // ===============================
    const ctx1 = document.getElementById("graphCovoiturage");
    if (ctx1) {
      new Chart(ctx1, {
        type: "bar", // Diagramme en barres
        data: {
          labels: labels,
          datasets: [
            {
              label: "Covoiturages",
              data: trajets,
              backgroundColor: "rgba(0, 95, 115, 0.6)",
              borderColor: "#005f73",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true, // Commence à 0 sur l’axe vertical
              ticks: {
                precision: 0,
              },
            },
          },
        },
      });
    }

    // ===============================
    //  Graphique : Crédits gagnés / jour
    // ===============================
    const ctx2 = document.getElementById("graphCredits");
    if (ctx2) {
      new Chart(ctx2, {
        type: "line", // Courbe
        data: {
          labels: labels,
          datasets: [
            {
              label: "Crédits gagnés",
              data: credits,
              backgroundColor: "rgba(0, 95, 73, 0.3)",
              borderColor: "#005f73",
              fill: true,
              tension: 0.3,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0,
              },
            },
          },
        },
      });
    }
  } else {
    // ⚠️ En cas d'erreur : statistiquesData non définie ou vide
    console.warn("⚠️ statistiquesData est vide ou non défini");
  }
});
