<?php
$pdo = new PDO('mysql:host=localhost;dbname=emploi_du_temps_2acc', 'root', '');

function getClasses($pdo) {
    $stmt = $pdo->query("SELECT nom FROM classes ORDER BY nom");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// function getCours($pdo, $classe_nom) {
//     $stmt = $pdo->prepare("
//         SELECT c.jour, c.heure, m.nom AS matiere, p.nom AS professeur, s.nom AS salle
//         FROM cours c
//         JOIN classes cl ON c.classe_id = cl.id
//         JOIN professeurs p ON c.professeur_id = p.id
//         JOIN matieres m ON c.matiere_id = m.id
//         JOIN salles s ON c.salle_id = s.id
//         WHERE cl.nom = :classe_nom
//         ORDER BY c.jour, c.heure
//     ");
//     $stmt->execute(['classe_nom' => $classe_nom]);
//     $cours = [];
//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         $cours[$row['jour']][$row['heure']] = $row;
//     }
//     return $cours;
// }
function getCours($pdo, $classe_nom) {
    $stmt = $pdo->prepare("
        SELECT c.jour, c.heure, m.nom AS matiere, p.nom AS professeur, s.nom AS salle
        FROM cours c
        JOIN classes cl ON c.classe_id = cl.id
        JOIN professeurs p ON c.professeur_id = p.id
        JOIN matieres m ON c.matiere_id = m.id
        JOIN salles s ON c.salle_id = s.id
        WHERE cl.nom = :classe_nom
        ORDER BY c.jour, c.heure
    ");
    $stmt->execute(['classe_nom' => $classe_nom]);
    $cours = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cours[$row['jour']][$row['heure']] = $row;
    }

    // Ajouter des séances pour combler les heures manquantes
    $jours = ['Lu', 'Ma', 'Me', 'Je', 'Ve'];
    foreach ($jours as $jour) {
        for ($h = 6; $h <= 8; $h++) {  // Créneaux de 15h30 à 17h30
            if (!isset($cours[$jour][$h])) {
                $cours[$jour][$h] = [
                    'matiere' => 'Activité libre',  // Remplissage temporaire
                    'professeur' => 'Non assigné',
                    'salle' => 'A définir',
                ];
            }
        }
    }

    return $cours;
}
function verifierHeuresTotales($cours) {
    // Total d'heures attendu
    $heuresParSemaine = 35;
    $heuresCumulees = 0;

    foreach ($cours as $jour => $seances) {
        $heuresCumulees += count($seances);
    }

    if ($heuresCumulees !== $heuresParSemaine) {
        throw new Exception("Erreur : la classe n'a pas exactement 35 heures de cours par semaine, elle a $heuresCumulees heures.");
    }
}

function verifierHeuresParJour($cours) {
    // Heures attendues par jour
    $heuresAttendueParJour = [
        'Lu' => 8,
        'Ma' => 6,
        'Me' => 8,
        'Je' => 6,
        'Ve' => 7,
    ];

    foreach ($heuresAttendueParJour as $jour => $heuresAttendues) {
        if (isset($cours[$jour])) {
            $heuresEffectives = count($cours[$jour]);
            if ($heuresEffectives != $heuresAttendues) {
                throw new Exception("Erreur : le jour $jour n'a pas exactement $heuresAttendues heures, il a $heuresEffectives heures.");
            }
        } else {
            throw new Exception("Erreur : le jour $jour n'a aucun cours assigné.");
        }
    }
}

function verifierCoursContinus($cours) {
    foreach ($cours as $jour => $seances) {
        $heuresCours = array_keys($seances);
        sort($heuresCours);
        for ($i = 1; $i < count($heuresCours); $i++) {
            if ($heuresCours[$i] - $heuresCours[$i - 1] != 1) {
                throw new Exception("Erreur : les cours ne sont pas continus pour le jour $jour.");
            }
        }
    }
}

$classes = getClasses($pdo);
$classe_selectionnee = isset($_GET['classe']) ? $_GET['classe'] : $classes[0];
$cours = getCours($pdo, $classe_selectionnee);

// Appliquer les vérifications
try {
    verifierHeuresTotales($cours);
    verifierHeuresParJour($cours);
    verifierCoursContinus($cours);
    echo "L'emploi du temps est valide.";
} catch (Exception $e) {
    echo $e->getMessage();
}

$jours = ['Lu', 'Ma', 'Me', 'Je', 'Ve'];
$heures = range(1, 8);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps</title>
</head>
<body>
    <h1>Emploi du temps</h1>
    <form method="get">
        Choisir une classe:
        <select name="classe" onchange="this.form.submit()">
            <?php foreach ($classes as $classe) : ?>
                <option value="<?= $classe ?>" <?= $classe === $classe_selectionnee ? 'selected' : '' ?>>
                    <?= $classe ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <table border="1">
        <tr>
            <th>Jour</th>
            <?php foreach ($heures as $heure) : ?>
                <th><?= ($heure+7 ) ?>:30 h</th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($jours as $jour) : ?>
            <tr>
                <td><?= $jour ?></td>
                <?php for ($h = 1; $h <= 8; $h++) : ?>
                    <td>
                        <?= isset($cours[$jour][$h]) ? $cours[$jour][$h]['matiere'] . ' (' . $cours[$jour][$h]['professeur'] . ')' : '' ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
