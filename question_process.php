<?php
// Retrieve the form attributes from the $_POST array
$part_id = $_POST['part_id'];
$module_no = $_POST['module_no'];
$question_text = $_POST['question_text'];

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
$sql = "INSERT INTO question (`PartID`, `ModuleNo`,`QuestionText`) VALUES ('$part_id','$module_no','$question_text')";

if ($conn->query($sql) === TRUE) {
  echo "Attributes added successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
