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

 //student_id
$student_id = $_SESSION['student_id'];

//verification_date
$verification_date = date('d/m/Y');

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $imagePath = $image['tmp_name'];
    $imageName = $image['name'];

    // Read the image file
    $fp = fopen($imagePath, 'rb');
    $data = fread($fp, filesize($imagePath));
    fclose($fp);



    // Prepare and execute SQL query to insert image into database
    $stmt = $db->prepare('INSERT INTO absence_verification (student_id, name, data, verification_date) VALUES (?, ?, ?, ?)');
    $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $imageName, PDO::PARAM_STR);
    $stmt->bindParam(3, $data, PDO::PARAM_LOB);
    $stmt->bindParam(4, $verification_date, PDO::PARAM_STR);


    if ($stmt->execute()) 
    {
       header("Location: ../justification.php?envoi=succes");
       exit;
    } else 
    {
        header("Location: ../justification.php?envoi=filed");
        exit;
    }
}
?>
