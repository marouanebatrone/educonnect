<?php
session_start();

require_once('dbh.inc.php');

  //if supervisor requesting
  if(isset($_POST['choix_class']) && isset($_POST['choix_method']))
  {
    $choix_method = $_POST['choix_method'];
    $choix_class = $_POST['choix_class'];
    $months = 
    [
      'January', 'February', 'March', 'April', 'May', 'September', 'October', 'November', 'December'
    ];

    if($choix_method == 'empty')
    {
      header("Location: ../absence.php");
      exit;
    }
    else if (in_array($choix_method, $months)) 
    {
      // $method is one of the values in the array
      header("Location: ../absence.php?class_id=".$choix_class."&choix_method=$choix_method");
      exit;    
    } 
 
    else if($choix_method == 'matiere')
    {
      header("Location: ../absence.php?class_id=".$choix_class."&choix_method=".$choix_method."");
      exit;
    }
  }
  
  //if teacher requesting
  else if(isset($_POST['choix_class']) && !isset($_POST['choix_method']))
  {    
    $choix_class = $_POST['choix_class'];
    header("Location: ../absence.php?class_id=".$choix_class."");
    exit;
  }
  else if(isset($_POST['choix_classv']))
  {
    $choix_classv = $_POST['choix_classv'];
    header("Location: ../absence_visualize.php?id=".$choix_classv."");
    exit;
  }
  
  //if student requesting
  else if(!isset($_POST['choix_class']) && isset($_POST['choix_method']))
  {
    $choix_method = $_POST['choix_method'];

    if ($choix_method == 'mois')
    {
      header("Location: ../absence.php?choix_method=mois");
      exit;
    }
    else if ($choix_method == 'matiere')
    {
      header("Location: ../absence.php?choix_method=matiere");
      exit;
    }
  }

