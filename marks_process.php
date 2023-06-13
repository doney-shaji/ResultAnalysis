<?php
// Retrieve the form attributes from the $_POST array
$student_id = $_POST['student_id'];
$exam_id = $_POST['exam_id'];
$part_id = $_POST['part_id'];
$question_id = $_POST['question_id'];
$marks_obtained = $_POST['marks_obtained'];

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
$sql = "INSERT INTO marks (`StudentID`,`ExamID`,`PartID`,`QuestionID`,`MarksObtained`) VALUES ('$student_id','$exam_id','$part_id','$question_id','$marks_obtained')";

if ($conn->query($sql) === TRUE) {
  echo "<h3>Attributes added successfully</h3>";
  
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
