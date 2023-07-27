<?php
// fetch_data.php

$connect = mysqli_connect("localhost", "root", "3321185", "result_analysis");
if (!$connect) {
    die('Could not connect to the database: ' . mysqli_connect_error());
}

$selectedProgram = $_GET['program'];
$selectedSemester = $_GET['semester'];
$selectedExamType = $_GET['exam_type'];
$selectedBatch = $_GET['batch'];

$query = "SELECT ie.CourseID, 
                 CASE 
                    WHEN tm.TotalMarks >= 0 AND tm.TotalMarks <= 10 THEN '0-10' 
                    WHEN tm.TotalMarks > 10 AND tm.TotalMarks <= 20 THEN '11-20'
                    WHEN tm.TotalMarks > 20 AND tm.TotalMarks <= 30 THEN '21-30'
                    WHEN tm.TotalMarks > 30 AND tm.TotalMarks <= 40 THEN '31-40'
                    WHEN tm.TotalMarks > 40 AND tm.TotalMarks <= 50 THEN '41-50'
                 END AS marks_range,
                 COUNT(*) AS count
          FROM internal_exam ie
          INNER JOIN marks_total tm ON ie.ExamID = tm.ExamID
          WHERE ie.ProgramID = ? 
          AND ie.Semester = ?
          AND ie.ExamName = ?
          AND ie.Batch = ?
          GROUP BY ie.CourseID, marks_range";

$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "ssss", $selectedProgram, $selectedSemester, $selectedExamType, $selectedBatch);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$chart_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $subject = $row["CourseID"];
    $marks_range = $row["marks_range"];
    $count = $row["count"];

    if (!isset($chart_data[$subject])) {
        $chart_data[$subject] = array(
            'CourseID' => $subject,
            '0-10' => 0,
            '11-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
        );
    }

    $chart_data[$subject][$marks_range] = $count;
}

$chart_data_json = json_encode(array_values($chart_data));

mysqli_close($connect);

header('Content-Type: application/json');
echo $chart_data_json;
?>
