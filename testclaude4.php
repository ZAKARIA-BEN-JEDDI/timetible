<?php
    // class MultiClassScheduleGenerator {
    //     private $pdo;
    //     private $classes = [];
    //     private $matieres = [];
    //     private $professeurs = [];
    //     private $salles = [];
    //     private $maxDailyHours = 6;
        
    //     public function __construct() {
    //         try {
    //             $this->pdo = new PDO(
    //                 'mysql:host=localhost;dbname=emploi_du_temps_2acc;charset=utf8',
    //                 'root',
    //                 '',
    //                 [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    //             );
    //             $this->loadData();
    //         } catch (PDOException $e) {
    //             die("Connection failed: " . $e->getMessage());
    //         }
    //     }
        
    //     private function loadData() {
    //         $this->classes = $this->fetchData("SELECT * FROM classes ORDER BY nom");
    //         $this->matieres = $this->fetchData("SELECT * FROM matieres");
    //         $this->professeurs = $this->fetchData("SELECT * FROM professeurs");
    //         $this->salles = $this->fetchData("SELECT * FROM salles");
    //     }
        
    //     private function fetchData($query) {
    //         return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    //     }
        
    //     private function getDailyTimeSlots() {
    //         return [
    //             ['start' => '08:30', 'end' => '10:30', 'duration' => 120],
    //             ['start' => '10:45', 'end' => '12:30', 'duration' => 105],
    //             ['start' => '14:30', 'end' => '16:30', 'duration' => 120],
    //             ['start' => '16:45', 'end' => '18:30', 'duration' => 105]
    //         ];
    //     }
        
    //     private function getRandomItem($array) {
    //         return $array[array_rand($array)];
    //     }
        
    //     private function isTeacherAvailable($teacherId, $usedTeachers, $day, $timeSlot) {
    //         return !isset($usedTeachers[$day][$timeSlot]) || 
    //             !in_array($teacherId, $usedTeachers[$day][$timeSlot]);
    //     }
        
    //     private function isRoomAvailable($roomId, $usedRooms, $day, $timeSlot) {
    //         return !isset($usedRooms[$day][$timeSlot]) || 
    //             !in_array($roomId, $usedRooms[$day][$timeSlot]);
    //     }
        
    //     public function generateAllSchedules() {
    //         $allSchedules = [];
    //         $usedTeachers = [];
    //         $usedRooms = [];
    //         $timeSlots = $this->getDailyTimeSlots();
    //         $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
            
    //         foreach ($this->classes as $class) {
    //             $schedule = [];
    //             foreach ($days as $day) {
    //                 $dailyHours = 0;
    //                 $schedule[$day] = [];
                    
    //                 foreach ($timeSlots as $timeSlot) {
    //                     $timeSlotKey = $timeSlot['start'] . '-' . $timeSlot['end'];
                        
    //                     // Check if adding this slot would exceed 6 hours
    //                     // Vérifiez si l'ajout de ce créneau dépasserait 6 heures
    //                     if (($dailyHours + ($timeSlot['duration'] / 60)) > $this->maxDailyHours) {
    //                         $schedule[$day][$timeSlotKey] = null;
    //                         continue;
    //                     }
                        
    //                     // 80% chance of filling the slot
    //                     // 80% de chances de remplir le créneau
    //                     if (rand(1, 100) <= 80) {
    //                         // Find available professor and room
    //                         // Trouver un professeur et une salle disponibles
    //                         $availableProfessor = null;
    //                         $availableRoom = null;
                            
    //                         // Shuffle arrays to get random selections
    //                         // Mélangez les tableaux pour obtenir des sélections aléatoires
    //                         $shuffledProfessors = $this->professeurs;
    //                         shuffle($shuffledProfessors);
                            
    //                         foreach ($shuffledProfessors as $prof) {
    //                             if ($this->isTeacherAvailable($prof['id'], $usedTeachers, $day, $timeSlotKey)) {
    //                                 $availableProfessor = $prof;
    //                                 break;
    //                             }
    //                         }
                            
    //                         if ($availableProfessor) {
    //                             $shuffledRooms = $this->salles;
    //                             shuffle($shuffledRooms);
                                
    //                             foreach ($shuffledRooms as $room) {
    //                                 if ($this->isRoomAvailable($room['id'], $usedRooms, $day, $timeSlotKey)) {
    //                                     $availableRoom = $room;
    //                                     break;
    //                                 }
    //                             }
                                
    //                             if ($availableRoom) {
    //                                 $matiere = $this->getRandomItem($this->matieres);
                                    
    //                                 // Record the usage
    //                                 $usedTeachers[$day][$timeSlotKey][] = $availableProfessor['id'];
    //                                 $usedRooms[$day][$timeSlotKey][] = $availableRoom['id'];
                                    
    //                                 $schedule[$day][$timeSlotKey] = [
    //                                     'matiere' => $matiere['nom'],
    //                                     'professeur' => $availableProfessor['nom'],
    //                                     'salle' => $availableRoom['nom'],
    //                                     'duration' => $timeSlot['duration']
    //                                 ];
                                    
    //                                 $dailyHours += $timeSlot['duration'] / 60;
    //                             } else {
    //                                 $schedule[$day][$timeSlotKey] = null;
    //                             }
    //                         } else {
    //                             $schedule[$day][$timeSlotKey] = null;
    //                         }
    //                     } else {
    //                         $schedule[$day][$timeSlotKey] = null;
    //                     }
    //                 }
    //             }
    //             $allSchedules[$class['id']] = [
    //                 'class_name' => $class['nom'],
    //                 'schedule' => $schedule
    //             ];
    //         }
    //         return $allSchedules;
    //     }
    // }

    // Initialize and generate schedules
    // $generator = new MultiClassScheduleGenerator();
    // $allSchedules = $generator->generateAllSchedules();
