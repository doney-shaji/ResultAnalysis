<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '3321185';
$database = 'result_analysis';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die('Could not connect to the database: ' . mysqli_connect_error());
}
require __DIR__. "/vendor/autoload.php";

use Dompdf\Dompdf;
$exam_name = $_POST['exam_name'];
$ProgramID = $_POST['ProgramID'];
$semester = $_POST['semester'];
$subject = $_POST['subject'];
$batch = $_POST['batch'];
// Function to retrieve the ExamID from internal_exam table based on provided values
function getExamIDFromDB($conn, $ProgramID , $semester, $batch, $exam_name, $subject) {
    $selectExamIdSql = "SELECT ExamID FROM internal_exam WHERE ProgramID = ? AND Semester = ? AND Batch = ? AND ExamName = ? AND CourseID = ?";
    $selectExamIdStmt = $conn->prepare($selectExamIdSql);
    $selectExamIdStmt->bind_param("sssss", $ProgramID , $semester, $batch, $exam_name, $subject);
    $selectExamIdStmt->execute();
    $selectExamIdResult = $selectExamIdStmt->get_result();

    // Check if ExamID is retrieved successfully
    if ($selectExamIdResult->num_rows > 0) {
        $row_examId = $selectExamIdResult->fetch_assoc();
        return $row_examId['ExamID'];
    } else {
        return null;
    }
}

// Get the ExamID based on the provided values
$examId = getExamIDFromDB($conn, $ProgramID, $semester, $batch, $exam_name, $subject);

// Function to get the details of students who have weaker modules based on the ExamID
// Function to get the details of students who have weaker modules based on the ExamID
function getWeakerModulesData($conn, $examId) {
    $weakerModulesData = array();

    // Fetch the data from the student_module_analysis table based on the ExamID and Weaker_Module_Value = 1
    $sql = "SELECT sma.StudentID, sma.ModuleID, s.StudentName FROM student_module_analysis AS sma
            INNER JOIN student AS s ON sma.StudentID = s.StudentID
            WHERE sma.ExamID = ? AND sma.weakerModule = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $examId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Loop through the result and store the data in the $weakerModulesData array
    while ($row = $result->fetch_assoc()) {
        $studentId = $row['StudentID'];
        $moduleId = $row['ModuleID'];
        $studentName = $row['StudentName'];
        $weakerModulesData[] = array(
            'student_id' => $studentId,
            'student_name' => $studentName,
            'Module' => 'Module ' . $moduleId,
        );
    }

    return $weakerModulesData;
}

// ...

if ($examId !== null) {
    // Get the details of students who have weaker modules based on the ExamID
    $weakerModulesData = getWeakerModulesData($conn, $examId);

    // Function to generate the HTML content for the PDF
    function generatePdfContent($weakerModulesData, $ProgramID, $semester, $exam_name, $subject, $batch) {
        $html = '<h4>Program: <small class="text-muted">' . $ProgramID . ' </small>| ';
        $html .= 'Semester: <small class="text-muted">' . $semester . '</small> | ';
        $html .= 'Exam: <small class="text-muted">' . $exam_name . ' </small>| ';
        $html .= 'Subject: <small class="text-muted">' . $subject . '</small>| ';
        $html .= 'Batch: <small class="text-muted">' . $batch . '</small></h4>';
        $html .= '<h4>Students with Weaker Modules</h4>';
        $html .= '<table border="1">';
        $html .= '<tr><th>Student ID</th><th>Student Name</th><th>Module</th></tr>';

        foreach ($weakerModulesData as $data) {
            $html .= '<tr>';
            $html .= '<td>' . $data['student_id'] . '</td>';
            $html .= '<td>' . $data['student_name'] . '</td>';
            $html .= '<td>' . $data['Module'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    // Generate the HTML content for the PDF
    $pdf_content = generatePdfContent($weakerModulesData, $ProgramID, $semester, $exam_name, $subject, $batch);

    $dompdf = new Dompdf;
    // Load the HTML content into Dompdf
    $dompdf->loadHtml($pdf_content);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML to PDF
    $dompdf->render();

    // Output the generated PDF
    $dompdf->stream('failed_students.pdf', ['Attachment' => 0]);
} else {
    echo "ExamID not found in internal_exam table for the provided criteria.";
}
mysqli_close($conn);
?>