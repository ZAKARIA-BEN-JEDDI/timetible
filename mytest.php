<?php
$pdo = new PDO('mysql:host=localhost;dbname=emploi_du_temps_2acc', 'root', '');

//classe
$query = $pdo->query("SELECT DISTINCT nom FROM classes ORDER BY nom");
$classes = $query->fetchAll(PDO::FETCH_ASSOC);
$list_classe = [];
foreach ($classes as $classe) {
    $list_classe[] = $classe;
}

//matiere
$query = $pdo->query("SELECT DISTINCT nom FROM matieres ORDER BY nom");
$matieres = $query->fetchAll(PDO::FETCH_ASSOC);
$list_matieres = [];
foreach ($matieres as $matiere) {
    $list_matieres[] = $matiere;
}

//professeurs
$query = $pdo->query("SELECT DISTINCT nom FROM professeurs ORDER BY nom");
$professeurs = $query->fetchAll(PDO::FETCH_ASSOC);
$list_professeurs = [];
foreach ($professeurs as $professeur) {
    $list_professeurs[] = $professeur;
}

$nbr1 = rand(0,count($list_classe) - 1);
$nbr2 = rand(0,count($list_matieres) - 1);
$nbr3 = rand(0,count($list_professeurs) - 1);

// echo $nbr1;
// echo $list_classe[$nbr1]['nom'];
// echo $nbr2;
// echo $list_matieres[$nbr2]['nom'];
// echo $nbr3;
// echo $list_professeurs[$nbr3]['nom'];
$lundi = [
    '8:30-10:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '10:45-12:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '14:30-16:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '16:30-18:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
];
$mardi = [
    '8:30-10:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '10:45-12:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '14:30-16:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '16:30-18:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
];
$mercredi = [
    '8:30-10:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '10:45-12:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '14:30-16:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '16:30-18:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
];
$jeudi = [
    '8:30-10:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '10:45-12:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '14:30-16:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '16:30-18:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
];
$vendredi = [
    '8:30-10:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '10:45-12:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '14:30-16:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
    '16:30-18:30' => $list_classe[rand(0,count($list_classe) - 1)]['nom'] . ' <br> ' . $list_matieres[rand(0,count($list_matieres) - 1)]['nom'] . ' <br> ' . $list_professeurs[rand(0,count($list_professeurs) - 1)]['nom'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Emploi du temps</h1>
    <?php var_dump($list_professeurs)  ?>
    <br><br><br>
    <?php var_dump($lundi)  ?>
    <br><br><br><br><br><br><br><br><br>
    <table>
        <tr>
            <th></th>
            <th>8:30 - 10:30</th><th>10:45 - 12:30</th><th>14:30 - 16:30</th><th>16:45 - 18:30</th>
        </tr>
        <tr>
            <td>Lundi</td>
            <td><?= $lundi['8:30-10:30'] ?></td>
            <td><?= $lundi['10:45-12:30'] ?></td>
            <td><?= $lundi['14:30-16:30'] ?></td>
            <td><?= $lundi['16:30-18:30'] ?></td>
        </tr>
        <tr>
            <td>Mardi</td>
            <td><?= $mardi['8:30-10:30'] ?></td>
            <td><?= $mardi['10:45-12:30'] ?></td>
            <td><?= $mardi['14:30-16:30'] ?></td>
            <td><?= $mardi['16:30-18:30'] ?></td>
        </tr>
        <tr>
            <td>Mercredi</td>
            <td><?= $mercredi['8:30-10:30'] ?></td>
            <td><?= $mercredi['10:45-12:30'] ?></td>
            <td><?= $mercredi['14:30-16:30'] ?></td>
            <td><?= $mercredi['16:30-18:30'] ?></td>
        </tr>
        <tr>
            <td>Jeudi</td>
            <td><?= $jeudi['8:30-10:30'] ?></td>
            <td><?= $jeudi['10:45-12:30'] ?></td>
            <td><?= $jeudi['14:30-16:30'] ?></td>
            <td><?= $jeudi['16:30-18:30'] ?></td>
        </tr>
        <tr>
            <td>Vendredi</td>
            <td><?= $vendredi['8:30-10:30'] ?></td>
            <td><?= $vendredi['10:45-12:30'] ?></td>
            <td><?= $vendredi['14:30-16:30'] ?></td>
            <td><?= $vendredi['16:30-18:30'] ?></td>
        </tr>
    </table>
</body>
</html>