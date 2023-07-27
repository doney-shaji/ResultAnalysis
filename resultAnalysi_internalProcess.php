<?php
session_start();

$exam_name = $_POST['exam_type'];
$no_of_parts = $_POST['parts'];
$ProgramID = isset($_POST['program']) ? $_POST['program'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$subject = $_POST['subject'];
$batch = $_POST['batch'];

$servername = 'localhost';
$username = 'root';
$password = '3321185';
$dbname = 'result_analysis';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$select_query = "SELECT * FROM internal_exam WHERE ExamName = '$exam_name' AND ProgramID = '$ProgramID' AND Semester = '$semester' AND CourseID = '$subject' AND Batch = '$batch'";
$result = $conn->query($select_query);

if ($result->num_rows > 0) {
  $_SESSION['status'] = "Value already exists in the table. No insert will be performed.";
  header("location:index.php");
        exit();
} else {
    // Value does not exist, perform the insert query
    $sql = "INSERT INTO internal_exam (`ExamName`, `No_of_Parts`, `ProgramID`, `Semester`, `CourseID`, Batch) VALUES ('$exam_name', '$no_of_parts', '$ProgramID', '$semester', '$subject', '$batch')";
    if ($conn->query($sql) === TRUE) {
        
        $_SESSION['program'] = $ProgramID;
        $_SESSION['semester'] = $semester;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $last_insert_id = mysqli_insert_id($conn);
    // Insert details into the second table
    $_SESSION['id'] = $last_insert_id;
    for ($i = 0; $i < $no_of_parts; $i++) {
        if($i == 0){
            $part_name = chr(65+($i));
            $num_questions = $_POST['inputQuestions'. chr(65 + ($i))];
            $marks = $_POST['inputMarks'. chr(65 + ($i))];
            $choice = 'No';
        }
        else{
            $part_name = chr(65+($i));
            $num_questions = $_POST['inputQuestions'. chr(65 + ($i))];
            $marks = $_POST['inputMarks'. chr(65 + ($i))];
            $choice = $_POST['answer'];
        }
        $sql_1 = "INSERT INTO internal_exam_part (`PartName`, `NoOfQst`, `QstChoice`, `MarksEachQst`, `LastID`) VALUES ('$part_name', '$num_questions', '$choice', '$marks','$last_insert_id')";

        if ($conn->query($sql_1) === TRUE) {
            $_SESSION['status'] = 'Data inserted successfully';
          
        }
        else{
            $_SESSION['status'] = 'Error inserting data';
            echo "Error: " . $sql ."<br>" . $conn->error;
        }
        
    }
    header("location:index.php");
          exit();
}

$conn->close();
?>
