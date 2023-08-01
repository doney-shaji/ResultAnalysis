<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// MySQL database connection details
require_once('database.php');

$ProgramID = isset($_POST['program']) ? $_POST['program'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$batch = isset($_POST['batch']) ? $_POST['batch'] : '';
// Check if a file was uploaded
if (isset($_FILES['excel_file']['tmp_name']) && $_FILES['excel_file']['tmp_name'] != '') {
    $file = $_FILES['excel_file']['tmp_name'];

    // Load the spreadsheet file
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();

    // Iterate through the rows, starting from the second row (skipping the header row)
    $rows = $worksheet->toArray(null, true, true, true);
    $headerRowSkipped = false;
    $rowCount = 0;
    foreach ($rows as $row) {
        if ($rowCount < 1) {
            $rowCount++;
            continue;
        }
        if (empty(array_filter($row))) {
            continue; // Skip empty rows
        }
        // Assuming 'uid' is in column A
        $name = $row['B'];
        $uid = $row['C']; 
        $ret = $row['D']; // Assuming 'ret' is in column D

        // Insert the record into the database
        $sql = "INSERT INTO student (`StudentID`, `StudentName`, RETID, ProgramID, Semester, Batch)
                VALUES ('$uid', '$name', '$ret', '$ProgramID', '$semester', '$batch')";

        if ($conn->query($sql) === false) {
            $_SESSION['status'] = 'Error inserting data';
        } else {
            $_SESSION['status'] = 'Data inserted successfully';
            
        }
        
    }
    header('location:/Result_analysis/index.php');
            exit;
}
 else {
    echo 'No file uploaded.';
}
// Close the database connection
$conn->close();
?>
