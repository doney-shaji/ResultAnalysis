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
    $exam_name = $_POST['exam_type'];
    $ProgramID = isset($_POST['program']) ? $_POST['program'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $subject = $_POST['subject'];
    $batch = $_POST['batch'];
    echo "<h4>Program: <small class='text-muted'>" .$ProgramID. " </small>| ";
    echo "Semester: <small class='text-muted'>" . $semester . "</small> | ";
    echo "Exam: <small class='text-muted'>" . $exam_name . " </small>| ";
    echo "Subject: <small class='text-muted'>" . $subject . "</small>| ";
    echo "Batch: <small class='text-muted'>" . $batch . "</small></h4>";
    
    $lastid_query = "SELECT ExamID FROM internal_exam WHERE Batch = '$batch' AND ProgramID = '$ProgramID' AND CourseID = '$subject' AND Semester = '$semester' AND ExamName = '$exam_name'";
    $result_lastid = mysqli_query($conn, $lastid_query);
    if (mysqli_num_rows($result_lastid) > 0) {
        $row_lastid = mysqli_fetch_assoc($result_lastid);
        $lastid = $row_lastid['ExamID'];
        $_SESSION['lastid'] = $lastid;
        // Query to retrieve QstChoice from internal_exam_part based on lastid
        $choice_query = "SELECT QstChoice FROM internal_exam_part WHERE LastID = '$lastid'";
        $result_choice = mysqli_query($conn, $choice_query);

        if ($result_choice) {
            $choice_values = array();
            while ($choice_row = mysqli_fetch_assoc($result_choice)) {
                $selected_choice = $choice_row['QstChoice'];
                // Add the value to the array
                $choice_array[] = $selected_choice;
            }
        } else {
            echo "Error executing query: " . mysqli_error($conn);
        }
    } else {
        echo "No records found.";
    }
    $query_details = "SELECT NoOfQst FROM internal_exam_part WHERE LastID = '$lastid'";
    $result_details = mysqli_query($conn, $query_details);
    // Check if any rows are returned
    if ($result_details) {
        $values = array();
        while ($ro = mysqli_fetch_assoc($result_details)) {
            $numberOfQuestions = $ro['NoOfQst'];
            $values[] = $numberOfQuestions;
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
    $sql = "SELECT PartID, QstChoice FROM internal_exam_part WHERE LastID = '$lastid'";
    $result = $conn->query($sql);

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        $partids = array();
        $qstChoices = array();
        while ($row = $result->fetch_assoc()) {
            $partids[] = $row['PartID'];
            $qstChoices[] = $row['QstChoice'];
        }
        // Store the partid and qstChoice values in $_SESSION
        $_SESSION['partids'] = serialize($partids);
        $_SESSION['qstChoices'] = serialize($qstChoices);
        $_SESSION['values'] = serialize($values);
    } else {
        // No rows found, handle the case accordingly
        $_SESSION['partids'] = array(); // Optional: Initialize an empty array
    }
        // Display the student data in a table
    echo '<form method="POST" action="resultAnalysis_questionInsertData.php">';
    echo '<table class="table">';
    echo '<tr><th></th><th>Question No. </th><th> Module No. </th> <th> Question Text </th></tr>';
    $j = 1;
    foreach ($values as $index => $value){
        $choice_selected = $choice_array[$index];
        if($value!=0){
            if($choice_selected === 'No'){
                echo '<tr><td rowspan ="'.$value.'"> Part '.chr(65 + ($index)).'</td>';
                for($i=1;$i<=$value;$i++){
                    echo '<td>Q'.($j).'</td>';
                    echo '<td class="choiceNo">';
                    $moduleName = 'Question[' . $j - 1 . '][0]';
                    echo '<input class="form-control" style="width:45px" placeholder="" type="text" name="' . $moduleName . '" required/></td>';
                    $inputName = 'Question[' . $j - 1 . '][1]';
                    echo '<td><input class="form-control" style="width:95%" placeholder="" type="text" name="' . $inputName . '" required/>';
                    echo '</td></tr>';
                    $j = $j + 1;
                }
            }
            elseif ($choice_selected === 'Yes') {
                echo '<tr><td <td rowspan ="'.($value*2).'"> Part '.chr(65 + ($index)).'</th>';
                for($i=1;$i<=$value;$i++){
                    for($k=1;$k<=2;$k++){
                        echo '<td>Q'.($j).'</td>';
                        echo '<td class="choiceYess">';
                        $moduleName = 'Question[' . $j - 1 . '][0]';
                        echo '<input class="form-control" style="width:45px" placeholder="" type="text" name="' . $moduleName . '" required/></td>';
                        $inputName = 'Question[' . $j - 1 . '][1]';
                        echo '<td><textarea class="form-control" id="floatingTextarea" name="' . $inputName . '" required/></textarea>';
                        echo '</td></tr>';
                        $j = $j + 1;
                    }
                }
            }
        }
    }
    
    echo '</table>';
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    mysqli_close($conn);
    ?>
</body>
</html>
