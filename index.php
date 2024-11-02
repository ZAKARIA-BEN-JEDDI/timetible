<?php
// Connexion à la base de données
$host = 'localhost';  
$dbname = 'ecole_college3';  
$username = 'root';  
$password = '';  

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


function obtenirEmploiDuTemps($pdo, $id_classe=1) {
    $requete = " SELECT matieres.nom_matiere, enseignants.nom_enseignant, salles.nom_salle, jours_semaine.nom_jour, seances.heure_debut, seances.heure_fin
            FROM emploi_du_temps
            JOIN cours ON emploi_du_temps.id_cours = cours.id_cours
            JOIN matieres ON cours.id_matiere = matieres.id_matiere
            JOIN enseignants ON cours.id_enseignant = enseignants.id_enseignant
            JOIN salles ON emploi_du_temps.id_salle = salles.id_salle
            JOIN jours_semaine ON emploi_du_temps.id_jour = jours_semaine.id_jour
            JOIN seances ON emploi_du_temps.id_seance = seances.id_seance
            WHERE emploi_du_temps.id_classe = :id_classe
            ORDER BY jours_semaine.id_jour, seances.heure_debut
    ";
    
    $stmt = $pdo->prepare($requete);
    $stmt->execute(['id_classe' => $id_classe]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function afficherTableauEmploiDuTemps($pdo, $id_classe, $nom_classe) {
    $emploi_du_temps = obtenirEmploiDuTemps($pdo, $id_classe);
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    $seances = [
        ['08:30', '10:30'],
        ['10:45', '12:30'],
        ['14:30', '16:30'],
        ['16:45', '18:30']
    ];

    echo "<h2>Emploi du temps pour la classe : $nom_classe</h2>";
    echo "<table border='1' cellpadding='10'>";
    
    // Afficher les jours de la semaine comme en-tête
    echo "<tr><th>Heures</th>";
    foreach ($jours as $jour) {
        echo "<th>$jour</th>";
    }
    echo "</tr>";
    
    // Remplir le tableau avec les séances
    foreach ($seances as $seance) {
        echo "<tr>";
        echo "<td>{$seance[0]} - {$seance[1]}</td>";  // Heure de début et fin de la séance
        
        foreach ($jours as $jour) {
            $cours_trouve = false;
            foreach ($emploi_du_temps as $cours) {
                if ($cours['heure_debut'] == $seance[0] && $cours['nom_jour'] == $jour) {
                    echo "<td>
                        <b>Matière :</b> {$cours['nom_matiere']}<br>
                        <b>Prof :</b> {$cours['nom_enseignant']}<br>
                        <b>Salle :</b> {$cours['nom_salle']}
                    </td>";
                    $cours_trouve = true;
                    break;
                }
            }
            if (!$cours_trouve) {
                echo "<td>---</td>";  // Si aucune séance n'est prévue pour ce créneau horaire
            }
        }
        echo "</tr>";
    }
    
    echo "</table><br><br>";
}

// Récupérer et afficher l'emploi du temps de chaque classe
function afficherClasses($pdo) {
    $requete = "SELECT id_classe, nom_classe FROM classes";
    $stmt = $pdo->query($requete);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    echo "<h1>Gestion des emplois du temps</h1>";

    if ($classes) {
        foreach ($classes as $classe) {
            afficherTableauEmploiDuTemps($pdo, $classe['id_classe'], $classe['nom_classe']);
        }
    } else {
        echo "<p>Aucune classe trouvée.</p>";
    }
}



// Appel pour afficher les emplois du temps
afficherClasses($pdo);
?>
