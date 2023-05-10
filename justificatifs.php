<?php
session_start();
// Database connection settings
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

// Get today's and yesterday's dates in the "d/m/Y" format
$today = date('d/m/Y');
$yesterday = date('d/m/Y', strtotime('-1 day'));

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
            // Connect to the database
            require_once "includes/dbh.inc.php";
            
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
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>EduConnect - justificatifs</title>
</head>
<style>
body
{
    background-color: #e4eff8;
}
.intro_phrase
{
    text-align: center;
    margin-bottom: 20px;
    margin-top: 40px;
}
.makeinfo2
{
  display: flex;
  justify-content: center;
  align-items: center;
  height: 90vh;
}
.display_two
{
  display: flex;
  margin-right:20px;
}
.btn1
{
  margin-right: 10px;
}

</style>
<body>
<?php include 'header.php';?>
<?php

if ($show_justif == false) 
{
    echo '<h6 class="makeinfo2">Actuellement, vous n\'avez aucune justificatif d\'absence téléchargée!</h6>';
}
else
{
         echo'
            <section class="intro">
            <div class="gradient-custom-1 h-100">
            <h6 class="intro_phrase">Voilà les derniers justificatifs d\'absences téléchargées par les élèves:</h6>
             <div class="mask d-flex align-items-center h-100">
               <div class="container">
                 <div class="row justify-content-center">
                   <div class="col-12">
                     <div class="table-responsive bg-white">
                       <table class="table mb-0">
                         <thead>
                           <tr>
                             <th scope="col">Prénom</th>
                             <th scope="col">Nom</th>
                             <th scope="col">Classe</th>
                             <th scope="col">Justificatifs</th>
                             <th scope="col">Votre réponse</th>
                           </tr>
                         </thead>
                         <tbody>';
                         foreach ($images as $image)
                         {  
                            include_once 'includes/dbh.inc.php';
                            $student_id = $image['student_id'];

                            $status = 'accept';            
                            // Retrieve corresponding permissions from the database           
                            $stmt_permis1 = $db->query("SELECT * FROM feuilles_entree WHERE student_id = '$student_id' AND (date = '$yesterday' OR date = '$today') AND statu = '$status';");
                            $permis1 = $stmt_permis1->fetchAll(PDO::FETCH_ASSOC);

                            if(empty($permis1)) 
                            {
                            //Selection of student data
                             $stmt1 = $conn->prepare("SELECT first_name, last_name, class_id FROM eleve WHERE id = ?");
                             $stmt1->bind_param("i", $student_id);
                             $stmt1->execute();
                             $stmt1->bind_result($first_name, $last_name, $class_id);
                             $stmt1->fetch();
                             $stmt1->close();   
                             echo '<tr>';
                             echo '<td>' . $first_name . '</td>';
                             echo '<td>' . $last_name . '</td>';
                             echo '<td>' . $class_id . '</td>';
                             echo '<td>' ?><a href="includes/justifs_select.php?id=<?php $image['id'] ?>"><?php echo $image['name']; ?></a><?php echo '</td>';
                             echo '<td>' . '<div class="display_two"><div><a href="includes/generate_permis.php?name=' . $first_name . ' ' . $last_name . '&student_id='.$student_id.'"><button type="button" class="btn btn1 btn-success">Accepter</button></a></div><div><a href="includes/generate_permis.php?id='.$image['id'].'&name='.$image['name'].'&student_id='.$student_id.'&reponse=refuse"><button type="button" class="btn btn-danger">Refuser</button></a></div></div>'.'</td>';
                             echo '</tr>';
                            }
                          }
                         echo '</tbody>';
                       echo '</table>';
                       echo '</div>';
                       echo '</div>';
                       echo '</div>';
                       echo '</div>';
                       echo '</div>';
                       echo '</div>';
                       echo '</section>';
}
                    ?>
</body>
</html>