<?php
if (isset($_GET['student_id'])) 
{
    // Get the ID from the URL parameter
    $student_id = $_GET['student_id'];

    // Get today's and yesterday's dates in the "d/m/Y" format
    $today = date('d/m/Y');
    $yesterday = date('d/m/Y', strtotime('-1 day'));

    // Database connection details
    $dbHost = 'localhost';
    $dbName = 'educconnect';
    $dbUser = 'root';
    $dbPass = '';

    try 
    {
        // Connect to the database
        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to retrieve the PDF path
        $stmt = $conn->prepare("SELECT html_content FROM feuilles_entree WHERE student_id = :student_id AND (date = :date_today OR date = :date_yesterday) AND statu = :statu");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':date_today', $today);
        $stmt->bindParam(':date_yesterday', $yesterday);
        $status = "accept";
        $stmt->bindParam(':statu', $status);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retrieve the HTML content
        $htmlContent = $result['html_content'];

        // Output the HTML content
        echo $htmlContent;
    } 
    catch (PDOException $e) 
    {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
