<?php
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

// Retrieve image from the database based on the provided id parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare('SELECT name, data FROM absence_verification WHERE id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        // Set the appropriate headers for downloading the image
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $image['name'] . '"');

        // Output the image data
        echo $image['data'];
        exit();
    }
}
else
{
// If the image id is invalid or not provided, redirect back to the image gallery page
header('Location: ../justificatifs.php?download=failed');
exit();
}

?>
