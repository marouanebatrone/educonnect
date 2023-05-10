<?php
session_start();

  // Connect to the database
  require_once "dbh.inc.php";

  // Get the students from the session
  $students = $_SESSION['students'];

  // Loop through the students and insert the absence data
  foreach ($students as $student) 
  {
    $student_id = $student['id'];
    $student_class_id = $student['class_id'];
    $student_first_name = $student['first_name'];
    $student_last_name = $student['last_name'];
    $student_cne = $student['cne'];

    $stmt = $conn->prepare("SELECT class_name FROM classes WHERE id = ? ");
    $stmt->bind_param("i", $student_class_id);
    $stmt->execute();
    $stmt->bind_result($class_name);
    $stmt->fetch();
    $stmt->close();

    $current_day_name = date('l');

    if($_POST[$student_id])
    {
        $stmt1 = $conn->prepare("SELECT start_hour, end_hour FROM calendrier_enseignant WHERE class_id = ? AND day_of_week = ? AND teacher_id = ? ");
        $stmt1->bind_param("isi", $student_class_id, $current_day_name, $_SESSION['user_id']);
        $stmt1->execute();
        $stmt1->bind_result($start_hour, $end_hour);
        $stmt1->fetch();
        $stmt1->close();

        $absent = $end_hour - $start_hour;
        $absent1 = $end_hour - $start_hour;
    }
    else
    {
      $absent = 0;
      $absent1 = 0;
    }

    $currentMonth = date('F');

    $stmt21 = $conn->prepare("SELECT Nombre_heures FROM absence WHERE student_id = ? AND Mois = ?");
    $stmt21->bind_param("is", $student_id, $currentMonth);
    $stmt21->execute();
    $stmt21->bind_result($Nombre_heures);
    $stmt21->fetch();
    $stmt21->close();
    if($Nombre_heures === NULL)
    {
        // Prepare a statement to insert the absence data
        $stmt2 = $conn->prepare("INSERT INTO absence (student_id, Prénom, Nom, Cne, Classe, Nombre_heures, Mois) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // Bind the parameters to the statement
        $stmt2->bind_param("issssis", $student_id, $student_first_name, $student_last_name, $student_cne, $class_name, $absent, $currentMonth);
        // Execute the statement
        $stmt2->execute();
        // Close the statement
        $stmt2->close();
    }
    else if($Nombre_heures >= 0)
    {
        $absent += $Nombre_heures;
        $stmt2 = $conn->prepare("UPDATE absence SET Nombre_heures = $absent WHERE student_id = ? AND Mois = ?");
        // Bind the parameters to the statement
        $stmt2->bind_param("is", $student_id, $currentMonth);
        // Execute the statement
        $stmt2->execute();
        // Close the statement
        $stmt2->close();
    }
    

    $current_date = date('d/m/Y');
    // Prepare a statement to insert the absence data
    $stmt3 = $conn->prepare("INSERT INTO absence_details (teacher_id, student_id, class_id, absence_hours, month, absence_day) VALUES (?, ?, ?, ?, ?, ?)");
    // Bind the parameters to the statement
    $stmt3->bind_param("iiiiss", $_SESSION['user_id'], $student_id, $student_class_id, $absent1, $currentMonth, $current_date);
    // Execute the statement
    $stmt3->execute();
    // Close the statement
    $stmt3->close();

  }

  // Redirect to the homepage
  header("location: ../about.php?insert=success");
  exit;

?>
