<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) 
{
  header("location: index.php");
  exit;
}

// Connect to the database
require_once "includes/dbh.inc.php";

if($_SESSION['user_role'] == 'teacher' || $_SESSION['user_role'] == 'surveillant')
{
// Prepare a SELECT statement to retrieve the teacher's first and last name
$stmt = $conn->prepare("SELECT first_name, last_name FROM surveillant_enseignant WHERE id = ? AND who_is = ?");

// Bind the teacher_id from the logins table to the statement
$stmt->bind_param("is", $_SESSION['user_id'], $_SESSION['user_role']);

// Execute the statement
$stmt->execute();

// Bind the results to variables
$stmt->bind_result($first_name, $last_name);

// Fetch the results
$stmt->fetch();

// Close the statement
$stmt->close();

if (isset($_SESSION['students'])) 
{
  $students = $_SESSION['students'];
} 
else 
  {
    $students = array();
  }
}
else if ($_SESSION['user_role'] == 'eleve')
{
  $first_name = $_SESSION['first_name'];
  $last_name = $_SESSION['last_name'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>EduConnect - accueil</title>
</head>
<style>
body
{
    background-color: #e4eff8;
}
h2 
{
  text-align: center;
  margin: 0;
  opacity: 0;
  display: none;
}
.name_style
{
  color: #356ac7;
}
.makeinfo2
{
  display: flex;
  justify-content: center;
  align-items: center;
  height: 90vh;
}
.makeinfo1
{
  margin: 60px;
  margin-top: 80px;
  font-weight:500;
}

/* Absence table styles*/
.class_name
{
  text-align: center;
  margin-bottom: 19px;
  text-decoration: underline;
}
table td,table th 
{
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
}
tbody td 
{
  font-weight: 500;
  color: #999999;
}
.text-center
{
  margin: 10px 0px;
}
.btnt
{
  background-color: #356ac7;
  color: white;
}
.btnt:hover
{
  background-color: #386bc0;
  --bs-btn-hover-border-color: none;
}
</style>
<body>

<?php include 'header.php';?>

      <div>
        <h2>Bienvenue <span class="name_style"><?php echo " $first_name $last_name!";?></span></h2>
      </div>
<?php
     // we check if user logged in is a teacher
     if($_SESSION['user_role'] == 'teacher')
        {
              $teacher_id = $_SESSION['user_id'];
              $class_id = $_SESSION['students_class_id'];
              $current_date = date('d/m/Y');
              $absence_checked = mysqli_query($conn, "SELECT * FROM absence_details WHERE class_id = '$class_id' AND absence_day = '$current_date' AND teacher_id = '$teacher_id' ");

              if(mysqli_num_rows($absence_checked) >= 1)
              {
                $absence_checked = true;
              }
              else
              {
                $absence_checked = false;
              }

              if (isset($_SESSION['students']) && !isset($_GET['insert']) && $absence_checked === false) 
                {
                  echo '<p class="makeinfo1">Enregistrez l\'absence de la classe que tu enseignes maintenant s\'il vous plaît:</p>';
                  echo '
                  <section class="intro">
                   <div class="gradient-custom-1 h-100">
                   <h6 class="class_name">Tableau d\'absence de la classe '.$class_id.' pour le '.$current_date.' </h6>
                    <div class="mask d-flex align-items-center h-100">
                      <div class="container">
                        <div class="row justify-content-center">
                          <div class="col-12">
                            <div class="table-responsive bg-white">
                            <form method="POST" action="includes/absence.inc.php">
                              <table class="table mb-0">
                                <thead>
                                  <tr>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">CNE</th>
                                    <th scope="col" title="Écrivez simplement 1 devant l\'élève absent">Absent?</th>
                                  </tr>
                                </thead>
                                <tbody>';
                          foreach ($students as $student) 
                                {
                                 echo '<tr>';
                                   echo '<td>' . $student['first_name'] . '</td>';
                                   echo '<td>' . $student['last_name'] . '</td>';
                                   echo '<td>' . $student['cne'] . '</td>';
                                   echo '<td><input type="number" min="0" max="1" name='.$student['id'].'></td>';
                                  echo '</tr>';
                                }
                                echo '</tbody>';
                              echo '</table>';
                            
                            echo '</div>';
                          echo '</div>';
                        echo '</div>';
                      echo '</div>';
                    echo '</div>';
                   echo '</div>';
                   echo '<div class="text-center">';
                   echo '<button type="submit" class="btn btnt btn-outline-primary me-2">Enregistrer</button>';
                   echo '</div>';
                    echo '</form>';
                  echo '</section>';
                             
                }
                else if(isset($_GET['insert']))
                {
                echo '<h6 class="makeinfo2">Vous avez effectué l\'absence de cette classe avec succès!</h6>';
                }
                else
                {
                  echo '<h6 class="makeinfo2">Actuellement, vous n\'avez aucune tâche à faire!</h6>';
                }
         }
         // we check if user logged in is a surveillant
         else if($_SESSION['user_role'] == 'surveillant')
         {
          // Get today's and yesterday's dates in the "d/m/Y" format
          $today = date('d/m/Y');
          $yesterday = date('d/m/Y', strtotime('-1 day'));

          $host = 'localhost';
          $dbname = 'educconnect';
          $username = 'root';
          $password = '';
          
          // Connect to the database
          try {
              $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
              $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          } catch(PDOException $e) {
              die("Database connection failed: " . $e->getMessage());
          }

          // Retrieve images from the database
          $stmt = $db->query("SELECT id, name, student_id FROM absence_verification WHERE verification_date = '$yesterday' OR verification_date = '$today'");
          $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $show_justif = false; // Assume no justifications are available initially

          if (!empty($images)) 
          {
            foreach ($images as $image) 
            {
              $student_id = $image['student_id'];

              // Retrieve corresponding permissions from the database
              $stmt_permis = $db->query("SELECT * FROM feuilles_entree WHERE student_id = '$student_id' AND (date = '$yesterday' OR date = '$today');");
              $permis = $stmt_permis->fetchAll(PDO::FETCH_ASSOC);
        
              if(empty($permis)) 
              {
                $show_justif = true;
              } 
              else 
              {            
                // Prepare and execute the SQL query to check if the uploaded the verification today or yesterday
                $stmt1 = $conn->prepare("SELECT * FROM absence_verification WHERE student_id = ? AND (verification_date = ? OR verification_date = ?)");
                $stmt1->bind_param("iss", $student_id, $today, $yesterday);
                $stmt1->execute();
                $result = $stmt1->get_result();
                $rowCount = $result->num_rows; 
            
                // Prepare and execute the SQL query to check if the uploaded got refused
                $stmt11 = $conn->prepare("SELECT * FROM feuilles_entree WHERE student_id = ? AND (date = ? OR date = ?)");
                $stmt11->bind_param("iss", $student_id, $today, $yesterday);
                $stmt11->execute();
                $result1 = $stmt11->get_result();
                $rowCount1 = $result1->num_rows;

                if(($rowCount1 > $rowCount) || ($rowCount1 == $rowCount))
                {
                  $status = 'accept';            
                  // Retrieve corresponding permissions from the database           
                  $stmt_permis1 = $db->query("SELECT * FROM feuilles_entree WHERE student_id = '$student_id' AND (date = '$yesterday' OR date = '$today') AND statu = '$status';");
                  $permis1 = $stmt_permis1->fetchAll(PDO::FETCH_ASSOC);
              
                  if(empty($permis1))
                  {
                    // No permissions found for the student, set $show_justif to true
                    $show_justif = true;
                  }
                  else
                  {
                    // Permissions found, remove the image details           
                    unset($image['id']);            
                    unset($image['name']);         
                    unset($image['student_id']);
                  }
                }
              }
            }
          } 
          else
          {
            // No images found, set $show_justif to false
            $show_justif = false;
          }
          // No images reset, set $show_justif to false
          if (empty($images)) 
          {
            $show_justif = false;
          }
          
          if($show_justif)
          {
            echo '<h6 class="makeinfo2">Merci d\'avoir accédé a votre page de&nbsp<a href="justificatifs.php">justificatifs</a>&nbsppour voir les derniers téléchargements d\'étudiants!</h6>';
          }
          else
          {
            echo '<h6 class="makeinfo2">Actuellement, vous n\'avez aucune tâche à faire!</h6>';
          }
         }
         // we check if user logged in is a student
         else if($_SESSION['user_role'] == 'eleve')
         {
          // Get today's and yesterday's dates in the "d/m/Y" format
          $today = date('d/m/Y');
          $yesterday = date('d/m/Y', strtotime('-1 day'));
          
          // Prepare and execute the SQL query to check if the student was absent today or yesterda
          $stmt = $conn->prepare("SELECT absence_hours FROM absence_details WHERE absence_day = ? OR absence_day = ? AND student_id = ?");
          $stmt->bind_param("ssi", $today, $yesterday, $_SESSION['user_id']);
          $stmt->execute();
          $stmt->bind_result($absent);
          $stmt->fetch();
          $stmt->close();  

          // Prepare and execute the SQL query to check if the uploaded the verification today or yesterday
          $stmt1 = $conn->prepare("SELECT * FROM absence_verification WHERE student_id = ? AND (verification_date = ? OR verification_date = ?)");
          $stmt1->bind_param("iss", $_SESSION['user_id'], $today, $yesterday);
          $stmt1->execute();
          $result = $stmt1->get_result();
          $rowCount = $result->num_rows;

          // Prepare and execute the SQL query to check if the uploaded got refused
          $stmt11 = $conn->prepare("SELECT * FROM feuilles_entree WHERE student_id = ? AND (date = ? OR date = ?) AND statu = ?");
          $status = "accept";
          $stmt11->bind_param("isss", $_SESSION['user_id'], $today, $yesterday, $status);
          $stmt11->execute();
          $result1 = $stmt11->get_result();
          $rowCount1 = $result1->num_rows;

          if($absent > 0 && $rowCount1 == 0 && $rowCount == 0)
          {
            echo '<h6 class="makeinfo2">Merci d\'avoir accédé a votre page de&nbsp<a href="justification.php">vérification d\'absences</a>&nbsppour de verifier votre dernière absence!</h6>';
          }
          else
          {
            // Prepare and execute the SQL query to check if the uploaded the verification today or yesterday
            $stmt1 = $conn->prepare("SELECT * FROM feuilles_entree WHERE student_id = ? AND (date = ? OR date = ?) AND statu = ?");
            $status = "accept";
            $stmt1->bind_param("isss", $_SESSION['user_id'], $today, $yesterday, $status);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $rowCount = $result->num_rows;

            if($rowCount >= 1)
          {
            echo '<h6 class="makeinfo2"><a href="includes/permis_select.php?student_id='.$_SESSION['user_id'].'">Voir</a>&nbspvotre permis d\'entrée de votre dernière absence</h6>';
          }
          else
          {
            echo '<h6 class="makeinfo2">Actuellement, vous n\'avez aucune tâche à faire!</h6>';
          }
          }
         }
?>
       <script>
        $(document).ready(function() 
        {
          $("h2").slideDown(400, function() 
          {
            $(this).animate({ opacity: 1 }, 400);
          })
        });
      </script>
</body>

</html>