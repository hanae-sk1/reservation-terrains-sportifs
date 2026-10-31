<?php
require __DIR__ . "/includes/config.php";

// Affiche une petite page de résultat (succès ou erreurs)
function afficherPage($titre, $classe, $messages, $lienRetour, $texteRetour)
{
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titre); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="conteneur">
        <h1 class="<?php echo $classe; ?>"><?php echo htmlspecialchars($titre); ?></h1>
        <?php foreach ($messages as $message) { ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>
        <p><a href="<?php echo $lienRetour; ?>"><?php echo htmlspecialchars($texteRetour); ?></a></p>
    </div>
</body>
</html>
    <?php
    exit;
}

// On n'accepte que l'envoi du formulaire (pas un accès direct à l'URL)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: reservation.html");
    exit;
}

$nom       = trim($_POST["nom"] ?? "");
$telephone = trim($_POST["telephone"] ?? "");
$terrain   = trim($_POST["terrain"] ?? "");
$date      = trim($_POST["date"] ?? "");
$heure     = trim($_POST["heure"] ?? "");
$duree     = trim($_POST["duree"] ?? "");

$terrainsValides = ["tennis", "padel", "football", "basketball"];
$dureesValides   = ["1", "2", "3"];

$erreurs = [];

if ($nom === "" || $telephone === "" || $terrain === "" || $date === "" || $heure === "" || $duree === "") {
    $erreurs[] = "Veuillez remplir tous les champs.";
} else {
    if (mb_strlen($nom) > 100) {
        $erreurs[] = "Le nom est trop long (100 caractères maximum).";
    }

    if (!preg_match('/^[0-9]{10}$/', $telephone)) {
        $erreurs[] = "Le téléphone doit être composé de 10 chiffres.";
    }

    if (!in_array($terrain, $terrainsValides, true)) {
        $erreurs[] = "Type de terrain invalide.";
    }

    if (!in_array($duree, $dureesValides, true)) {
        $erreurs[] = "Durée invalide.";
    }

    // Validation de la date (format AAAA-MM-JJ et date réelle)
    $dateObj = DateTime::createFromFormat("Y-m-d", $date);
    if ($dateObj === false || $dateObj->format("Y-m-d") !== $date) {
        $erreurs[] = "Date invalide.";
        $dateObj = null;
    }

    // Validation de l'heure (format HH:MM)
    $heureObj = DateTime::createFromFormat("H:i", $heure);
    if ($heureObj === false || $heureObj->format("H:i") !== $heure) {
        $erreurs[] = "Heure invalide.";
        $heureObj = null;
    }

    // La réservation ne doit pas être dans le passé
    if ($dateObj !== null && $heureObj !== null) {
        $debut = new DateTime($date . " " . $heure);
        if ($debut < new DateTime()) {
            $erreurs[] = "La réservation ne peut pas être dans le passé.";
        }
    }
}

if (!empty($erreurs)) {
    afficherPage("Réservation refusée", "erreur", $erreurs, "reservation.html", "Retour au formulaire");
}

// Vérifier que le terrain est libre sur ce créneau (pas de chevauchement)
$dureeInt     = (int) $duree;
$debutSec     = ((int) $heureObj->format("H")) * 3600 + ((int) $heureObj->format("i")) * 60;
$finSec       = $debutSec + $dureeInt * 3600;

$sqlConflit = "SELECT COUNT(*) FROM reservations
               WHERE terrain = ? AND date_res = ?
               AND TIME_TO_SEC(heure_debut) < ?
               AND TIME_TO_SEC(heure_debut) + duree * 3600 > ?";
$stmt = mysqli_prepare($conn, $sqlConflit);
if ($stmt === false) {
    die("Erreur de préparation : " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ssii", $terrain, $date, $finSec, $debutSec);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nbConflits);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($nbConflits > 0) {
    afficherPage(
        "Créneau indisponible",
        "erreur",
        ["Ce terrain est déjà réservé sur ce créneau. Veuillez choisir un autre horaire."],
        "reservation.html",
        "Retour au formulaire"
    );
}

// Enregistrement
$sql  = "INSERT INTO reservations (nom, telephone, terrain, date_res, heure_debut, duree)
         VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    die("Erreur de préparation : " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "sssssi", $nom, $telephone, $terrain, $date, $heure, $dureeInt);

if (mysqli_stmt_execute($stmt) === false) {
    die("Erreur d'exécution : " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

afficherPage(
    "Réservation enregistrée",
    "succes",
    ["La réservation a été enregistrée avec succès !"],
    "liste.php",
    "Voir la liste"
);
