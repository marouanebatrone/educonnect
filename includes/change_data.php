<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) 
{
    header("location: index.php");
    exit;
}

// Connect to the database
require_once "dbh.inc.php";

// Check if the form has been submitted
if (isset($_POST['submit'])) 
{
    // Get the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Get the new values from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $newpass = $_POST['newpass'];
    $rnewpass = $_POST['rnewpass'];

    if($_SESSION['user_role'] == 'teacher' || $_SESSION['user_role'] == 'surveillant')
    {
        if(!empty($_POST['newpass']) && !empty($_POST['rnewpass']))
        {
            // Update the user's data in the database with password
            $sql = "UPDATE surveillant_enseignant SET username=?, email=?, phone=?, last_name=?, first_name=?, password=? WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
        }
        else
        {
            // Update the user's data in the database without password
            $sql = "UPDATE surveillant_enseignant SET username=?, email=?, phone=?, last_name=?, first_name=? WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
        }
    }

    else if($_SESSION['user_role'] == 'eleve')
    {
        if(!empty($_POST['newpass']) && !empty($_POST['rnewpass']))
        {
            // Update the user's data in the database with password
            $sql = "UPDATE eleve SET username=?, email=?, phone=?, last_name=?, first_name=?, password=? WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
        }
        else
        {
            // Update the user's data in the database without password
            $sql = "UPDATE eleve SET username=?, email=?, phone=?, last_name=?, first_name=? WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
        }
    }

    if (!mysqli_stmt_prepare($stmt, $sql)) 
    {
        // SQL statement failed
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }
    else
    {
        if(!empty($_POST['newpass']) && !empty($_POST['rnewpass']))
        {
            if($rnewpass == $newpass)
            {
                mysqli_stmt_bind_param($stmt, "ssssssi", $username, $email, $phone, $nom, $prenom, $newpass, $user_id);
                mysqli_stmt_execute($stmt);
                // Close the prepared statement
                mysqli_stmt_close($stmt);
                // Redirect the user profile to the account settings page
                header("location: ../profile.php?update=success");
            }
            else
            {
                header("location: ../profile.php?error=password-not-the-same");
            }
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "sssssi", $username, $email, $phone, $nom, $prenom, $user_id);
            mysqli_stmt_execute($stmt);
            // Close the prepared statement
            mysqli_stmt_close($stmt);
            // Redirect the user back to the account settings page
            header("location: ../profile.php?update=success");
        }
    }
  
}