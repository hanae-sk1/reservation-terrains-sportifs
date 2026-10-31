<?php
require __DIR__ . "/includes/config.php";

$sql    = "SELECT * FROM reservations ORDER BY date_res ASC, heure_debut ASC";
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("Erreur de requête : " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des réservations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="conteneur">
        <h1>Liste des réservations</h1>

        <?php if (mysqli_num_rows($result) === 0) { ?>
            <p>Aucune réservation n'est enregistrée pour le moment.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Terrain</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Durée</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo (int) $row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["nom"]); ?></td>
                            <td><?php echo htmlspecialchars($row["telephone"]); ?></td>
                            <td><?php echo htmlspecialchars($row["terrain"]); ?></td>
                            <td><?php echo htmlspecialchars($row["date_res"]); ?></td>
                            <td><?php echo htmlspecialchars(substr($row["heure_debut"], 0, 5)); ?></td>
                            <td><?php echo (int) $row["duree"]; ?> h</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <p><a href="reservation.html">Nouvelle réservation</a></p>
    </div>
</body>
</html>
<?php
mysqli_free_result($result);
mysqli_close($conn);
