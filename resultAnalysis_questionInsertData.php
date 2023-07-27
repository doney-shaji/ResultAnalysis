<?php
session_start();
$lastid = isset($_SESSION['lastid']) ? $_SESSION['lastid'] : '';
// Check if the lastid is available
if ($lastid === '') {
    echo "Error: lastid not found.";
    exit();
}
$partids = unserialize($_SESSION['partids']);
$values = unserialize($_SESSION['values']);
$qstChoices = unserialize($_SESSION['qstChoices']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questions = $_POST['Question'];
    $host = 'localhost';
    $username = 'root';
    $password = '3321185';
    $database = 'result_analysis';
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die('Could not connect to the database: ' . mysqli_connect_error());
    }
    $j = 1;
    foreach ($questions as $key => $question) {
        $moduleNumber = $question[0];
        $questionText = $question[1];

        // Find the corresponding partid based on the number of questions
        $partid = getPartId($values, $partids, $qstChoices, $j);

        // Insert the module number, question text, partid, and lastid into the database
        $insertQuery = "INSERT INTO question (ModuleNo, QuestionText, PartID, QuestionNo, LastID) 
                        VALUES ('$moduleNumber', '$questionText', '$partid', '$j', '$lastid')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data: " . mysqli_error($conn);
        }

        $j++;
    }
    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Error: Invalid request method.";
}
function getPartId($values, $partids, $questionChoice, $questionNo) {
    $totalQuestions = 0;
    foreach ($values as $key => $value) {
        $totalQuestions += ($questionChoice[$key] === 'Yes') ? ($value * 2) : $value;
        if ($questionNo <= $totalQuestions) {
            return $partids[$key];
        }
    }
    return null;
}

?>