?>

<!-- test  -->

<?php
    class MultiClassScheduleGenerator {
        private $pdo;
        private $classes = [];
        private $matieres = [];
        private $professeurs = [];
        private $professeursTimetable = [];
        private $salles = [];
        private $maxDailyHours = 6;
        
        public function __construct() {
            try {
                $this->pdo = new PDO(
                    'mysql:host=localhost;dbname=emploi_du_temps_2acc;charset=utf8',
                    'root',
                    '',
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                $this->loadData();
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        
        private function loadData() {
            $this->classes = $this->fetchData("SELECT * FROM classes ORDER BY nom");
            $this->matieres = $this->fetchData("SELECT * FROM matieres");
            $this->professeurs = $this->fetchData("SELECT * FROM professeurs");
            $this->salles = $this->fetchData("SELECT * FROM salles");
            $this->loadTeachersTimetable();
        }
        
        private function loadTeachersTimetable() {
            $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
            $timeSlots = $this->getDailyTimeSlots();
            
            foreach ($this->professeurs as $prof) {
                $this->professeursTimetable[$prof['id']] = [];
                foreach ($days as $day) {
                    $this->professeursTimetable[$prof['id']][$day] = array_fill_keys(array_map(function($slot) {
                        return $slot['start'] . '-' . $slot['end'];
                    }, $timeSlots), null);
                }
            }
        }
        
        private function fetchData($query) {
            return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }
        
        private function getDailyTimeSlots() {
            return [
                ['start' => '08:30', 'end' => '10:30', 'duration' => 120],
                ['start' => '10:45', 'end' => '12:30', 'duration' => 105],
                ['start' => '14:30', 'end' => '16:30', 'duration' => 120],
                ['start' => '16:45', 'end' => '18:30', 'duration' => 105]
            ];
        }
        
        private function getRandomItem($array) {
            return $array[array_rand($array)];
        }
        
        private function isTeacherAvailable($teacherId, $day, $timeSlot) {
            return $this->professeursTimetable[$teacherId][$day][$timeSlot] === null;
        }
        
        private function isRoomAvailable($roomId, $usedRooms, $day, $timeSlot) {
            return !isset($usedRooms[$day][$timeSlot]) || 
                !in_array($roomId, $usedRooms[$day][$timeSlot]);
        }
        
        private function bookTeacher($teacherId, $day, $timeSlot) {
            $this->professeursTimetable[$teacherId][$day][$timeSlot] = true;
        }
        
        private function bookRoom($roomId, $usedRooms, $day, $timeSlot) {
            if (!isset($usedRooms[$day][$timeSlot])) {
                $usedRooms[$day][$timeSlot] = [];
            }
            $usedRooms[$day][$timeSlot][] = $roomId;
            return $usedRooms;
        }
        
        public function generateAllSchedules() {
            $allSchedules = [];
            $usedRooms = [];
            $timeSlots = $this->getDailyTimeSlots();
            $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
            
            foreach ($this->classes as $class) {
                $schedule = [];
                foreach ($days as $day) {
                    $dailyHours = 0;
                    $schedule[$day] = [];
                    
                    foreach ($timeSlots as $timeSlot) {
                        $timeSlotKey = $timeSlot['start'] . '-' . $timeSlot['end'];
                        
                        // Check if adding this slot would exceed 6 hours
                        if (($dailyHours + ($timeSlot['duration'] / 60)) > $this->maxDailyHours) {
                            $schedule[$day][$timeSlotKey] = null;
                            continue;
                        }
                        
                        // 80% chance of filling the slot
                        if (rand(1, 100) <= 80) {
                            // Find available professor and room
                            $availableProfessor = null;
                            $availableRoom = null;
                            
                            // Shuffle arrays to get random selections
                            $shuffledProfessors = $this->professeurs;
                            shuffle($shuffledProfessors);
                            
                            foreach ($shuffledProfessors as $prof) {
                                if ($this->isTeacherAvailable($prof['id'], $day, $timeSlotKey)) {
                                    $availableProfessor = $prof;
                                    break;
                                }
                            }
                            
                            if ($availableProfessor) {
                                $shuffledRooms = $this->salles;
                                shuffle($shuffledRooms);
                                
                                foreach ($shuffledRooms as $room) {
                                    if ($this->isRoomAvailable($room['id'], $usedRooms, $day, $timeSlotKey)) {
                                        $availableRoom = $room;
                                        break;
                                    }
                                }
                                
                                if ($availableRoom) {
                                    $matiere = $this->getRandomItem($this->matieres);
                                    
                                    // Record the usage
                                    $this->bookTeacher($availableProfessor['id'], $day, $timeSlotKey);
                                    $usedRooms = $this->bookRoom($availableRoom['id'], $usedRooms, $day, $timeSlotKey);
                                    
                                    $schedule[$day][$timeSlotKey] = [
                                        'matiere' => $matiere['nom'],
                                        'professeur' => $availableProfessor['nom'],
                                        'salle' => $availableRoom['nom'],
                                        'duration' => $timeSlot['duration']
                                    ];
                                    
                                    $dailyHours += $timeSlot['duration'] / 60;
                                } else {
                                    $schedule[$day][$timeSlotKey] = null;
                                }
                            } else {
                                $schedule[$day][$timeSlotKey] = null;
                            }
                        } else {
                            $schedule[$day][$timeSlotKey] = null;
                        }
                    }
                }
                $allSchedules[$class['id']] = [
                    'class_name' => $class['nom'],
                    'schedule' => $schedule
                ];
            }
            return $allSchedules;
        }

        // Ajouter cette nouvelle méthode pour obtenir l'emploi du temps des professeurs
        public function getTeachersSchedule() {
            $teachersSchedule = [];
            
            foreach ($this->professeurs as $prof) {
                $teachersSchedule[$prof['id']] = [
                    'nom' => $prof['nom'],
                    'schedule' => $this->professeursTimetable[$prof['id']]
                ];
            }
            
            return $teachersSchedule;
        }
    }

    // // Initialize and generate schedules
    // $generator = new MultiClassScheduleGenerator();
    // $allSchedules = $generator->generateAllSchedules();
    $generator = new MultiClassScheduleGenerator();
    $allSchedules = $generator->generateAllSchedules();
    $teachersSchedule = $generator->getTeachersSchedule();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emplois du temps - Toutes les classes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .schedule-container {
            margin-bottom: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c5282;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            color: #4a5568;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            background-color: white;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #2c5282;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .time-slot {
            font-weight: bold;
            color: #2d3748;
            background-color: #edf2f7;
        }
        .class-info {
            margin-bottom: 5px;
        }
        .matiere {
            color: #2c5282;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .professeur {
            color: #805ad5;
            margin-bottom: 3px;
        }
        .salle {
            color: #38a169;
            font-style: italic;
        }
        .empty-slot {
            color: #a0aec0;
            font-style: italic;
        }
        .total-hours {
            text-align: right;
            margin-top: 10px;
            color: #2d3748;
            font-size: 0.9em;
        }
        @media print {
            .schedule-container {
                page-break-after: always;
            }
        }
    </style>
</head>
    <body>
    <div id="classes-section" class="schedule-section active">
    <?php foreach($allSchedules as $classId=>$data): ?>
        <div class="schedule-container">
            <h2>Classe: <?=htmlspecialchars($data['class_name'])?></h2>
            <table>
                <tr>
                    <th>Horaire</th>
                    <?php foreach(array_keys($data['schedule']) as $day): ?>
                        <th><?=$day?></th>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $timeSlots = array_keys(reset($data['schedule']));
                foreach($timeSlots as $timeSlot): 
                ?>
                <tr>
                    <td class="time-slot"><?=$timeSlot?></td>
                    <?php foreach($data['schedule'] as $day=>$slots): ?>
                        <td class="<?=empty($slots[$timeSlot])?'empty-slot':''?>">
                            <?php if(!empty($slots[$timeSlot])): ?>
                                <div class="class-info">
                                    <div class="matiere"><?=htmlspecialchars($slots[$timeSlot]['matiere'])?></div>
                                    <div class="professeur"><?=htmlspecialchars($slots[$timeSlot]['professeur'])?></div>
                                    <div class="salle"><?=htmlspecialchars($slots[$timeSlot]['salle'])?></div>
                                </div>
                            <?php else: ?>
                                Libre
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </table>
            <div class="total-hours">
                <?php 
                foreach($data['schedule'] as $day=>$slots) {
                    $totalHours = array_reduce($slots, function($carry, $slot) {
                        return $carry + ($slot ? $slot['duration']/60 : 0);
                    }, 0);
                    echo "$day: ".number_format($totalHours, 1)." heures<br>";
                }
                ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="teachers-section" class="schedule-section">
        <?php foreach ($teachersSchedule as $teacherId => $data): ?>
            <div class="schedule-container">
                <h2>Professeur: <?= htmlspecialchars($data['nom']) ?></h2>
                <table>
                    <tr>
                        <th>Horaire</th>
                        <?php foreach (array_keys($data['schedule']) as $day): ?>
                            <th><?= $day ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <?php 
                    $timeSlots = array_keys(reset($data['schedule']));
                    foreach ($timeSlots as $timeSlot): 
                    ?>
                    <tr>
                        <td class="time-slot"><?= $timeSlot ?></td>
                        <?php foreach ($data['schedule'] as $day => $slots): ?>
                            <td class="<?= empty($slots[$timeSlot]) ? 'empty-slot' : '' ?>">
                                <?php if (!empty($slots[$timeSlot])): ?>
                                    <div class="class-info">
                                        <div class="status">Occupé</div>
                                    </div>
                                <?php else: ?>
                                    Disponible
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endforeach; ?>
    </div>

        <script>
            function showSection(sectionName) {
                // Cacher toutes les sections
                document.querySelectorAll('.schedule-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                // Désactiver tous les onglets
                document.querySelectorAll('.nav-tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Afficher la section sélectionnée
                document.getElementById(sectionName + '-section').classList.add('active');
                
                // Activer l'onglet sélectionné
                event.target.classList.add('active');
            }
        </script>
    </body>
</html>