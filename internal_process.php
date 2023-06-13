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

if ($conn->query($sql) === TRUE) {
  echo "Attributes added successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
