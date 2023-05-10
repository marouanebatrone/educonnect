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

if($_SESSION['user_role'] == 'surveillant' || $_SESSION['user_role'] == 'teacher')
{
  if(isset($_GET['id']) && isset($_GET['class_number']))
  {
    $_SESSION['id'] = $class_id = $_GET['id'];
    $_SESSION['class_name'] = $class_name = $_GET['class_number'];
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>EduConnect - absence</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
body
{
    background-color: #e4eff8;
}
.form-select
{
    width: 200px;
}
.stmtinfo
{
    text-align: center;
    margin-bottom: 20px;
}
.btna
{
  background-color: #356ac7;
  color: white;
}
.btna:hover
{
  background-color: #235ec2;
  --bs-btn-hover-border-color: none;
}
.custom-width 
{
  width: 70px; 
  margin-top: -3.2px;
}
.intro
{
  margin: 30px 0px;
}
.class_name
{
  text-decoration: underline;
}
.Aucune
{
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
}
.selectdowl
{
  margin: 20px 0px;
}
select.custom-select-class 
{
  width: 100px !important;
}
ul.pagination
{
  margin-top: 5px;
}
</style>
<body>
<?php include 'header.php';?>

    <div class="container mt-3">
        <h5 class="stmtinfo">
          <?php
           if($_SESSION['user_role'] == 'surveillant')
           {echo 'Choisissez la classe et le mode d\'affichage de l\'absence:';}
           elseif($_SESSION['user_role'] == 'teacher')
           {echo 'Choisissez la classe pour laquelle vous voulez voir l\'absence:';}
           elseif($_SESSION['user_role'] == 'eleve')
           {echo 'Choisissez le mode d\'affichage de l\'absence:';}
           ?>
        </h5>
              <?php
              if($_SESSION['user_role'] == 'surveillant' || $_SESSION['user_role'] == 'teacher')
              {
                echo '        
                <div class="text-center">
                <div class="d-inline-block me-3">
                <form method="post" action="includes/absence_choix.php">
                <select class="form-select" name="choix_class">';

                $stmt1 = $conn->prepare("SELECT * FROM classes WHERE FIND_IN_SET(id, (SELECT class_id FROM surveillant_enseignant WHERE id = ".$_SESSION["user_id"].")) > 0;");
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $userData1 = $result1->fetch_all(MYSQLI_ASSOC);
            
                foreach ($userData1 as $data) 
                {
                  $_SESSION['class_id'] = $data['id'];
                  $_SESSION['class_name'] = $data['class_name'];
                  echo '<option value='.$_SESSION["class_id"].'>'.$_SESSION["class_name"].'</option>';
                }
              }
              echo '            
              </select>
              </div>';
              ?>
          <?php
           if($_SESSION['user_role'] == 'surveillant')
           {
            echo 
            '<div class="d-inline-block">
            <select class="form-select" name="choix_method">
              <option value="mois">Par mois</option>
              <option value="matiere">Par matière</option>
            </select>
            </div>';
           } 
           if($_SESSION['user_role'] == 'eleve')
           {
            echo 
            '
            <form method="post" action="includes/absence_choix.php">
            <div class="text-center">
            <div class="d-inline-block">
            <select class="form-select" name="choix_method">
              <option value="mois">Par mois</option>
              <option value="matiere">Par matière</option>
            </select>
            </div>
            </form>';
           }
           ?>

          <input type="submit" class="btn btna btn-outline-primary me-2 custom-width" value="Voir">
        </form>
        
        <?php 
        if($_SESSION['user_role'] == 'surveillant')
        {
          if(isset($_GET['class_id']) && isset($_GET['choix_method']))
          {
            $choix_method = $_GET['choix_method'];
            $class_id = $_GET['class_id'];

            if($choix_method == 'mois') //If the user selects y mois
            {

           //Pagination 
           // Définir le nombre de lignes par page
            $LignesParPage = 10;
            
            // Obtenir le numéro de page actuel
            if (isset($_GET['page'])) 
            {
                $currentPage = $_GET['page'];
            } 
            else 
            {
                $currentPage = 1;
            }
            // Calculate offset
            $offset = ($currentPage - 1) * $LignesParPage;

              $stmt = "SELECT * FROM absence WHERE Classe = 'class" . $class_id . "' LIMIT $LignesParPage OFFSET $offset;";
              $student_list = $conn->query($stmt);

            // Obtenir le nombre total des lignes de la base de donee
            $totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM absence WHERE Classe = 'class" . $class_id . "' GROUP BY student_id;"));
            
            // Calculer le nombre total de pages
            $totalPages = ceil($totalRecords / $LignesParPage);

              if ($student_list->num_rows > 0) 
              {
                $students = $student_list->fetch_all(MYSQLI_ASSOC);
  
                echo '
                <section class="intro">
                 <div class="gradient-custom-1 h-100">
                 <h6 class="class_name">Le tableau d\'absence de la classe '.$class_id.' pour chaque mois:</h6>
                  <div class="mask d-flex align-items-center h-100">
                    <div class="container">
                      <div class="row justify-content-center">
                        <div class="col-12">
                          <div class="table-responsive bg-white">
                            <table class="table mb-0">
                              <thead>
                                <tr>
                                <th scope="col">No</th>
                                  <th scope="col">Prénom</th>
                                  <th scope="col">Nom</th>
                                  <th scope="col">Cne</th>
                                  <th scope="col">Nombre d\'heures</th>
                                  <th scope="col">Mois</th>
                                </tr>
                              </thead>
                              <tbody>';
                        foreach ($students as $student) 
                              {
                                //Selection of absence for each student for each month
                                $stmt = $conn->prepare("SELECT *, SUM(Nombre_heures) AS total_heurs FROM absence WHERE student_id = ? GROUP BY Mois");
                                $stmt->bind_param("i", $student['student_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
  
                                //Selection of No of student
                                $stmt = $conn->prepare("SELECT No FROM eleve WHERE id = ?");
                                $stmt->bind_param("i", $student['student_id']);
                                $stmt->execute();
                                $stmt->bind_result($no);
                                $stmt->fetch();
                                $stmt->close();
  
                                while ($row = $result->fetch_assoc()) 
                                {
                                  echo '<tr>';
                                  echo '<td>' . $no . '</td>';
                                  echo '<td>' . $row['Prénom'] . '</td>';
                                  echo '<td>' . $row['Nom'] . '</td>';
                                  echo '<td>' . $row['Cne'] . '</td>';
                                  echo '<td>' . $row['total_heurs'] . '</td>';
                                  echo '<td>' . $row['Mois'] . '</td>';
                                  echo '</tr>';
                                }
                              }
                              echo '</tbody>';
                            echo '</table>';
                            echo '</div>';

                               // Afficher les liens de pagination
                               echo "<nav aria-label='...'>
                               <ul class='pagination justify-content-center'>";
                               
                               if ($currentPage == 1) 
                               {
                                echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
                              } 
                              else
                              {
                                echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&choix_method=".$choix_method."&page=".($currentPage-1)."'>Previous</a></li>";
                              }
                              for ($i = 1; $i <= $totalPages; $i++) 
                              {
                                if ($i == $currentPage) 
                                {
                                  echo "<li class='page-item active' aria-current='page'><a class='page-link' href='#'>$i</a></li>";
                                } 
                                  else 
                                  {
                                    echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&choix_method=".$choix_method."&page=$i'>$i</a></li>";
                                  }
                                }
                                  if ($currentPage == $totalPages) 
                                  {
                                    echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
                                  } 
                                    else 
                                    {
                                      echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&choix_method=".$choix_method."&page=".($currentPage+1)."'>Next</a></li>";
                                    }
                                    echo "</ul>
                                    </nav>";
                        echo '</div>';
                      echo '</div>';
                    echo '</div>';
                  echo '</div>';
                 echo '</div>';
                 echo '<div class="text-center">';
                 echo '</div>';
                echo '</section>';
              }
              else
              {
                echo '<h6 class="Aucune">Aucune résultat trouvé!</h6>';
              }
            }
            else if($choix_method == 'matiere') //if user selects by matiere
            {
              //Pagination 
              // Définir le nombre de lignes par page        
              $LignesParPage = 10;

              // Obtenir le numéro de page actuel
              if (isset($_GET['page'])) 
              {
                $currentPage = $_GET['page'];
              }  
              else 
              {
                $currentPage = 1;
              }

              // Calculate offset
              $offset = ($currentPage - 1) * $LignesParPage;

              $stmt = "SELECT * FROM eleve WHERE class_id = '$class_id' LIMIT $LignesParPage OFFSET $offset;";
              $result = $conn->query($stmt);

              // Obtenir le nombre total des lignes de la base de donee
              $totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM eleve WHERE class_id = '$class_id';"));
          
              // Calculer le nombre total de pages
              $totalPages = ceil($totalRecords / $LignesParPage);
          
              if ($result->num_rows > 0) 
              {
                $students = $result->fetch_all(MYSQLI_ASSOC);

                echo '
                <section class="intro">
                 <div class="gradient-custom-1 h-100">
                 <h6 class="class_name">Le tableau d\'absence de la classe '.$class_id.' pour chaque matière pour ce semetre:</h6>
                  <div class="mask d-flex align-items-center h-100">
                    <div class="container">
                      <div class="row justify-content-center">
                        <div class="col-12">
                          <div class="table-responsive bg-white">
                            <table class="table mb-0">
                              <thead>
                                <tr>
                                <th scope="col">No</th>
                                  <th scope="col">Prénom</th>
                                  <th scope="col">Nom</th>
                                  <th scope="col">Cne</th>
                                  <th scope="col">Nombre d\'heures</th>
                                  <th scope="col">Matière</th>
                                </tr>
                              </thead>
                              <tbody>';
            
                              foreach($students as $student)
                              {

                                $stmt1 = "SELECT *, SUM(absence_hours) AS total_hours FROM absence_details WHERE student_id = ".$student['id']." GROUP BY teacher_id;";
                                $result1 = $conn->query($stmt1);

                                if ($result1->num_rows > 0) 
                                {
                                  $row1 = $result1->fetch_all(MYSQLI_ASSOC);
                                }
                                foreach($row1 as $row11)
                                {
                                echo '<tr>';

                                //Selection of No of student
                                $stmt = $conn->prepare("SELECT No FROM eleve WHERE id = ?");
                                $stmt->bind_param("i", $student['id']);
                                $stmt->execute();
                                $stmt->bind_result($no);
                                $stmt->fetch();
                                $stmt->close();

                                echo '<td>' .$no.'</td>';
                                echo '<td>' .$student["first_name"]. '</td>';
                                echo '<td>' .$student["last_name"]. '</td>';
                                echo '<td>' .$student["cne"]. '</td>';
                                echo '<td>' .$row11['total_hours']. '</td>';

                                //Selection of subject name from teacher_id
                                $stmt = $conn->prepare("SELECT teaching_subject FROM calendrier_enseignant WHERE teacher_id = ?");
                                $stmt->bind_param("i", $row11["teacher_id"]);
                                $stmt->execute();
                                $stmt->bind_result($sub);
                                $stmt->fetch();
                                $stmt->close();
                                echo '<td>' .$sub. '</td>';
                                }
                                echo '</tr>';
                              }
              
                              echo '</tbody>';
                            echo '</table>';
                          echo '</div>';

                              // Afficher les liens de pagination
                              echo "<nav aria-label='...'>
                              <ul class='pagination justify-content-center'>";
                              
                              if ($currentPage == 1) 
                              {
                               echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
                             } 
                             else
                             {
                               echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&choix_method=".$choix_method."&page=".($currentPage-1)."'>Previous</a></li>";
                             }
                             for ($i = 1; $i <= $totalPages; $i++) 
                             {
                               if ($i == $currentPage) 
                               {
                                 echo "<li class='page-item active' aria-current='page'><a class='page-link' href='#'>$i</a></li>";
                               } 
                                 else 
                                 {
                                   echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&choix_method=".$choix_method."&page=$i'>$i</a></li>";
                                 }
                               }
                                 if ($currentPage == $totalPages) 
                                 {
                                   echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
                                 } 
                                   else 
                                   {
                                     echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&choix_method=".$choix_method."&page=".($currentPage+1)."'>Next</a></li>";
                                   }
                                   echo "</ul>
                                   </nav>";
                        echo '</div>';
                      echo '</div>';
                    echo '</div>';
                  echo '</div>';
                 echo '</div>';
                echo '</section>';

              } 
              else
              {
                echo '<h6 class="Aucune">Aucune résultat trouvé!</h6>';
              }
            }

          }

          if(isset($_GET['class_id']))
          {
          echo '
          <div class="container selectdowl mt-3">
          <h5 class="stmtinfo">Télécharger le relevé d\'absence de cette classe en sélectionnant un mois:</h5>
          <div class="row justify-content-center">
          <div class="col-sm-6">
          <form method="post" action="includes/generate_excel.php?class_name=class'.$class_id.'&id='.$class_id.'">
          <div class="input-group">
          <select class="form-select custom-select-class" name="choix_mois">
          <option value="vide">Sélectionnez un mois</option>;';
          for ($i = 1; $i <= date('n'); $i++) 
          {
            $month = strftime('%B', strtotime("2023-$i-01"));
            if ($i === (int)date('n') && (int)date('j') <= 28) 
            {
              $month .= ' (en cours)';
            }
            echo "<option value=\"$month\">$month</option>";
          }
          echo '
        </select>
      <input type="submit" class="btn btna btn-outline-primary" value="Télécharger">
      </form>
      </div>
      </div>
      </div>';
        }
      }

      //Teacher section
      else if($_SESSION['user_role'] == 'teacher')
      {
        if(isset($_GET['class_id']))
        {
          $class_id = $_GET['class_id'];

              //Pagination 
              // Définir le nombre de lignes par page        
              $LignesParPage = 10;

              // Obtenir le numéro de page actuel
              if (isset($_GET['page'])) 
              {
                $currentPage = $_GET['page'];
              }  
              else 
              {
                $currentPage = 1;
              }

              // Calculate offset
              $offset = ($currentPage - 1) * $LignesParPage;

              $stmt = "SELECT *, SUM(absence_hours) AS total_hours FROM absence_details WHERE class_id = '$class_id' AND teacher_id = ".$_SESSION["user_id"]." GROUP BY student_id LIMIT $LignesParPage OFFSET $offset;";
              $student_list = $conn->query($stmt);

              // Obtenir le nombre total des lignes de la base de donee
              $totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT *, SUM(absence_hours) AS total_hours FROM absence_details WHERE class_id = '$class_id' AND teacher_id = ".$_SESSION["user_id"]." GROUP BY student_id;"));
          
              // Calculer le nombre total de pages
              $totalPages = ceil($totalRecords / $LignesParPage);
            
          if ($student_list->num_rows > 0) 
            {
              $students = $student_list->fetch_all(MYSQLI_ASSOC);
              
              echo '
              <section class="intro">
               <div class="gradient-custom-1 h-100">
               <h6 class="class_name">Le tableau d\'absence de la classe '.$class_id.' pour ce semestre pour ta matière:</h6>
                <div class="mask d-flex align-items-center h-100">
                  <div class="container">
                    <div class="row justify-content-center">
                      <div class="col-12">
                        <div class="table-responsive bg-white">
                          <table class="table mb-0">
                            <thead>
                              <tr>
                              <th scope="col">No</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Cne</th>
                                <th scope="col">Nombre d\'heures</th>
                              </tr>
                            </thead>
                            <tbody>';
                      foreach ($students as $student) 
                            {
  
                              //Selection of No of student
                              $stmt = $conn->prepare("SELECT No, first_name, last_name, cne FROM eleve WHERE id = ?");
                              $stmt->bind_param("i", $student['student_id']);
                              $stmt->execute();
                              $stmt->bind_result($no, $first_name, $last_name, $cne);
                              $stmt->fetch();
                              $stmt->close();
  
                                echo '<tr>';
                                echo '<td>' . $no . '</td>';
                                echo '<td>' . $first_name . '</td>';
                                echo '<td>' . $last_name . '</td>';
                                echo '<td>' . $cne . '</td>';
                                echo '<td>' . $student['total_hours'] . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                          echo '</table>';
                        echo '</div>';
                        // Afficher les liens de pagination
                        echo "<nav aria-label='...'>
                        <ul class='pagination justify-content-center'>";
                        
                        if ($currentPage == 1) 
                        {
                         echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
                       } 
                       else
                       {
                         echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&page=".($currentPage-1)."'>Previous</a></li>";
                       }
                       for ($i = 1; $i <= $totalPages; $i++) 
                       {
                         if ($i == $currentPage) 
                         {
                           echo "<li class='page-item active' aria-current='page'><a class='page-link' href='#'>$i</a></li>";
                         } 
                           else 
                           {
                             echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&page=$i'>$i</a></li>";
                           }
                         }
                           if ($currentPage == $totalPages) 
                           {
                             echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
                           } 
                             else 
                             {
                               echo "<li class='page-item'><a class='page-link' href='absence.php?class_id=".$class_id."&page=".($currentPage+1)."'>Next</a></li>";
                             }
                             echo "</ul>
                             </nav>";
                      echo '</div>';
                    echo '</div>';
                  echo '</div>';
                echo '</div>';
               echo '</div>';
               echo '<div class="text-center">';
               echo '</div>';
              echo '</section>';
            }
            else
            {
              echo '<h6 class="Aucune">Aucune résultat trouvé!</h6>';
            }
        }
        
          }
          else if($_SESSION['user_role'] == 'eleve')
          {
            if(isset($_GET['choix_method']))
            {
              $choix_method = $_GET['choix_method'];
              if($choix_method == 'mois') //If the user selects y mois
              {
                //Selection of absence for the student
                $stmt = $conn->prepare("SELECT *, SUM(Nombre_heures) AS total_heurs FROM absence WHERE student_id = ? GROUP BY Mois");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) 
                {
                  $rows = $result->fetch_all(MYSQLI_ASSOC);
    
                  echo '
                  <section class="intro">
                   <div class="gradient-custom-1 h-100">
                   <h6 class="class_name">Le nombre d\'heures que tu as absent pour chaque mois:</h6>
                    <div class="mask d-flex align-items-center h-100">
                      <div class="container">
                        <div class="row justify-content-center">
                          <div class="col-12">
                            <div class="table-responsive bg-white">
                              <table class="table mb-0">
                                <thead>
                                  <tr>
                                  <th scope="col">Mois</th>
                                  <th scope="col">Nombre d\'heures</th>
                                  </tr>
                                </thead>
                                <tbody>';
                                  foreach ($rows as $row) 
                                  {
                                    echo '<tr>';
                                    echo '<td>' . $row['Mois'] . '</td>';
                                    echo '<td>' . $row['total_heurs'] . '</td>';
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
                   echo '</div>';
                  echo '</section>';
                }
                else
                {
                  echo '<h6 class="Aucune">Aucune résultat trouvé!</h6>';
                }
            }
            else if($choix_method == 'matiere')
            {
              $stmt = "SELECT *, SUM(absence_hours) AS total_hours FROM absence_details WHERE student_id = ".$_SESSION['user_id']." GROUP BY teacher_id;";
              $result = $conn->query($stmt);
          
              if ($result->num_rows > 0) 
              {
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                echo '
                <section class="intro">
                 <div class="gradient-custom-1 h-100">
                 <h6 class="class_name">Le nombre d\'heures que tu as absent pour chaque matière pour ce semetre:</h6>
                  <div class="mask d-flex align-items-center h-100">
                    <div class="container">
                      <div class="row justify-content-center">
                        <div class="col-12">
                          <div class="table-responsive bg-white">
                            <table class="table mb-0">
                              <thead>
                                <tr>
                                <th scope="col">Matière</th>
                                <th scope="col">Nombre d\'heures</th>
                                </tr>
                              </thead>
                              <tbody>';
                              foreach($rows as $row)
                              {
                                echo '<tr>';
                                echo '<td>' .$row['total_hours']. '</td>';

                                //Selection of subject name from teacher_id
                                $stmt = $conn->prepare("SELECT teaching_subject FROM calendrier_enseignant WHERE teacher_id = ?");
                                $stmt->bind_param("i", $row["teacher_id"]);
                                $stmt->execute();
                                $stmt->bind_result($sub);
                                $stmt->fetch();
                                $stmt->close();
                                echo '<td>' .$sub. '</td>';
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
                echo '</section>';

              } 
              else
              {
                echo '<h6 class="Aucune">Aucune résultat trouvé!</h6>';
              }              
            }
            
            }

          }
      
        
   ?>
        </div>
      </div>

</body>
</html>