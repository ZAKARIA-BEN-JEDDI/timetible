<?php
class ScheduleGenerator {
    private PDO $pdo;
    private array $timeSlots = [
        1 => '08:30 - 10:30',
        2 => '10:45 - 12:30',
        3 => '14:30 - 16:30',
        4 => '16:45 - 18:30'
    ];
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                'mysql:host=localhost;dbname=emploi_du_temps_2acc;charset=utf8',
                'root',
                '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function generateSchedule() {
        // Clear existing schedule
        $this->pdo->exec("DELETE FROM cours");

        // Get all available resources
        $classes = $this->fetchAll("SELECT * FROM classes");
        $professeurs = $this->fetchAll("SELECT * FROM professeurs");
        $matieres = $this->fetchAll("SELECT * FROM matieres");
        $salles = $this->fetchAll("SELECT * FROM salles");
        
        $days = ['Lu', 'Ma', 'Me', 'Je', 'Ve'];
        
        foreach ($classes as $class) {
            foreach ($days as $day) {
                foreach ($this->timeSlots as $hour => $time) {
                    // 80% chance to schedule a class
                    if (rand(1, 100) <= 80) {
                        $professor = $this->getRandomItem($professeurs);
                        $matiere = $this->getRandomItem($matieres);
                        $salle = $this->getRandomItem($salles);
                        
                        // Check if slot is available
                        if ($this->isSlotAvailable($class['id'], $professor['id'], $salle['id'], $day, $hour)) {
                            $this->scheduleClass($class['id'], $professor['id'], $matiere['id'], $salle['id'], $day, $hour);
                        }
                    }
                }
            }
        }
    }

    private function isSlotAvailable($classId, $profId, $salleId, $day, $hour) {
        // Check if class already has a course at this time
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM cours 
            WHERE (classe_id = ? OR professeur_id = ? OR salle_id = ?)
            AND jour = ? AND heure = ?
        ");
        $stmt->execute([$classId, $profId, $salleId, $day, $hour]);
        return $stmt->fetchColumn() == 0;
    }

    private function scheduleClass($classId, $profId, $matiereId, $salleId, $day, $hour) {
        $stmt = $this->pdo->prepare("
            INSERT INTO cours (classe_id, professeur_id, matiere_id, salle_id, jour, heure)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$classId, $profId, $matiereId, $salleId, $day, $hour]);
    }

    private function fetchAll($query) {
        return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRandomItem($array) {
        return $array[array_rand($array)];
    }

    // Method to calculate and validate study hours per class
    private function validateClassHours() {
      $classes = $this->fetchAll("SELECT id, nom FROM classes");
      foreach ($classes as $class) {
          $stmt = $this->pdo->prepare("
              SELECT COUNT(*) FROM cours WHERE classe_id = ?
          ");
          $stmt->execute([$class['id']]);
          $totalSessions = $stmt->fetchColumn();
          $totalHours = $totalSessions * 2; // Each session is 2 hours

          if ($totalHours < 22 || $totalHours > 26) {
              echo "Warning: Class " . $class['nom'] . " has $totalHours hours scheduled, which is out of the 22-26 hour range.<br>";
          }
      }
  }

      // Method to calculate and validate teaching hours per teacher
      private function validateTeacherHours() {
          $teachers = $this->fetchAll("SELECT id, nom FROM professeurs");
          foreach ($teachers as $teacher) {
              $stmt = $this->pdo->prepare("
                  SELECT COUNT(*) FROM cours WHERE professeur_id = ?
              ");
              $stmt->execute([$teacher['id']]);
              $totalSessions = $stmt->fetchColumn();
              $totalHours = $totalSessions * 2; // Each session is 2 hours

              if ($totalHours > 30) {
                  echo "Warning: Teacher " . $teacher['nom'] . " has $totalHours hours scheduled, exceeding the 30-hour limit.<br>";
              }
          }
      }
}

// Display class
class ScheduleDisplay {
    private PDO $pdo;
    private array $timeSlots = [
        1 => '08:30 - 10:30',
        2 => '10:45 - 12:30',
        3 => '14:30 - 16:30',
        4 => '16:45 - 18:30'
    ];

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function displayAllSchedules() {
        $classes = $this->pdo->query("SELECT * FROM classes ORDER BY nom")->fetchAll();
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Emplois du temps</title>
            <style>
                body { font-family: Arial; margin: 20px; }
                .schedule { margin-bottom: 40px; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #4CAF50; color: white; }
                .time-slot { font-weight: bold; }
                .course-info { padding: 5px; }
                .matiere { color: #2196F3; font-weight: bold; }
                .professeur { color: #4CAF50; }
                .salle { color: #f44336; }
            </style>
        </head>
        <body>
            <h1>Emplois du temps - Toutes les classes</h1>
            <?php foreach ($classes as $class): ?>
                <div class="schedule">
                    <h2>Classe: <?= htmlspecialchars($class['nom']) ?></h2>
                    <?= $this->generateScheduleTable($class['id']) ?>
                </div>
            <?php endforeach; ?>
        </body>
        </html>
        <?php
    }

    private function generateScheduleTable($classId) {
        $days = ['Lu', 'Ma', 'Me', 'Je', 'Ve'];
        $html = '<table>';
        $html .= '<tr><th>Horaire</th>';
        foreach ($days as $day) {
            $html .= "<th>$day</th>";
        }
        $html .= '</tr>';

        foreach ($this->timeSlots as $slot => $time) {
            $html .= "<tr><td class='time-slot'>$time</td>";
            foreach ($days as $day) {
                $html .= $this->getScheduleCell($classId, $day, $slot);
            }
            $html .= '</tr>';
        }
        return $html . '</table>';
    }

    private function getScheduleCell($classId, $day, $hour) {
        $stmt = $this->pdo->prepare("
            SELECT m.nom as matiere, p.nom as professeur, s.nom as salle
            FROM cours c
            JOIN matieres m ON m.id = c.matiere_id
            JOIN professeurs p ON p.id = c.professeur_id
            JOIN salles s ON s.id = c.salle_id
            WHERE c.classe_id = ? AND c.jour = ? AND c.heure = ?
        ");
        $stmt->execute([$classId, $day, $hour]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            return "<td>
                <div class='course-info'>
                    <div class='matiere'>" . htmlspecialchars($course['matiere']) . "</div>
                    <div class='professeur'>" . htmlspecialchars($course['professeur']) . "</div>
                    <div class='salle'>" . htmlspecialchars($course['salle']) . "</div>
                </div>
            </td>";
        }
        return "<td>Libre</td>";
    }
}

// Usage
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=emploi_du_temps_2acc;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Generate new schedule
    $generator = new ScheduleGenerator();
    $generator->generateSchedule();

    // Display schedules
    $display = new ScheduleDisplay($pdo);
    $display->displayAllSchedules();

} catch (Exception $e) {
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
}