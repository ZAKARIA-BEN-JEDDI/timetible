<?php
session_start();

/**
 * Inclusion de la page de connexion à la base de données
 */
// require('connexion.php');

// CONNEXION À LA BASE DE DONNÉES
$servername = "localhost"; // Remplacez par votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe

$connexion = new mysqli($servername, $username, $password, "ecole_college4");

// Vérification de la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// TRAITEMENT POUR LA PAGE PERSONNALISÉE DU RECTEUR
$sqlH = "SELECT DISTINCT (horaire) FROM jour_horaire";
$horaires = $connexion->query($sqlH);
$horaire = $horaires->fetch_all(MYSQLI_ASSOC);

$sqlNiveau = "SELECT DISTINCT(niveau) FROM emploi_etudiant";
$niveaux = $connexion->query($sqlNiveau);
$niveaux = $niveaux->fetch_all(MYSQLI_ASSOC);

// Modification de la requête pour inclure une jointure
$sqlFiliere = "SELECT DISTINCT f.nom_filiere 
                FROM emploi_etudiant ee 
                JOIN filieres f ON ee.id_filiere = f.id";
$filieres = $connexion->query($sqlFiliere);
$filieres = $filieres->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des emplois de temps</title>
    <style>
        li a,
        th {
            text-transform: uppercase;
        }

        h1 {
            text-align: center;
        }

        .pull-right {
            margin-top: 3.8px;
            margin-right: 5px;
        }

        html {
            margin: 10px;
        }

        #printable {
            width: 100%;
            margin: auto;
        }

        @media print {
            .btn,
            caption {
                display: none;
            }
        }

        i {
            text-transform: uppercase;
        }

        i {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="row">
            <!-- EN-TETE DE NOTRE PAGE -->
            <?php 
							// include('utils/navS.php'); 
							?>
            </nav>
        </section>
    </div>

    <div class="row" id="printable">
        <h4 style="text-decoration:underline;text-align: center; font-family:'Times New Roman', Times, serif; font-weight: bold;font-size: xx-large;">EMPLOI DE TEMPS DES ÉTUDIANTS</h4>
        <?php
        if (count($filieres) > 0) {
            foreach ($filieres as $filiere) {
                $faculty = $filiere['nom_filiere'];
                if (count($niveaux) > 0) {
                    foreach ($niveaux as $niveau) {
                        $level = $niveau['niveau'];

                        // Préparation de la requête pour récupérer l'emploi du temps
                        // $stmt = $connexion->prepare("SELECT * FROM emploi_etudiant WHERE niveau = ? AND id = (SELECT id FROM filieres WHERE nom_filiere = ?) INNER JOIN filieres ON id_filiere = id JOIN salles ON id = id_salle  JOIN salles ON id = id_enseignant  ");
                        $stmt = $connexion->prepare("SELECT ee.*, f.nom_filiere, s.nom_salle, e.nom_enseignant 
                              FROM emploi_etudiant ee 
                              INNER JOIN filieres f ON ee.id_filiere = f.id 
                              INNER JOIN salles s ON ee.id_salle = s.id 
                              INNER JOIN enseignants e ON ee.id_enseignant = e.id 
                              WHERE ee.niveau = ? AND f.nom_filiere = ?");

												$stmt->bind_param("ss", $level, $faculty);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
        ?>
                            <section class="col-lg-12 table-responsive well">
                                <table class="table table-bordered table-striped table-condensed">
                                    <caption>
                                        <h4>EMPLOI DE TEMPS DES ÉTUDIANTS</h4>
                                        <blockquote style="text-transform: uppercase;"><?php echo $faculty . " - " . $level; ?></blockquote>
                                    </caption>
                                    <thead>
                                        <th class="success">Horaires</th>
                                        <th class="warning">Lundi</th>
                                        <th class="danger">Mardi</th>
                                        <th class="active">Mercredi</th>
                                        <th class="success">Jeudi</th>
                                        <th class="primary">Vendredi</th>
                                        <th class="info">Samedi</th>
                                        <th class="default">Dimanche</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        while ($rows = $result->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td class="success">
                                                    <?php
                                                    echo $horaire[$i]['horaire']; // Assurez-vous que 'horaire' est le bon champ
                                                    ?>
                                                </td>
                                                <td class="danger">
                                                    <?php if ($rows['jour'] == 'Lundi' || $rows['jour'] == 'Mardi' || $rows['jour'] == 'Mercredi' || $rows['jour'] == 'Jeudi' || $rows['jour'] == 'Vendredi' ) { ?>
                                                        <!-- <i><?php //echo $rows['code_ue']; ?></i><br> |
                                                        <i><?php //echo $rows['nom_salle']; ?></i> |<br>
                                                        <i><?php //echo $rows['nom_enseignant']; ?></i> -->
                                                        <i><?php echo $rows['nom_filiere']; ?></i><br>
                                                        <i><?php echo $rows['nom_enseignant']; ?></i><br>
                                                        <i><?php echo $rows['nom_salle']; ?></i>
                                                    <?php } ?>
                                                </td>
                                                <!-- Ajoutez d'autres jours ici selon votre logique -->
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </section>
        <?php
                        }
                        $stmt->close();
                    }
                }
            }
        }
        ?>
    </div>
    <div class="col-lg-6">
        <button onclick="actualiser()" class="btn btn-lg btn-success">REFRESH</button>
        <button class="btn btn-lg btn-warning pull-right" onclick="imprimer()">PRINT THIS PAGE</button>
    </div>

    <script>
        function actualiser() {
            window.location.reload(true);
        }

        function imprimer() {
            window.print();
        }
    </script>
</body>

</html>
