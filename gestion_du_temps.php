<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "gestion_emploi_temps";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Récupérer toutes les classes
$sqlClasses = "SELECT DISTINCT nom FROM classes";
$resultClasses = $conn->query($sqlClasses);

$emploisDuTemps = [];

if ($resultClasses->num_rows > 0) {
    while($rowClass = $resultClasses->fetch_assoc()) {
        $classe = $rowClass['nom'];
        // Récupérer l'emploi du temps pour cette classe
        $sql = "
        SELECT 
            m.nom AS matiere,
            h.debut AS horaire,
            j.nom AS jour,
            e.nom AS enseignant
        FROM emploi_du_temps et
        JOIN matieres m ON et.matiere_id = m.id
        JOIN horaires h ON et.horaire_id = h.id
        JOIN jours j ON et.jour_id = j.id
        JOIN enseignants e ON et.enseignant_id = e.id
        WHERE et.classe_id = (SELECT id FROM classes WHERE nom = '$classe')
        ORDER BY j.nom, h.debut
        ";

        $result = $conn->query($sql);
        $emploisDuTemps[$classe] = [];

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $jours = $row['jour'];
                $horaire = $row['horaire'];
                $matiere = "{$row['matiere']}<br>({$row['enseignant']})";
                $emploisDuTemps[$classe][$jours][$horaire] = $matiere;
            }
        } else {
            echo "Aucun cours trouvé pour la classe $classe<br>";
        }
    }
} else {
    echo "Aucune classe trouvée dans la base de données.<br>";
}

$conn->close();

// Afficher l'emploi du temps
function afficherEmploiDuTemps($emploisDuTemps) {
    echo "<h1>EMPLOI DU TEMPS</h1>";
    foreach ($emploisDuTemps as $classe => $jours) {
        echo "<h2>$classe</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse:collapse; width:100%;'>";
        
        // En-tête des heures
        echo "<tr><th>Jour</th><th>1</th><th>2</th><th>3</th><th>PAUSE</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th></tr>";
        
        // Jours de la semaine
        $joursDeLaSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

        foreach ($joursDeLaSemaine as $jour) {
            echo "<tr><td>$jour</td>";
            for ($i = 1; $i <= 9; $i++) {
                if (isset($jours[$jour][$i])) {
                    echo "<td>" . $jours[$jour][$i] . "</td>";
                } elseif ($i == 4) { // Colonne de pause
                    echo "<td>PAUSE</td>";
                } else {
                    echo "<td>Aucun cours</td>";
                }
            }
            echo "</tr>";
        }
        
        echo "</table><br>";
    }
}

afficherEmploiDuTemps($emploisDuTemps);
?>