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
        foreach ($_POST['marks_no'] as $uid => $marks) {
            foreach ($marks as $markIndex => $mark) {
                $questionID = $markIndex+1;
                $mark = isset($marks[$markIndex]) ? floatval($marks[$markIndex]) : 0.0;
                $insertQuery = "INSERT INTO marks (`StudentID`, QuestionNo, ExamID, MarksObtained) VALUES ('" . $uid . "', '" . $questionID . "', '" . $lastid . "', '" . $mark . "')";
                
                $result = mysqli_query($conn, $insertQuery);
                $question_Index = $questionID;
                
            }
        }

        foreach ($_POST['marks_yes'] as $uid => $marks) {
            foreach ($marks as $markIndex => $mark) {
                if (($markIndex) % 2 === 0) {
                    $questionID_1 = $markIndex + $question_Index + 1;
                    $questionID_2 = $markIndex + $question_Index + 2;
                    $mark1 = isset($marks[$markIndex]) ? floatval($marks[$markIndex]) : 0.0;
                    $mark2 = isset($marks[$markIndex+1]) ? floatval($marks[$markIndex+1]) : 0.0;
                    $mark = $mark1 > $mark2 ? $mark1 : $mark2;
                    $questionID = $mark1 >= $mark2 ? $questionID_1 : $questionID_2;
                    $insertQuery = "INSERT INTO marks (`StudentID`, QuestionNo, ExamID, MarksObtained) VALUES ('" . $uid . "', '" . $questionID . "', '" . $lastid . "', '" . $mark . "')";
                    $result = mysqli_query($conn, $insertQuery);
                }
            }
        }
            if ($result) {
                echo "Data inserted successfully.";
                $_SESSION['lastid'] = $lastid;
                $_SESSION['status'] = 'Data inserted successfully';
                header("location:index.php");
                exit();
            } else {
                echo "Error inserting data: " . mysqli_error($conn);
            }
    
        }
    
    ?>