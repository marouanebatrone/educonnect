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
?>
<!DOCTYPE html>
<html>
<head>   
    <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <title>EduConnect - justification</title>
</head>
<style>
body
{
    background-color: #e4eff8;
}
.btnu
{
    background-color: #356ac7;
}
.btnu:hover
{
    background-color: #386bc0;
  --bs-btn-hover-border-color: none;
}
.container
{
    margin-top: 200px;
}
.makeinfo
{
    display: flex;
  justify-content: center;
  align-items: center;
  height: 80vh;
}
</style>
<body>
<?php include 'header.php';?>
<?php

// Get today's and yesterday's dates in the "d/m/Y" format
$today = date('d/m/Y');
$yesterday = date('d/m/Y', strtotime('-1 day'));

// Prepare and execute the SQL query to check if the student was absent today or yesterday
$stmt = $conn->prepare("SELECT absence_hours FROM absence_details WHERE (absence_day = ? OR absence_day = ?) AND student_id = ?");
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

if($absent > 0 &&  $rowCount1 == 0 && $rowCount == 0)
{
?>
<div class="container">
<h6 class="text-center">Merci d'avoir vérifier votre dernière absence en téléchargeant un justificatif!</h6>
    <div class="col-md-4 mx-auto">
        <form method="post" action="includes/justifs_upload.php" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*" required/>
            <p style="text-align: right; margin-top: 20px;">
                <input type="submit" value="Envoyer" class="btn btnu btn-primary" />
            </p>
        </form>
    </div>
</div>

<?php
}

else if(isset($_GET['envoi']))
{
    $envoi = $_GET['envoi'];
    if($envoi == 'succes')
    {
        echo '<h6 class="makeinfo">Vous avez envoyé votre justificatif avec succès!</h6>';
    }
    else if($envoi == 'failed')
    {
        echo '<h6 class="makeinfo">Votre justificatif n\'a pas été envoyé ,Ressayez encore une fois!</h6>';
    }
}
else
{
    echo '<h6 class="makeinfo">Actuellement, vous n\'avez aucune absence à justifier!</h6>';
}
?>

<!-- you need to include the ShieldUI CSS and JS assets in order for the Upload widget to work -->
<link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>

<script type="text/javascript">
    jQuery(function ($) 
    {
        $("#files").shieldUpload();
    });
</script>
</div>
 
</body>
</html>
