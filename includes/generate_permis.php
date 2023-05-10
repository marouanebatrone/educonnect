<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// Insert the PDF file into the database table
$dbHost = 'localhost';
$dbName = 'educconnect';
$dbUser = 'root';
$dbPass = '';


if (isset($_GET['name'])) 
{
    // Get the name from the URL parameter
    $name = $_GET['name'];
    $student_id = $_GET['student_id'];
    $today = date('d/m/Y');
    $yesterday = date('d/m/Y', strtotime('-1 day'));

    if (isset($_GET['reponse'])) 
    {
        try 
        {
            $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Prepare the SQL statement to insert statue refusee
            $stmt = $conn->prepare("INSERT INTO feuilles_entree (student_id, date, statu) VALUES (:student_id, :today, :statu)");
            $status = "refuse";
            // Bind the parameters
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':today', $today);
            $stmt->bindParam(':statu', $status);
            // Execute the statement
            $stmt->execute();

            $stmt1 = $conn->prepare("DELETE FROM absence_verification WHERE student_id = :student_id AND (verification_date = :today OR verification_date = :yesterday)");
            $stmt1->bindParam(':student_id', $student_id);
            $stmt1->bindParam(':today', $today);
            $stmt1->bindParam(':yesterday', $yesterday);
            $stmt1->execute();

            header("Location: ../justificatifs.php?generate=refusee");
            exit;
        } 
        catch (PDOException $e) 
        {
            echo 'Error: ' . $e->getMessage();
        }
    } 
    else 
    {
        require_once "dbh.inc.php";

        // Prepare and execute the SQL query to select student info
        $stmt = $conn->prepare("SELECT first_name, last_name, class_id FROM eleve WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $last_name, $class_id);
        $stmt->fetch();
        $stmt->close();

        // Prepare and execute the SQL query to check if the student was absent today or yesterday
        $stmt1 = $conn->prepare("SELECT first_name, last_name FROM surveillant_enseignant WHERE id = ?");
        $stmt1->bind_param("i", $_SESSION['user_id']);
        $stmt1->execute();
        $stmt1->bind_result($sfirst_name, $slast_name);
        $stmt1->fetch();
        $stmt1->close();



        // Create the HTML content
        $htmlContent = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
            <title>EduConnect - accueil</title>
        </head>
        <style>
        body
        {
            background-color: #e4eff8;
        }
        header
        {
          background-color: #fafdff;
        }
        .nav-link:hover
        {
            background-color: #b5cef5;
        }
        .btnh:hover
        {
            background-color: #356ac7;
            --bs-btn-hover-border-color: none;
        }
        .lielement
        {
          margin-right: 20px;
        }
        .link-secondary {
          color: gray !important;
        }
        </style>
        <body>
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
          <a href="../about.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="../styles/images/logo.png" alt="" >
          </a>
        </div>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
          <li class="lielement">
            <a href="../absence.php" class="nav-link px-2 ">Voir l\'absence</a>
          </li>
                      <li class="lielement"><a href="../justificatifs.php" class="nav-link px-2 ">Voir les justificatifs</a></li>
                          <li class="lielement">
                <a href="../profile.php" class="nav-link px-2 ">Mon profil</a>
              </li>
         </ul>
  
        <div class="col-md-3 text-end">
          <a href="logout.php"><button type="button" class="btn btnh btn-outline-primary me-2">Déconnexion</button></a>
        </div>
        </header>  
        <br>
        <center>
        <table>
  <tr>
    <td colspan="2" align="center">
      <img src="../styles/images/ministrylogo.png" alt="Logo" width="400px" height="90px">
    </td>
  </tr>
  <tr>
    <td>Établissement scolaire:</td>
    <td>Lycée X</td>
  </tr>
  <tr>
    <td>Date:</td>
    <td>'.$today.'</td>
  </tr>
  <tr>
  <td colspan="2" style="text-align: center; text-decoration: underline;">Feuille Permis d\'entrée</td>
  </tr>
  <tr>
    <td colspan="2">
      <p>Élève:</p>
      <ul>
        <li>Nom: '.$first_name.'</li>
        <li>Prénom: '.$last_name.'</li>
        <li>Classe: Class '.$class_id.'</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>Responsable:</p>
      <ul>
        <li>Nom: '.$sfirst_name.' '.$slast_name.'</li>
        <li>Fonction: Surveillant Général</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>Signature du responsable:</p>
      <p>'.$sfirst_name.' '.$slast_name.'</p>
      <p>________________________________________________________________</p>
    </td>
  </tr>        
  </table>
  </center>
  </body>
  </html>
        ';
    
try 
{
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO feuilles_entree (student_id, name, html_content, date, statu) VALUES (:student_id, :name, :html_content, :today, :statu)");
    $status = "accept";

    // Bind the parameters
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':html_content', $htmlContent);
    $stmt->bindParam(':today', $today);
    $stmt->bindParam(':statu', $status);


    // Execute the statement
    $stmt->execute();

    header("Location: ../justificatifs.php?generate=success");
} 
catch (PDOException $e) 
{
    header("Location: ../justificatifs.php?generate=failed");
}
}
}
?>

