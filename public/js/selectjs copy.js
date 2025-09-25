const activitesData = {
    automobile: [
        { value: "vtc", text: "VTC" },
        { value: "taxi", text: "Taxi" },
        { value: "transport_pro", text: "Transport professionnelle" },
        { value: "flotte_legere", text: "Flotte < 3t5" },
        { value: "flotte_lourde", text: "Flotte > 3t5" },
    ],
    personnel: [
        { value: "transport_pro", text: "Transport professionnelle" },
        { value: "sante", text: "Mutuelle santé" },
        { value: "retraite", text: "Retraite" },
    ],
};

const produitsData = {
    transport_pro: [
        {
            value: "https://transport-de-marchandise.fr/",
            text: "Transport de marchandise",
        },
        { value: "https://location-vehicuel.fr/", text: "Location véhicules" },
        { value: "https://negocie-marchandise.fr/", text: "Négociant" },
        { value: "https://poind-lourd.fr/", text: "Poids lourds" },
        { value: "https://compgne-car-transp.fr/", text: "Camping-car" },
        { value: "https://tracteur-de-routier.fr/", text: "Tracteur routier" },
        { value: "https://engin-remouque.fr/", text: "Semi-remorque" },
    ],
    retraite: [
        {
            value: "https://assurance-maintien-salaire.fr/",
            text: "Maintien salaire",
        },
        { value: "https://peri-simulation.fr/", text: "Retraite épargne" },
        { value: "https://tarif-mutuelle-senior.fr/", text: "Mutuelle santé" },
    ],
    sante: [
        {
            value: "https://assurance-maintien-jeune.fr/",
            text: "Mutuelle jeune",
        },
        {
            value: "https://peri-mutuelle-famille.fr/",
            text: "Mutuelle famille",
        },
        {
            value: "https://tarif-mutuelle-travaile.fr/",
            text: "Mutuelle travail",
        },
    ],
};

function updateSelectOptions(selectElement, options, defaultText) {
    // Détruire NiceSelect avant de modifier
    $(selectElement).niceSelect("destroy");

    // Vider le select
    selectElement.innerHTML = "";

    // Ajouter l'option par défaut
    const defaultOption = document.createElement("option");
    defaultOption.selected = true;
    defaultOption.disabled = true;
    defaultOption.textContent = defaultText;
    selectElement.appendChild(defaultOption);

    // Ajouter les nouvelles options
    options.forEach((option) => {
        const optionElement = document.createElement("option");
        optionElement.value = option.value;
        optionElement.textContent = option.text;
        selectElement.appendChild(optionElement);
    });

    // Recréer NiceSelect après modification
    $(selectElement).niceSelect();
}

document.addEventListener("DOMContentLoaded", function () {
    // Attendre que NiceSelect soit initialisé
    setTimeout(function () {
        const divAksam1 = document.getElementById("div-aksam1");

        if (divAksam1) {
            const assuranceSelect = divAksam1.querySelector(
                'select[name="assurance_type"]'
            );
            const activiteSelect = divAksam1.querySelector(
                'select[name="activite"]'
            );
            const produitSelect = divAksam1.querySelector(
                'select[name="produit"]'
            );
            const form = divAksam1.querySelector("form");

            // Utiliser jQuery pour les événements (compatible avec NiceSelect)
            $(assuranceSelect).on("change", function () {
                const selectedValue = this.value;

                // Réinitialiser le champ Produit
                $(produitSelect).niceSelect("destroy");
                produitSelect.innerHTML =
                    "<option selected disabled>Produit</option>";
                $(produitSelect).niceSelect();

                if (selectedValue === "automobile") {
                    updateSelectOptions(
                        activiteSelect,
                        activitesData.automobile,
                        "Activités"
                    );
                } else if (selectedValue === "personnel") {
                    updateSelectOptions(
                        activiteSelect,
                        activitesData.personnel,
                        "Activités"
                    );
                } else {
                    $(activiteSelect).niceSelect("destroy");
                    activiteSelect.innerHTML =
                        "<option selected disabled>Activités</option>";
                    $(activiteSelect).niceSelect();
                }
            });

            $(activiteSelect).on("change", function () {
                const selectedValue = this.value;

                if (selectedValue === "transport_pro") {
                    updateSelectOptions(
                        produitSelect,
                        produitsData.transport_pro,
                        "Produit"
                    );
                } else if (selectedValue === "retraite") {
                    updateSelectOptions(
                        produitSelect,
                        produitsData.retraite,
                        "Produit"
                    );
                } else if (selectedValue === "sante") {
                    updateSelectOptions(
                        produitSelect,
                        produitsData.sante,
                        "Produit"
                    );
                } else {
                    $(produitSelect).niceSelect("destroy");
                    produitSelect.innerHTML =
                        "<option selected disabled>Produit</option>";
                    $(produitSelect).niceSelect();
                }
            });

            // Gérer la soumission du formulaire
            $(form).on("submit", function (e) {
                e.preventDefault(); // Empêcher la soumission normale

                const produitValue = produitSelect.value;

                // Vérifier si la valeur est une URL
                if (produitValue && produitValue.startsWith("http")) {
                    // Rediriger vers l'URL externe
                    window.location.href = produitValue;
                } else {
                    // Sinon, soumettre le formulaire normalement
                    this.submit();
                }
            });
        }
    }, 500); // Attendre 500ms que NiceSelect soit initialisé
});
