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
// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM surveillant_enseignant WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
}

else if($_SESSION['user_role'] == 'eleve')
{
// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM eleve WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
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

    <title>EduConnect - profile</title>
</head>
<style>

body
{
background-color: #e4eff8;
color:#69707a;
}

.img-account-profile {
    height: 10rem;
}
.rounded-circle {
    border-radius: 50% !important;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
}
.card .card-header {
    font-weight: 500;
}
.card-header:first-child {
    border-radius: 0.35rem 0.35rem 0 0;
}
.card-header {
    padding: 1rem 1.35rem;
    margin-bottom: 0;
    background-color: rgba(33, 40, 50, 0.03);
    border-bottom: 1px solid rgba(33, 40, 50, 0.125);
}
.form-control, .dataTable-input {
    display: block;
    width: 100%;
    padding: 0.875rem 1.125rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1;
    color: #69707a;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #c5ccd6;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.nav-borders .nav-link.active 
{
    color: #0061f2;
    border-bottom-color: #0061f2;
}
.nav-borders .nav-link 
{
    color: #69707a;
    border-bottom-width: 0.125rem;
    border-bottom-style: solid;
    border-bottom-color: transparent;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    padding-left: 0;
    padding-right: 0;
    margin-left: 1rem;
    margin-right: 1rem;
}
.btne
{
    background-color: #356ac7;
}
.btne:hover
{
    background-color: #356ac7;
    --bs-btn-hover-border-color: none;
}
</style>

<body>
<?php include 'header.php';?>

  <div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Image de profil</div>
                <div class="card-body text-center">
                    <!-- Profile picture image-->
                    <img class="img-account-profile rounded-circle mb-2" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                    <!-- Profile picture help block-->
                    <div class="small font-italic text-muted mb-4">
                        <h5><?php echo $userData['first_name'] . " " . $userData['last_name']; ?></h5>
                        <?php
                        if($_SESSION['user_role'] == 'teacher') echo "<h6>Professeur</h6>";
                        if($_SESSION['user_role'] == 'surveillant') echo "<h6>Surveillant</h6>";
                        if($_SESSION['user_role'] == 'eleve') echo "<h6>Éleve</h6>";
                        ?>
                    </div>
                    <!-- Profile picture upload button-->
                    <button class="btn btne btn-primary" type="button">Télécharger une image</button>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Détails du compte</div>
                <div class="card-body">
                    <form action="includes/change_data.php" method="post">
                        <!-- Form Group (username)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="username">Nom d'utilisateur</label>
                            <input class="form-control" id="username" name="username" type="text" placeholder="Nom d'utilisateur" value="<?php echo $userData['username']; ?>">
                        </div>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="prenom">Prénom</label>
                                <input class="form-control" id="prenom" type="text" name="prenom" placeholder="Prénom" value="<?php echo $userData['first_name']; ?>">
                            </div>
                            <!-- Form Group (last name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="nom">Nom</label>
                                <input class="form-control" id="nom" type="text" name="nom" placeholder="Nom" value="<?php echo $userData['last_name']; ?>">
                            </div>
                        </div>
                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="email">E-mail adresse</label>
                            <input class="form-control" id="email" type="email" name="email" placeholder="E-mail" value="<?php echo $userData['email']; ?>">
                        </div>
                        <!-- Form Group (phone number)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="phone">Numéro de téléphone</label>
                            <input class="form-control" id="phone" type="tel" name="phone" placeholder="Numéro de téléphone" value="<?php echo $userData['phone']; ?>">
                        </div>

                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group Password-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="newpass">Nouveau mot de passe</label>
                                <input class="form-control" id="newpass" type="password" name="newpass" placeholder="Nouveau mot de passe">
                            </div>
                            <!-- Form Group (birthday)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="rnewpass">Retaper le nouveau mot de passe</label>
                                <input class="form-control" id="rnewpass" type="password" name="rnewpass" placeholder="Retaper le nouveau mot de passe">
                            </div>
                        </div>
                        <!-- Save changes button-->
                        <input class="btn btne btn-primary" type="submit" name="submit" value="Enregistrer">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>