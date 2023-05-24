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

  if(isset($_GET['id']))
  {
    $class_id = $_GET['id'];
  }
?>

<html>
    <head>
    <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
body
{
    background-color: #e4eff8;
}
.stmtinfo
{
    text-align: center;
    margin-bottom: 20px;
}
#chart-container 
{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
}
#curve_chart 
{
    width: 900px;
    height: 500px;
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
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
  
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            <?php
              $query = "SELECT Mois, SUM(Nombre_heures) AS Total_heures FROM absence WHERE Classe = 'class" . $class_id . "' GROUP BY Mois;";
              $result = mysqli_query($conn, $query);
              
              // Store the data in an array
              $data = array();
              while ($row = mysqli_fetch_assoc($result)) 
              {
                $data[] = array
                (
                  'month' => $row['Mois'],
                  'total_heures' => $row['Total_heures']
                );
              }

              $monthOrder = array(
                'January' => 1,
                'February' => 2,
                'March' => 3,
                'April' => 4,
                'May' => 5,
                'October' => 6,
                'November' => 7,
                'December' => 8
            );
            
            // Sort the data array based on the month order
            usort($data, function($a, $b) use ($monthOrder) {
                return $monthOrder[$a['month']] - $monthOrder[$b['month']];
            });
            ?>
            ['Mois', 'Heures d\'absences'],
            <?php
            foreach ($data as $row) 
            {
              $month = $row['month'];
              $total_heures = $row['total_heures'];
              echo "['$month', $total_heures],";
            }
          ?>
          ]);
  
          var options = {
            title: 'L\'absence de la classe <?php echo $class_id ?> pour toute cette année scolaire',
            curveType: 'function',
            legend: { position: 'bottom' }
          };
  
          var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
  
          chart.draw(data, options);
        }
      </script>
    </head>
    <body>
    <?php include 'header.php';?>

    <h5 class="stmtinfo">
          <?php
           echo 'Choisissez la classe pour laquelle vous voulez visualiser l\'absence:';
           ?>
    </h5>

    <?php
              if($_SESSION['user_role'] == 'surveillant')
              {
                echo '        
                <div class="text-center">
                <div class="d-inline-block me-3">
                <form method="post" action="includes/absence_choix.php">
                <select class="form-select" name="choix_classv">';

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
              <input type="submit" class="btn btnt btn-outline-primary me-2 custom-width" value="Voir">
              </form>

    <div id="chart-container">
    <div id="curve_chart"></div>
  </div>
    </body>
  </html>
  