<?php
session_start();
// Enable error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    $sql = "SELECT * FROM login_db
            WHERE id = {$_SESSION["user_id"]}";       
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R4</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.1/purify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
</head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">RESULT ANALYSIS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link" href="internal.php"></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="question.php"></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"></a>
                    </li>
                </ul>
            <form class="d-flex">
                <?php if (isset($user)): ?>
                    <a href="logout.php" class="btn btn-outline-danger">Log out</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-danger">Log in</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Sanitize and validate the inputs
        $exam_name = $_POST['exam_type'];
        $ProgramID = isset($_POST['program']) ? $_POST['program'] : '';
        $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
        $subject = $_POST['subject'];
        $batch = $_POST['batch'];
        $threshold = isset($_POST['threshold']) ? (int)$_POST['threshold'] : 0;
        
        $tableContent = '<h4>Program: <small class="text-muted">' . $ProgramID . ' </small>|';
        $tableContent .= 'Semester: <small class="text-muted">' . $semester . ' </small> | ';
        $tableContent .= 'Exam: <small class="text-muted">' . $exam_name . ' </small>| ';
        $tableContent .= 'Course: <small class="text-muted">' . $subject . ' </small>| ';
        $tableContent .= 'Batch: <small class="text-muted">' . $batch . ' </small></h4>';
        // Validate the threshold (Example: You can set a default value if the input is invalid)
        if ($threshold <= 0) {
            $threshold = 25; // Default threshold value if not provided or invalid
        }
    
        // Use prepared statements consistently for all queries to prevent SQL injection
        $sql = "SELECT marks_total.StudentID, marks_total.TotalMarks, internal_exam.ExamID
                FROM marks_total
                INNER JOIN internal_exam ON marks_total.ExamID = internal_exam.ExamID
                WHERE internal_exam.Batch = ?
                AND internal_exam.ProgramID = ?
                AND internal_exam.CourseID = ?
                AND internal_exam.Semester = ?
                AND internal_exam.ExamName = ?;";
    
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssss", $batch, $ProgramID, $subject, $semester, $exam_name);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $remedialStudents = array();
        $examid = null; // Variable to store the examid
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $examid = $row['ExamID'];
            while ($row = $result->fetch_assoc()) {
                if ($row['TotalMarks'] < $threshold) {
                    $remedialStudents[] = $row['StudentID'];
                }
            }
        }
        $stmt->close();
    
        // Insert remedial students into the remedial_list table with the examid
        if (!empty($remedialStudents) && $examid !== null) {
            foreach ($remedialStudents as $studentID) {
                // Use prepared statements for insertion to avoid SQL injection
                $insertSql = "INSERT INTO remedial_list (SubjectID, StudentID, ExamID) VALUES (?, ?, ?)";
                $insertStmt = $mysqli->prepare($insertSql);
                $insertStmt->bind_param("sss", $subject, $studentID, $examid);
                if ($insertStmt->execute() !== TRUE) {
                    echo "Error inserting remedial students: " . $mysqli->error;
                }
            }
            echo "Remedial students inserted successfully!";
        } else {
            echo "No remedial students found or examid not available.";
        }
    }
    ?>
    
    <form id="pdfForm" method="POST" action="generate_pdf.php" target="_blank">
        <!-- Hidden input fields to store PHP variables -->
        <input type="hidden" name="exam_name" value="<?php echo isset($_POST['exam_type']) ? $_POST['exam_type'] : ''; ?>">
        <input type="hidden" name="ProgramID" value="<?php echo isset($_POST['program']) ? $_POST['program'] : ''; ?>">
        <input type="hidden" name="semester" value="<?php echo isset($_POST['semester']) ? $_POST['semester'] : ''; ?>">
        <input type="hidden" name="subject" value="<?php echo $_POST['subject']; ?>">
        <input type="hidden" name="batch" value="<?php echo $_POST['batch']; ?>">

        <!-- Your "Generate PDF" button -->
        <button type="submit" class="btn btn-outline-secondary">Generate PDF</button>
    </form>
    <?php
    $selectDetailsSql = "SELECT remedial_list.StudentID, internal_exam.ExamID
                    FROM remedial_list
                    INNER JOIN internal_exam ON remedial_list.ExamID = internal_exam.ExamID
                    WHERE internal_exam.Batch = ?
                    AND internal_exam.ProgramID = ?
                    AND remedial_list.SubjectID = ?
                    AND internal_exam.Semester = ?;";
    $selectDetailsStmt = $mysqli->prepare($selectDetailsSql);
    $selectDetailsStmt->bind_param("ssss", $batch, $ProgramID, $subject, $semester);
    $selectDetailsStmt->execute();
    $detailsResult = $selectDetailsStmt->get_result();
    $tableContent .= '<table class="table">';
    $tableContent .= '<thead><tr><th>Student ID</th><th>Student Name</th><th>Marks Obtained</th></tr></thead>';
    $tableContent .= '<tbody>';

    // Fetch the details of the remedial students and add them to the table content
    while ($row = $detailsResult->fetch_assoc()) {
        $studentID = $row['StudentID'];
        $subjectID = $row['ExamID'];
        
        // Fetch the name of the student from the student table based on StudentID
        $query_student_name = "SELECT StudentName FROM student WHERE StudentID = ?";
        $stmt_student_name = mysqli_prepare($mysqli, $query_student_name);
        mysqli_stmt_bind_param($stmt_student_name, "s", $studentID);
        mysqli_stmt_execute($stmt_student_name);
        $result_student_name = mysqli_stmt_get_result($stmt_student_name);
        
        // Fetch the name of the student (assuming StudentID is unique in the student table)
        $student_name = '';
        if ($result_student_name && mysqli_num_rows($result_student_name) > 0) {
            $row_student_name = mysqli_fetch_assoc($result_student_name);
            $student_name = $row_student_name['StudentName'];
        } // Store the value in a variable

        // Fetch the marks of the student from the marks_total table based on StudentID and ExamID
        $query_student_marks = "SELECT TotalMarks FROM marks_total WHERE StudentID = ? AND ExamID = ?";
        $stmt_student_marks = mysqli_prepare($mysqli, $query_student_marks);
        mysqli_stmt_bind_param($stmt_student_marks, "ss", $studentID, $subjectID);
        mysqli_stmt_execute($stmt_student_marks);
        $result_student_marks = mysqli_stmt_get_result($stmt_student_marks);
        $marks_obtained = '';
        if ($result_student_marks && mysqli_num_rows($result_student_marks) > 0) {
            $row_student_marks = mysqli_fetch_assoc($result_student_marks);
            $marks_obtained = $row_student_marks['TotalMarks'];
        }
        
        // Append the student's details and marks to the table content
        $tableContent .= '<tr>';
        $tableContent .= '<td>' . $studentID . '</td>';
        $tableContent .= '<td>' . $student_name . '</td>';
        $tableContent .= '<td>' . $marks_obtained . '</td>';
        $tableContent .= '</tr>';
    }

    // Close the table
    $tableContent .= '</tbody>';
    $tableContent .= '</table>';

    // Display the inserted details, if any
    if ($detailsResult->num_rows > 0) {
        echo "<h2>Inserted Remedial Student Details:</h2>";
        echo $tableContent; 
    } else {
        echo "<p>No remedial student details found.</p>";
    }
        // Close the database connection
    mysqli_close($mysqli);
    ?>

</body>
</html>
