<?php
session_start();
require_once "dbh.inc.php";


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Include the Composer autoloader
require '../vendor/autoload.php';

if(isset($_POST['choix_mois']))
{
    $mois = $_POST['choix_mois'];
    if($mois == 'vide' || strpos($mois, ' (en cours)') !== false)
    {
        $class_name = $_GET['class_name'];
        $id = $_GET['id'];
        header('Location: ../absence.php?id='.$id.'&class_number='.$class_name.'');
        exit;
    }

if(isset($_GET['class_name']) && isset($_POST['choix_mois']))
{
    $class_name = $_GET['class_name'];
    $mois = $_POST['choix_mois'];
    // Query the database to fetch the data from the absence table
    $sql = "SELECT Prénom, Nom, Cne, SUM(Nombre_heures) AS total_heurs FROM absence WHERE Classe = '$class_name' AND Mois = '$mois' GROUP BY student_id";
    $result = $conn->query($sql);

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

// Set the column headings
$sheet->setCellValue('A1', 'Prénom');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Cne');
$sheet->setCellValue('D1', 'Total heurs');

// Make the headings bold
$boldStyle = [
    'font' => [
        'bold' => true,
        'size' => 14,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:D1')->applyFromArray($boldStyle);

// Loop through the data and populate the cells
$i = 2;
if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        $sheet->setCellValue('A'.$i, $row['Prénom']);
        $sheet->setCellValue('B'.$i, $row['Nom']);
        $sheet->setCellValue('C'.$i, $row['Cne']);
        $sheet->setCellValue('D'.$i, $row['total_heurs']);

        //Setting the height of rows
        $sheet->getRowDimension($i)->setRowHeight(21);
        $i++;
    }
}

// Center the data
$centerStyle = [
    'font' => [
        'size' => 14,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:D'.$i)->applyFromArray($centerStyle);

// Set the column width to 15
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(22);

$sheet->getRowDimension(1)->setRowHeight(21);


    // Set the file format and save the file
    $filename = 'absence_'.$class_name.'_'.$mois.'.xlsx';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);

    // Set headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // Output the file data to the browser
    $writer->save('php://output');
    exit();

    // Close the database connection
    $conn->close();
}
}
else if(isset($_POST['choix_matiere']))
{
    $matiere = $_POST['choix_matiere'];
    if($matiere == 'vide')
    {
        $class_name = $_GET['class_name'];
        $id = $_GET['id'];
        header('Location: results_table.php?id='.$id.'&class_number='.$class_name.'');
        exit;
    }

if(isset($_GET['class_name']) && isset($_POST['choix_matiere']))
{
    $class_name = $_GET['class_name'];
    $choix_matiere = $_POST['choix_matiere'];
    $class_id = $_GET["id"];
    // Query the database to fetch the data from the results table

    $query1 = "SELECT teacher_id FROM schedule WHERE teaching_subject = '$choix_matiere';";
    $result1 = $conn->query($query1);
    $teacher_id = $result1->fetch_assoc()['teacher_id'];

    $month = date('m'); // Get the month of the current date
    if($month >= 12 && $month < 2){$NS=1;$NC=1;}
    if($month >= 2 && $month < 4){$NS=1;$NC=2;}
    if($month >= 4 && $month < 6){$NS=2;$NC=1;}
    if($month >= 6 && $month < 8){$NS=2;$NC=2;}

    $sql = "SELECT Prénom, Nom, Cne, Note, No_Controle FROM results WHERE class_id = '$class_id' AND teacher_id = '$teacher_id' AND No_Controle = '$NC' AND No_Semester = '$NS' GROUP BY student_id";
    $result = $conn->query($sql);

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

// Set the column headings
$sheet->setCellValue('A1', 'Prénom');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Cne');
$sheet->setCellValue('D1', 'Note');
$sheet->setCellValue('E1', 'No Controle');


// Make the headings bold
$boldStyle = [
    'font' => [
        'bold' => true,
        'size' => 14,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:E1')->applyFromArray($boldStyle);

// Loop through the data and populate the cells
$i = 2;
if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        $sheet->setCellValue('A'.$i, $row['Prénom']);
        $sheet->setCellValue('B'.$i, $row['Nom']);
        $sheet->setCellValue('C'.$i, $row['Cne']);
        $sheet->setCellValue('D'.$i, $row['Note']);
        $sheet->setCellValue('E'.$i, $row['No_Controle']);

        //Setting the height of rows
        $sheet->getRowDimension($i)->setRowHeight(21);
        $i++;
    }
}

// Center the data
$centerStyle = [
    'font' => [
        'size' => 14,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:E'.$i)->applyFromArray($centerStyle);

// Set the column width to 15
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);

$sheet->getRowDimension(1)->setRowHeight(29);


    // Set the file format and save the file
    $filename = 'Controle'.$NC.'_'.'Semester'.$NS.$class_name.'_'.$matiere.'.xlsx';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);

    // Set headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    // Output the file data to the browser
    $writer->save('php://output');
    exit();

    // Close the database connection
    $conn->close();
}
}
