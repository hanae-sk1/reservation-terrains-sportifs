const formulaire = document.getElementById("formReservation");
const champDate = document.getElementById("date");

// On empêche de choisir une date passée dans le calendrier
champDate.min = new Date().toLocaleDateString("en-CA"); // format AAAA-MM-JJ

formulaire.addEventListener("submit", function (event) {
    const nom = document.getElementById("nom").value.trim();
    const telephone = document.getElementById("telephone").value.trim();
    const terrain = document.getElementById("terrain").value;
    const date = champDate.value;
    const heure = document.getElementById("heure").value;
    const duree = document.getElementById("duree").value;

    const erreur = valider(nom, telephone, terrain, date, heure, duree);

    if (erreur !== "") {
        event.preventDefault(); // on bloque l'envoi seulement si le formulaire est invalide
        alert(erreur);
    }
    // sinon : le formulaire part normalement vers traitement.php
});

function valider(nom, telephone, terrain, date, heure, duree) {
    if (nom === "") {
        return "Votre nom complet ne doit pas être vide.";
    }

    if (!/^[0-9]{10}$/.test(telephone)) {
        return "Le téléphone doit contenir dix chiffres.";
    }

    if (terrain === "") {
        return "Veuillez choisir un type de terrain.";
    }

    if (date === "") {
        return "Veuillez choisir une date.";
    }

    const dateSelectionnee = new Date(date + "T00:00:00");
    const aujourdHui = new Date();
    aujourdHui.setHours(0, 0, 0, 0);

    if (dateSelectionnee < aujourdHui) {
        return "La date de réservation ne peut pas être antérieure à aujourd'hui.";
    }

    if (heure === "") {
        return "Veuillez choisir une heure de début.";
    }

    if (duree === "") {
        return "Veuillez choisir une durée.";
    }

    return "";
}
