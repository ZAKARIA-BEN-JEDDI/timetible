<?php
$pdo = new PDO('mysql:host=localhost;dbname=emploi_du_temps_2acc', 'root', '');

function getClasses($pdo) {
    $stmt = $pdo->query("SELECT nom FROM classes ORDER BY nom");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

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
    return $cours;
}

$classes = getClasses($pdo);
$classe_selectionnee = isset($_GET['classe']) ? $_GET['classe'] : $classes[0];
$cours = getCours($pdo, $classe_selectionnee);
$jours = ['Lu', 'Ma', 'Me', 'Je', 'Ve'];
$heures = range(1, 11);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps <?= htmlspecialchars($classe_selectionnee) ?></title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Emploi du temps</h1>
    <form method="get">
        <label for="classe">Choisir une classe:</label>
        <select name="classe" id="classe" onchange="this.form.submit()">
            <?php foreach ($classes as $classe): ?>
                <option value="<?= htmlspecialchars($classe) ?>" <?= $classe === $classe_selectionnee ? 'selected' : '' ?>>
                    <?= htmlspecialchars($classe) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <h2><?= htmlspecialchars($classe_selectionnee) ?></h2>
    <table>
        <tr>
            <th></th>
            <?php foreach ($heures as $heure):
                if ($heure != 6) {?>
                    <th><?= ($heure+7 ) ?>:30 h</th>
            <?php } endforeach; ?>
        </tr>
        <?php foreach ($jours as $jour): ?>
            <tr>
                <th><?= $jour ?></th>
                <?php foreach ($heures as $heure): ?>
                    <td>
                        <?php if (isset($cours[$jour][$heure])): ?>
                            <strong><?= htmlspecialchars($cours[$jour][$heure]['matiere']) ?></strong><br>
                            <?= htmlspecialchars($cours[$jour][$heure]['professeur']) ?><br>
                            <em><?= htmlspecialchars($cours[$jour][$heure]['salle']) ?></em>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>