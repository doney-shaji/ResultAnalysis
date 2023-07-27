<?php
// require __DIR__. "/vendor/autoload.php";

// use Dompdf\Dompdf;

// $html = "<h1>Example</h1>";
// $html .= "hello <em>world</em>";
// $dompdf = new Dompdf;

// $dompdf->loadHtml($html);

// $dompdf->render();

// $dompdf->stream("invoice.pdf", ["Attachment"=>0]);
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

$query_failed_students = "SELECT remedial_list.StudentID, remedial_list.ExamID
                         FROM remedial_list
                         INNER JOIN internal_exam ON remedial_list.ExamID = internal_exam.ExamID
                         WHERE internal_exam.Batch = ? 
                         AND internal_exam.ProgramID = ? 
                         AND internal_exam.CourseID = ? 
                         AND internal_exam.Semester = ? 
                         AND internal_exam.ExamName = ?";

// Prepare and bind parameters for the query
$stmt_failed_students = mysqli_prepare($conn, $query_failed_students);
mysqli_stmt_bind_param($stmt_failed_students, "sssss", $batch, $ProgramID, $subject, $semester, $exam_name);
mysqli_stmt_execute($stmt_failed_students);

// Get the result of the query
$result_failed_students = mysqli_stmt_get_result($stmt_failed_students);


// Check if any rows are returned
if ($result_failed_students && mysqli_num_rows($result_failed_students) > 0) {
    // Generate the content for the PDF
$pdf_content = '<h4>Program: <small class="text-muted">' .$ProgramID. ' </small>| ';
$pdf_content .='Semester: <small class="text-muted">' . $semester . '</small> | ';
$pdf_content .='Exam: <small class="text-muted">' . $exam_name . ' </small>| ';
$pdf_content .='Subject: <small class="text-muted">' . $subject . '</small>| ';
$pdf_content .='Batch: <small class="text-muted">' . $batch . '</small></h4>';
    $pdf_content .= '<h3>Remedial List:</h3>';
    $pdf_content .= '<table border="1">';
    $pdf_content .= '<thead><tr><th>UID</th><th>Name</th><th>Marks</th></tr></thead>';
    $pdf_content .= '<tbody>';


    // Append the details of failed students
    while ($row_failed_students = mysqli_fetch_assoc($result_failed_students)) {
        $studentID = $row_failed_students['StudentID'];
        $subjectID = $row_failed_students['ExamID'];
    
        // Fetch the name of the student from the student table based on StudentID
        $query_student_name = "SELECT StudentName FROM student WHERE StudentID = ?";
        $stmt_student_name = mysqli_prepare($conn, $query_student_name);
        mysqli_stmt_bind_param($stmt_student_name, "s", $studentID);
        mysqli_stmt_execute($stmt_student_name);
        $result_student_name = mysqli_stmt_get_result($stmt_student_name);
    
        // Fetch the name of the student (assuming StudentID is unique in the student table)
        $student_name = '';
        if ($result_student_name && mysqli_num_rows($result_student_name) > 0) {
            $row_student_name = mysqli_fetch_assoc($result_student_name);
            $student_name = $row_student_name['StudentName'];
        } // Store the value in a variable

        // Fetch the marks of the student from the marks table based on StudentID, ExamID, and QuestionNo
        $query_student_marks = "SELECT TotalMarks FROM marks_total WHERE StudentID = ? AND ExamID = ?";
        $stmt_student_marks = mysqli_prepare($conn, $query_student_marks);
        
        // Pass the variables by reference in mysqli_stmt_bind_param()
        mysqli_stmt_bind_param($stmt_student_marks, "ss", $studentID, $subjectID);
        mysqli_stmt_execute($stmt_student_marks);
        $result_student_marks = mysqli_stmt_get_result($stmt_student_marks);
        $marks_obtained = '';
        if ($result_student_marks && mysqli_num_rows($result_student_marks) > 0) {
            $row_student_marks = mysqli_fetch_assoc($result_student_marks);
            $marks_obtained = $row_student_marks['TotalMarks'];
        }
    
        // Append the student's details and marks to the PDF content as a table row
        $pdf_content .= '<tr><td>' . $studentID . '</td><td>' . $student_name . '</td><td>' . $marks_obtained . '</td></tr>';
    }
    
    $pdf_content .= '</tbody>';
    $pdf_content .= '</table>';
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
    echo '<p>No students failed.</p>';
}

    
mysqli_close($conn);
?>