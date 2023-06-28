<?php
// Retrieve the form attributes from the $_POST array

$exam_name = $_POST['exam_type'];
$no_of_parts = $_POST['parts'];
$ProgramID = isset($_POST['program']) ? $_POST['program'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$subject = $_POST['subject'];

// Retrieve more attributes as needed

// Connect to the MySQL database
$servername = 'localhost';
$username = 'root';
$password = '3321185';
$dbname = 'result_analysis';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to insert the attributes into the database
$sql = "INSERT INTO internal_exam (`ExamName`, `No_of_Parts`, `ProgramID`,`Semester`,`Subject`) VALUES ('$exam_name', '$no_of_parts','$ProgramID','$semester','$subject')";
// Retrieve the ID of the last inserted row
if ($conn->query($sql) === TRUE) {
  session_start();
  
  $_SESSION['Parts'] = 2;
} else {
    echo "Error: " . $sql ."<br>" . $conn->error;
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

  if ($conn->query($sql_1) !== TRUE) {
    echo "Error: " . $sql ."<br>" . $conn->error;
  }
}
header('Location: resultAnalysis_junk.php');
exit; 

// Close the database connection
$conn->close();
?>
