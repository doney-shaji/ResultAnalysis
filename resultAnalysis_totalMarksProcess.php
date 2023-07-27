<?php
session_start();
$lastid = isset($_SESSION['lastid']) ? $_SESSION['lastid'] : '';
// Check if the lastid is available
if ($lastid === '') {
    echo "Error: lastid not found.";
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'localhost';
    $username = 'root';
    $password = '3321185';
    $database = 'result_analysis';
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die('Could not connect to the database: ' . mysqli_connect_error());
    }
    foreach ($_POST['total'] as $uid => $marks) {
        $totalMarks = isset($marks['1']) ? floatval($marks['1']) : 0.0; // Assuming you only have one mark per student
        $insertQuery = "INSERT INTO marks_total (`StudentID`, ExamID, TotalMarks) VALUES ('" . $uid . "', '" . $lastid . "', '" . $totalMarks . "')";
        $result = mysqli_query($conn, $insertQuery);
    }
    
    if ($result) {
        echo "Data inserted successfully.";
        $_SESSION['lastid'] = $lastid;
        header("location:index.php");
        exit();
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }
}
?>
