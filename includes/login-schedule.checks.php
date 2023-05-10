<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  require_once('dbh.inc.php');

  $username = $_POST['username'];
  $password = $_POST['password'];
  $user_role = $_POST['user_role'];

  if($user_role == 'eleve')
  {
    $stmt = $conn->prepare("SELECT * FROM eleve WHERE (username = ? OR email = ?) AND password = ?");
    $stmt->bind_param("sss", $username, $username, $password);
    $stmt->execute();
  
    $result = $stmt->get_result();
  
    if ($result->num_rows === 1) 
    {
      $row = $result->fetch_assoc();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['first_name'] = $row['first_name'];
      $_SESSION['last_name'] = $row['last_name'];
      $_SESSION['class_id'] = $row['class_id'];
      $_SESSION['student_id'] =  $row['id'];
      $_SESSION['user_role'] = $user_role;
      $_SESSION['loggedin'] = true;

      header("Location: ../about.php?user=eleve");
    }
    else 
    {
      header("Location: ../index.php?error=invalid-info");
    }
  }


  else if($user_role == 'teacher' || $user_role == 'surveillant')
  {
    $stmt = $conn->prepare("SELECT * FROM surveillant_enseignant WHERE (username = ? OR email = ?) AND password = ? AND who_is = ?");
    $stmt->bind_param("ssss", $username, $username, $password, $user_role);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) 
    {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_role'] = $user_role;
    $_SESSION['loggedin'] = true;

    $dayOfWeek = date('l');
    $currentTime = date('H:i:s');
    $scheduleQuery = "SELECT eleve.first_name, eleve.last_name, eleve.cne, eleve.class_id, eleve.id FROM eleve 
                      INNER JOIN calendrier_enseignant ON eleve.class_id = calendrier_enseignant.class_id 
                      WHERE calendrier_enseignant.teacher_id = {$_SESSION['user_id']} 
                      AND calendrier_enseignant.day_of_week = '$dayOfWeek' 
                      AND '$currentTime' BETWEEN calendrier_enseignant.start_hour AND calendrier_enseignant.end_hour";

    $scheduleResult = $conn->query($scheduleQuery);


    $student_class = mysqli_query($conn, $scheduleQuery);
    $results = $student_class->fetch_assoc();
    $_SESSION['students_class_id'] = $results['class_id'];

    if ($scheduleResult->num_rows > 0) 
    {
      $students = $scheduleResult->fetch_all(MYSQLI_ASSOC);
      $_SESSION['students'] = $students;
      header("Location: ../about.php");
      exit();
    } 
    else 
    {
      header("Location: ../about.php?calendrier=not-found");
    }
    } 
    else 
    {
      header("Location: ../index.php?error=invalid-info");
    }
  } 
}
?>
