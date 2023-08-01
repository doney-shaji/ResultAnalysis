<<<<<<< HEAD
<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "3321185";
$dbname = "result_analysis";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$program = $_POST['program'];
$batch = $_POST['batch'];
$semester = $_POST['semester'];
$exam_name = $_POST['exam_type'];
$subject = $_POST['subject'];
// $program = 'IT';
// $batch = '2020-2024';
// $semester = 'S5';
// $exam_name = 'Series 1';
// $subject = '100004/IT500B OPERATING SYSTEM CONCEPTS';


// Query to get examid from internal_Exam table based on selected options
$sql_exam = "SELECT ExamID FROM internal_exam 
             WHERE ProgramID = '$program' AND Batch = '$batch' AND Semester = '$semester' AND ExamName = '$exam_name' AND CourseID = '$subject'";

$result_exam = $conn->query($sql_exam);

if ($result_exam->num_rows > 0) {
    $row_exam = $result_exam->fetch_assoc();
    $examid = $row_exam['ExamID'];

    // Query to get module analysis data from student_module_analysis table based on ExamID
    $sql_module_analysis = "SELECT ModuleID, SUM(weakerModule = 0) as strong_count, SUM(weakerModule = 1) as weak_count
                        FROM student_module_analysis 
                        WHERE ExamID = $examid
                        GROUP BY ModuleID";

    $result_module_analysis = $conn->query($sql_module_analysis);

    $module_numbers = array(); // An array to store module numbers
    $weak_count = array(); // An array to store counts for weak modules
    $strong_count = array(); // An array to store counts for strong modules

    if ($result_module_analysis->num_rows > 0) {
        while ($row = $result_module_analysis->fetch_assoc()) {
            // Store data in arrays
            $module_numbers[] = "Module " . $row["ModuleID"];
            $strong_count[] = $row["strong_count"];
            $weak_count[] = $row["weak_count"];
        }
    }

    // Prepare the response array
    $response = [
        'module_numbers' => $module_numbers,
        'weak_count' => $weak_count,
        'strong_count' => $strong_count
    ];
    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "No data found.";
}

// Close the database connection
$conn->close();
?>
=======
<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "3321185";
$dbname = "result_analysis";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$program = $_POST['program'];
$batch = $_POST['batch'];
$semester = $_POST['semester'];
$exam_name = $_POST['exam_type'];
$subject = $_POST['subject'];
// $program = 'IT';
// $batch = '2020-2024';
// $semester = 'S5';
// $exam_name = 'Series 1';
// $subject = '100004/IT500B OPERATING SYSTEM CONCEPTS';


// Query to get examid from internal_Exam table based on selected options
$sql_exam = "SELECT ExamID FROM internal_exam 
             WHERE ProgramID = '$program' AND Batch = '$batch' AND Semester = '$semester' AND ExamName = '$exam_name' AND CourseID = '$subject'";

$result_exam = $conn->query($sql_exam);

if ($result_exam->num_rows > 0) {
    $row_exam = $result_exam->fetch_assoc();
    $examid = $row_exam['ExamID'];

    // Query to get module analysis data from student_module_analysis table based on ExamID
    $sql_module_analysis = "SELECT ModuleID, SUM(weakerModule = 0) as strong_count, SUM(weakerModule = 1) as weak_count
                        FROM student_module_analysis 
                        WHERE ExamID = $examid
                        GROUP BY ModuleID";

    $result_module_analysis = $conn->query($sql_module_analysis);

    $module_numbers = array(); // An array to store module numbers
    $weak_count = array(); // An array to store counts for weak modules
    $strong_count = array(); // An array to store counts for strong modules

    if ($result_module_analysis->num_rows > 0) {
        while ($row = $result_module_analysis->fetch_assoc()) {
            // Store data in arrays
            $module_numbers[] = "Module " . $row["ModuleID"];
            $strong_count[] = $row["strong_count"];
            $weak_count[] = $row["weak_count"];
        }
    }

    // Prepare the response array
    $response = [
        'module_numbers' => $module_numbers,
        'weak_count' => $weak_count,
        'strong_count' => $strong_count
    ];
    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "No data found.";
}

// Close the database connection
$conn->close();
?>
>>>>>>> 11c8991397b320f3c82a60ca23f6933420eafa42
