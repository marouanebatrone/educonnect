<?php
session_start();

require_once('dbh.inc.php');

  //if supervisor requesting
  if(isset($_POST['choix_class']) && isset($_POST['choix_method']))
  {
    $choix_method = $_POST['choix_method'];
    $choix_class = $_POST['choix_class'];

    if ($choix_method == 'mois')
    {
      header("Location: ../absence.php?class_id=".$choix_class."&choix_method=mois");
      exit;
    }
    else if ($choix_method == 'matiere')
    {
      header("Location: ../absence.php?class_id=".$choix_class."&choix_method=matiere");
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

