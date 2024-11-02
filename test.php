<?php
// Définir l'emploi du temps pour les différentes classes
$emploisDuTemps = [
    "Littéraire - Première" => [
        "Lundi" => [
            "8:30 - 9:30" => "M. AHMED (Salle 101)",
            "9:30 - 10:30" => "M. BOUAZZA (Salle 102)",
            "10:45 - 11:30" => "Mme. LEGRAND (Salle 103)",
            "11:30 - 12:30" => "M. DUBOIS (Salle 104)",
            "14:30 - 15:30" => "Mme. MARTIN (Salle 105)",
            "15:30 - 16:30" => "M. ROUX (Salle 106)",
            "16:45 - 17:30" => "Mme. MOREAU (Salle 107)",
            "17:30 - 18:30" => "M. PETIT (Salle 108)",
        ],
        "Mardi" => [
            "8:30 - 9:30" => "M. GARCIA (Salle 101)",
            "9:30 - 10:30" => "Mme. LEROY (Salle 102)",
            "10:45 - 11:30" => "M. MARTIN (Salle 103)",
            "11:30 - 12:30" => "M. DUBOIS (Salle 104)",
            "14:30 - 15:30" => "Mme. MARTIN (Salle 105)",
            "15:30 - 16:30" => "M. ROUX (Salle 106)",
            "16:45 - 17:30" => "Mme. MOREAU (Salle 107)",
            "17:30 - 18:30" => "M. PETIT (Salle 108)",
        ],
        "Mercredi" => [
            "8:30 - 9:30" => "M. GARCIA (Salle 101)",
            "9:30 - 10:30" => "Mme. LEROY (Salle 102)",
            // Ajoutez d'autres horaires si nécessaire
        ],
        "Jeudi" => [
            "8:30 - 9:30" => "M. AHMED (Salle 109)",
            "9:30 - 10:30" => "M. BOUAZZA (Salle 110)",
            // Ajoutez d'autres horaires si nécessaire
        ],
        "Vendredi" => [
            "8:30 - 9:30" => "M. GARCIA (Salle 111)",
            "9:30 - 10:30" => "Mme. LEROY (Salle 112)",
            // Ajoutez d'autres horaires si nécessaire
        ],
    ],
    "Scientifique - Deuxième" => [
        "Lundi" => [
            "8:30 - 9:30" => "Mme. SMITH (Salle 201)",
            "9:30 - 10:30" => "M. JOHNSON (Salle 202)",
            "10:45 - 11:30" => "Mme. MARTIN (Salle 203)",
            "11:30 - 12:30" => "M. DUBOIS (Salle 204)",
            "14:30 - 15:30" => "Mme. LEROY (Salle 205)",
            "15:30 - 16:30" => "M. ROUX (Salle 206)",
            "16:45 - 17:30" => "Mme. MOREAU (Salle 207)",
            "17:30 - 18:30" => "M. PETIT (Salle 208)",
        ],
        "Mardi" => [
            "8:30 - 9:30" => "M. GARCIA (Salle 201)",
            "9:30 - 10:30" => "Mme. LEROY (Salle 202)",
            "10:45 - 11:30" => "M. MARTIN (Salle 203)",
            "11:30 - 12:30" => "M. DUBOIS (Salle 204)",
            "14:30 - 15:30" => "Mme. MARTIN (Salle 205)",
            "15:30 - 16:30" => "M. ROUX (Salle 206)",
            "16:45 - 17:30" => "Mme. MOREAU (Salle 207)",
            "17:30 - 18:30" => "M. PETIT (Salle 208)",
        ],
        "Mercredi" => [
            "8:30 - 9:30" => "M. GARCIA (Salle 201)",
            "9:30 - 10:30" => "Mme. LEROY (Salle 202)",
            // Ajoutez d'autres horaires si nécessaire
        ],
        "Jeudi" => [
            "8:30 - 9:30" => "M. AHMED (Salle 209)",
            "9:30 - 10:30" => "M. BOUAZZA (Salle 210)",
            // Ajoutez d'autres horaires si nécessaire
        ],
        "Vendredi" => [
            "8:30 - 9:30" => "M. GARCIA (Salle 211)",
            "9:30 - 10:30" => "Mme. LEROY (Salle 212)",
            // Ajoutez d'autres horaires si nécessaire
        ],
    ],
];

function afficherEmploiDuTemps($emploisDuTemps) {
    echo "<h1>EMPLOI DE TEMPS DES ÉTUDIANTS</h1>";
    foreach ($emploisDuTemps as $classe => $jours) {
        echo "<h2>$classe</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        
        // En-tête des jours
        echo "<tr><th>Horaires</th>";
        foreach (array_keys($jours) as $jour) {
            echo "<th>$jour</th>";
        }
        echo "</tr>";
        
        // Horaires et cours
        $horaires = [
            "8:30 - 9:30", "9:30 - 10:30", "10:45 - 11:30", 
            "11:30 - 12:30", "14:30 - 15:30", "15:30 - 16:30", 
            "16:45 - 17:30", "17:30 - 18:30"
        ];
        
        foreach ($horaires as $horaire) {
            echo "<tr><td>$horaire</td>";
            foreach ($jours as $jour => $cours) {
                $coursAffiche = isset($cours[$horaire]) ? $cours[$horaire] : '';
                echo "<td>$coursAffiche</td>";
            }
            echo "</tr>";
        }
        
        echo "</table><br>";
    }
}

// Afficher l'emploi du temps
afficherEmploiDuTemps($emploisDuTemps);
?>