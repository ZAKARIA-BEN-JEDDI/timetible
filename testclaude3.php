<?php

function genererEmploiDuTemps($connexion, $id_classe, $heures_par_semaine) {
    // Jours de la semaine
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    
    // Plages horaires possibles
    $plages_horaires = [
        ['08:00', '10:00'],
        ['10:00', '12:00'],
        ['14:00', '16:00'],
        ['16:00', '18:00']
    ];
    
    // Récupérer les cours disponibles pour cette classe
    $stmt = $connexion->prepare("SELECT c.id_cours, c.id_enseignant FROM COURS c 
                                 JOIN CLASSE cl ON c.id_niveau = cl.id_niveau 
                                 WHERE cl.id_classe = ?");
    $stmt->execute([$id_classe]);
    $cours_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les salles disponibles
    $stmt = $connexion->prepare("SELECT DISTINCT salle FROM EMPLOI");
    $stmt->execute();
    $salles_disponibles = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Initialiser l'emploi du temps
    $emploi_du_temps = [];
    $heures_restantes = $heures_par_semaine;
    
    // Répartir les heures sur les jours de la semaine
    $heures_par_jour = array_fill(0, count($jours), floor($heures_par_semaine / count($jours)));
    $heures_restantes %= count($jours);
    for ($i = 0; $i < $heures_restantes; $i++) {
        $heures_par_jour[$i]++;
    }
    
    // Générer l'emploi du temps
    foreach ($jours as $index_jour => $jour) {
        $heures_du_jour = $heures_par_jour[$index_jour];
        $plages_du_jour = [];
        
        while ($heures_du_jour > 0) {
            $plage = $plages_horaires[array_rand($plages_horaires)];
            $cours = $cours_disponibles[array_rand($cours_disponibles)];
            $salle = $salles_disponibles[array_rand($salles_disponibles)];
            
            // Vérifier les conflits
            $conflit = false;
            foreach ($emploi_du_temps as $jour_existant => $plages_existantes) {
                foreach ($plages_existantes as $plage_existante) {
                    if ($plage_existante['heure_debut'] == $plage[0] && $plage_existante['heure_fin'] == $plage[1]) {
                        // Conflit de salle
                        if ($plage_existante['salle'] == $salle) {
                            $conflit = true;
                            break 2;
                        }
                        // Conflit d'enseignant
                        if ($plage_existante['id_enseignant'] == $cours['id_enseignant']) {
                            $conflit = true;
                            break 2;
                        }
                    }
                }
            }
            
            if (!$conflit) {
                $plages_du_jour[] = [
                    'id_cours' => $cours['id_cours'],
                    'id_enseignant' => $cours['id_enseignant'],
                    'heure_debut' => $plage[0],
                    'heure_fin' => $plage[1],
                    'salle' => $salle
                ];
                
                $heures_du_jour -= 2; // On considère que chaque cours dure 2 heures
            }
        }
        
        $emploi_du_temps[$jour] = $plages_du_jour;
    }
    
    // Insérer l'emploi du temps dans la base de données
    $stmt = $connexion->prepare("INSERT INTO EMPLOI (id_classe, id_cours, jour, heure_debut, heure_fin, salle) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($emploi_du_temps as $jour => $plages) {
        foreach ($plages as $plage) {
            $stmt->execute([
                $id_classe,
                $plage['id_cours'],
                $jour,
                $plage['heure_debut'],
                $plage['heure_fin'],
                $plage['salle']
            ]);
        }
    }
    
    return $emploi_du_temps;
}

function afficherEmploiDuTempsHTML($emploi_du_temps) {
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    $heures = ['08:00', '10:00', '14:00', '16:00'];
    
    $html = '<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">';
    
    // En-tête avec les jours
    $html .= '<tr><th></th>';
    foreach ($jours as $jour) {
        $html .= "<th>$jour</th>";
    }
    $html .= '</tr>';
    
    // Lignes pour chaque créneau horaire
    foreach ($heures as $heure) {
        $html .= "<tr><th>$heure</th>";
        foreach ($jours as $jour) {
            $html .= '<td>';
            foreach ($emploi_du_temps[$jour] ?? [] as $cours) {
                if ($cours['heure_debut'] == $heure) {
                    $html .= "Cours: {$cours['id_cours']}<br>";
                    $html .= "Salle: {$cours['salle']}";
                }
            }
            $html .= '</td>';
        }
        $html .= '</tr>';
    }
    
    $html .= '</table>';
    return $html;
}

// Utilisation de la fonction
try {
    $connexion = new PDO("mysql:host=localhost;dbname=gestion_ecole", "root", "");
    $emploi_du_temps = genererEmploiDuTemps($connexion, 1, 28); // Pour la classe avec id 1 et 28 heures par semaine
    // print_r($emploi_du_temps);
    echo afficherEmploiDuTempsHTML($emploi_du_temps);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>