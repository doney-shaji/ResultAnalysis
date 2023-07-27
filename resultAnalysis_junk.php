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
    $host = 'localhost';
    $username = 'root';
    $password = '3321185';
    $database = 'result_analysis';
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die('Could not connect to the database: ' . mysqli_connect_error());
    }
    // && isset($_SESSION['program']) && isset($_SESSION['semester']) && isset($_SESSION['subject']) && isset($_SESSION['examName']))
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id']; 
        $program = $_SESSION['program'];
        $semester = $_SESSION['semester'];
    } else {
        echo "The 'id' value is not set in the session.";
    }
    $query_details = "SELECT ProgramID, Semester, ExamName, `CourseID` FROM internal_exam WHERE ExamID = '$id'";
    $result_details = mysqli_query($conn, $query_details);

    // Check if any rows are returned
    if (mysqli_num_rows($result_details) > 0) {
        // Fetch data and display it
        $row_details = mysqli_fetch_assoc($result_details);
        echo "<h4>Program: <small class='text-muted'>" . $row_details['ProgramID'] . " </small>| ";
        echo "Semester: <small class='text-muted'>" . $row_details['Semester'] . "</small> | ";
        echo "Exam: <small class='text-muted'>" . $row_details['ExamName'] . " </small>| ";
        echo "Course: <small class='text-muted'>" . $row_details['CourseID']. "</small></h4>";
    } else {
        echo "No records found.";
    }
    $query = "SELECT `StudentID`, `StudentName` FROM student WHERE ProgramID = '$program' AND semester = '$semester' LIMIT 2";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('Error executing the query: ' . mysqli_error($conn));
    }
    $q = "SELECT NoOfQst FROM internal_exam_part WHERE lastid = '$id'";
    $r = mysqli_query($conn, $q);
    if ($r) {
        $values = array();
        while ($ro = mysqli_fetch_assoc($r)) {
            $numberOfQuestions = $ro['NoOfQst'];
            $values[] = $numberOfQuestions;
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
    $choice = "SELECT QstChoice FROM internal_exam_part WHERE lastid = '$id'";
    $choice_query = mysqli_query($conn, $choice);
    if ($choice_query) {
        // Initialize an array to store the values
        $choice_values = array();
        // Fetch each row as an associative array
        while ($choice_row = mysqli_fetch_assoc($choice_query)) {
            // Access the value of the number_of_questions column
            $selected_choice = $choice_row['QstChoice'];
            // Add the value to the array
            $choice_array[] = $selected_choice;
        }
    } else {
        // Query execution failed
        echo "Error executing query: " . mysqli_error($conn);
    }
        // Display the student data in a table
    echo '<form method="POST">';
    echo '<table class="table">';
    echo '<tr><th rowspan="2">Name</th><th rowspan="2">Roll Number</th>';
    foreach ($values as $index => $value){
        $choice_selected = $choice_array[$index];
        if($choice_selected === 'No'){
            echo '<th colspan="'.$value. 'style="text-align: center;"> Part '.chr(65 + ($index)).'</th>';
        }
        elseif ($choice_selected === 'Yes'){
            echo '<th colspan="'.($value*2). 'style="text-align: right;"> Part '.chr(65 + ($index)).'</th>';
        }
    }
    echo '<th><button id="total-button">Total</button></th></tr><tr>';
    $j = 1;
    foreach ($values as $index => $value){
        $choice_selected = $choice_array[$index];
        if($value!=0){
            for($i=1;$i<=$value;$i++){
                if($choice_selected === 'No'){
                    echo '<th>Q'.($j).'</th>';
                    $j = $j + 1;
                }
                elseif ($choice_selected === 'Yes') {
                    for($k=1;$k<=2;$k++){
                        echo '<th>Q'.($j).'</th>';
                        $j = $j + 1;
                    }
                }
            }
        }
    }
    echo '</tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['StudentID'] . ' </td>';
        echo '<td>' . $row['StudentName'] . ' </td>';
        
        foreach ($values as $index => $value) {
            $pointer = 0;
            $choice_selected = $choice_array[$index];
            if ($value != 0) {
                for ($i = 1; $i <= $value; $i++) {
                    if ($choice_selected === 'No') {
                        echo '<td class="choiceNo">';
                        $inputName = 'marks_no[' . $row['StudentID'] . '][' . $pointer . ']';
                        $defaultValue = isset($_POST['marks_no'][$row['StudentID']][$pointer]) ? $_POST['marks_no'][$row['StudentID']][$pointer] : '';
                        echo '<input style="width:45px" placeholder="" type="text" name="' . $inputName . '" value="0" onfocus="if (this.value === \'0\') this.value = \'\';" onblur="if (this.value === \'\') this.value = \'0\';" required/>';
                        echo '</td>';
                        $pointer = $pointer + 1;
                    } elseif ($choice_selected === 'Yes') {
                        echo '<td class="choiceYes">';
                        $inputName1 = 'marks_yes[' . $row['StudentID'] . '][' . $pointer . ']';
                        $inputName2 = 'marks_yes[' . $row['StudentID'] . '][' . ($pointer + 1) . ']';
                        $defaultValue1 = isset($_POST['marks_yes'][$row['StudentID']][$pointer]) ? $_POST['marks_yes'][$row['StudentID']][$pointer] : '';
                        $defaultValue2 = isset($_POST['marks_yes'][$row['StudentID']][$pointer + 1]) ? $_POST['marks_yes'][$row['StudentID']][$pointer + 1] : '';
                        echo '<input style="width:45px" placeholder="" type="text" name="' . $inputName1 . '" value="0" onfocus="if (this.value === \'0\') this.value = \'\';" onblur="if (this.value === \'\') this.value = \'0\';" required/></td>';
                        echo '<td class="choiceYes">';
                        echo '<input style="width:45px" placeholder="" type="text" name="' . $inputName2 . '" value="0" onfocus="if (this.value === \'0\') this.value = \'\';" onblur="if (this.value === \'\') this.value = \'0\';" required/>';
                        echo '</td>';
                        $pointer = $pointer + 2;
                    }

                }
            }
        }
        echo '<td><input type="text" style="width:45px" class="total-marks" name ="total['. $row['StudentID'] .']" placeholder="" required readonly></td>';
        echo '</tr>';
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $no_of_questions_completed_no = 0;
        foreach ($_POST['marks_no'] as $uid => $marks) {
            foreach ($marks as $markIndex => $mark) {
                $questionID = $markIndex+1;
                $mark = isset($marks[$markIndex]) ? floatval($marks[$markIndex]) : 0.0;
                $insertQuery = "INSERT INTO marks (`StudentID`, QuestionNo, ExamID, MarksObtained) VALUES ('" . $uid . "', '" . $questionID . "', '" . $id . "', '" . $mark . "')";
                $no_of_questions_completed_no = $no_of_questions_completed_no + 1;
                mysqli_query($conn, $insertQuery);
                
            }
        }
        foreach ($_POST['marks_yes'] as $uid => $marks) {
            foreach ($marks as $markIndex => $mark) {
                if (($markIndex) % 2 === 0) {
                    $questionID = $markIndex + $no_of_questions_completed_no;
                    $mark1 = isset($marks[$markIndex]) ? floatval($marks[$markIndex]) : 0.0;
                    $mark2 = isset($marks[$markIndex+1]) ? floatval($marks[$markIndex+1]) : 0.0;
                    $mark = $mark1 > $mark2 ? $mark1 : $mark2;
                    $questionID = $mark1 >= $mark2 ? $markIndex + $no_of_questions_completed_no - 1 : $markIndex + 1 + $no_of_questions_completed_no - 1;
                    $insertQuery = "INSERT INTO marks (`StudentID`, QuestionNo, ExamID, MarksObtained) VALUES ('" . $uid . "', '" . $questionID . "', '" . $id . "', '" . $mark . "')";
                    mysqli_query($conn, $insertQuery);
                }
            }
            $totalMarks = isset($_POST['total'][$uid]) ? floatval($_POST['total'][$uid]) : 0.0;
            $insertTotalMarksQuery = "INSERT INTO marks (`StudentID`, QuestionNo, ExamID, MarksObtained) VALUES ('" . $uid . "', 'TotalMarks', '" . $id . "', '" . $totalMarks . "')";
            mysqli_query($conn, $insertTotalMarksQuery);
        }
    }
    
    echo '</table>';
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    mysqli_close($conn);
    ?>
    <script>
        function calculateTotal() {
                var rows = document.querySelectorAll('table.table tbody tr'); // Get all table rows
                rows.forEach(function(row) {
                    var marksInputs;
                    var totalMarks = 0.0;
                    
                    marksInputs_n = row.querySelectorAll('.choiceNo input[type="text"]');
                    marksInputs_n.forEach(function(input) {
                        var mark = parseFloat(input.value) || 0.0;
                        totalMarks += mark;
                    });
                    marksInputs_y = row.querySelectorAll('.choiceYes input[type="text"]');
                    marksInputs_y.forEach(function(input, index) {
                        if (index % 2 === 0) {
                            var mark1 = parseFloat(input.value) || 0.0;
                            var mark2 = parseFloat(marksInputs_y[index + 1].value) || 0.0;
                            var mark = mark1 > mark2 ? mark1 : mark2;
                            totalMarks += mark;
                        }
                    });
                    var totalInput = row.querySelector('.total-marks');
                    if (totalInput) {
                    totalInput.value = totalMarks.toFixed(1);
                    }
                    var errorElement = row.querySelector('.error');
                    if (errorElement) {
                        if (totalMarks > 50) {
                            alert('Oops! Value exceeds 50');
                            totalInput.value = '';
                        } else {
                            errorElement.textContent = '';
                        }
                    }
                });
                }
            window.addEventListener('DOMContentLoaded', function() {
            var totalButton = document.querySelector('#total-button');
            totalButton.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent form submission
                    var totalInputs = document.querySelectorAll('.total-marks');
                    totalInputs.forEach(function (input) {
                    input.value = ''; // Clear the total marks field before calculating the new total
                    });
                    calculateTotal();
                });
            });
            function validateForm(event) {
                var totalInputs = document.querySelectorAll('.total-marks');
                for (var i = 0; i < totalInputs.length; i++) {
                    var totalInput = totalInputs[i];
                    if (totalInput.value === '') {
                        event.preventDefault(); // Prevent form submission
                        alert('Please fill in all the total marks fields.');
                        return;
                    }
                }
            }
            
            document.querySelector('form').addEventListener('submit', validateForm);
    </script>
</body>
</html>
