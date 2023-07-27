<?php
session_start();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
    <script>
        function myFunction() {
            var totalInputs = document.getElementsByClassName("total");
            var errorMessages = document.getElementsByClassName("error");

            for (var i = 0; i < totalInputs.length; i++) {
                var total = totalInputs[i].value;
                var error = errorMessages[i];

                if (total > 50) {
                    error.textContent = "Oops! Value exceeds 50";
                    totalInputs[i].value = "";
                } else {
                    error.textContent = "";
                }
            }
        }
    </script>
    
</script>

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
                </li>
                <li class="nav-item">
                </li>
                <li class="nav-item">
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
$exam_name = $_POST['exam_type'];
$ProgramID = isset($_POST['program']) ? $_POST['program'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$subject = $_POST['subject'];
$batch = $_POST['batch'];

echo "<h4>Program: <small class='text-muted'>" . $ProgramID . " </small>| ";
echo "Semester: <small class='text-muted'>" . $semester . " </small> | ";
echo "Exam: <small class='text-muted'>" . $exam_name . " </small>| ";
echo "Course: <small class='text-muted'>" . $subject . " </small>| ";
echo "Batch: <small class='text-muted'>" . $batch . " </small></h4>";

$query = "SELECT `StudentID`, `StudentName` FROM student WHERE ProgramID = '$ProgramID' AND semester = '$semester' AND Batch = '$batch' LIMIT 10";
    $result_students = mysqli_query($mysqli, $query);
    if (!$result_students) {
        die('Error executing the query: ' . mysqli_error($mysqli));
    }
    $query_examid = "SELECT ExamID FROM internal_exam WHERE Batch = '$batch' AND ProgramID = '$ProgramID' AND Semester = '$semester' AND CourseID = '$subject' AND ExamName = '$exam_name'";
    $result = mysqli_query($mysqli, $query_examid);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $examId = $row['ExamID'];
            $_SESSION["lastid"] = $examId;
        } else {
            echo "No examid found for the specified criteria.";
        }
    } else {
        echo "Error executing query: " . mysqli_error($mysqli);
    }

// Display the student data in a table
echo '<form method="POST" action="resultAnalysis_totalMarksProcess.php">';
echo '<table class="table">';
echo '<tr><th rowspan="">UID</th><th rowspan="">NAME</th>';
echo '<th>| Total</th><th></th>';
echo '</tr>';
$m = 1;
while ($row = mysqli_fetch_assoc($result_students)) {
    echo '<tr>';
    echo '<td>' . $row['StudentID'] . ' </td>';
    echo '<td>' . $row['StudentName'] . ' </td>';

    // Generate input boxes for marks with array-like names
    echo '<td><input type="number" style="width:45px" name="total[' . $row['StudentID'] . '][1]" class="total" onblur="myFunction()" required></td>';
    echo '<td><p class="error" style="color: red;"></p></td>'; 
    echo '</tr>';
    $m = $m + 1;
}
echo '</table>';
echo '<input type="submit" value="Submit" />';
echo '</form>';

// Close the database mysqliection

mysqli_close($mysqli);
?>
</body>
</html>
