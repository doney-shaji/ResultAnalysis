<?php 
     session_start();
    require_once("database.php");
    $lastid = isset($_SESSION['lastid']) ? $_SESSION['lastid'] : '';
// Check if the lastid is available
    if ($lastid === '') {
        echo "Error: lastid not found.";
        exit();
    }
    $query = "SELECT * FROM internal_exam WHERE ExamID = '$lastid' ";
    $result = mysqli_query($mysqli,$query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $exam_name = $row['ExamName'];
            $ProgramID = isset($row['ProgramID']) ? $row['ProgramID'] : '';
            $semester = isset($row['Semester']) ? $row['Semester'] : '';
            $subject = $row['CourseID'];
            $batch = $row['Batch'];
        } else {
            echo "No examid found for the specified criteria.";
        }
    } else {
        echo "Error executing query: " . mysqli_error($mysqli);
    }
    $query = "SELECT `StudentID`, `QuestionNo` FROM student WHERE ProgramID = '$ProgramID' AND semester = '$semester' AND Batch = '$batch' LIMIT 3";
    $result_students = mysqli_query($mysqli, $query);
    if (!$result_students) {
        die('Error executing the query: ' . mysqli_error($mysqli));
    }
    echo "<h4>Program: <small class='text-muted'>" . $ProgramID . " </small>| ";
    echo "Semester: <small class='text-muted'>" . $semester . " </small> | ";
    echo "Exam: <small class='text-muted'>" . $exam_name . " </small>| ";
    echo "Course: <small class='text-muted'>" . $subject . " </small>| ";
    echo "Batch: <small class='text-muted'>" . $batch . " </small></h4>";
    $q = "SELECT NoOfQst FROM internal_exam_part WHERE LastID = '$lastid'";
    $r = mysqli_query($mysqli, $q);
    if ($r) {
        $values = array();
        while ($ro = mysqli_fetch_assoc($r)) {
            $numberOfQuestions = $ro['NoOfQst'];
            $values[] = $numberOfQuestions;
        }
    } else {
        echo "Error executing query: " . mysqli_error($mysqli);
    }
    $choice = "SELECT QstChoice FROM internal_exam_part WHERE LastID = '$lastid'";
    $choice_query = mysqli_query($mysqli, $choice);
    if ($choice_query) {
        $choice_values = array();
        while ($choice_row = mysqli_fetch_assoc($choice_query)) {
            $selected_choice = $choice_row['QstChoice'];
            $choice_array[] = $selected_choice;
        }
    } else {
        echo "Error executing query: " . mysqli_error($mysqli);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>View Records</title>
</head>
<body class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col m-auto">
                    <div class="card mt-5">
                        <table class="table table-bordered">
                            <tr>
                            <th rowspan='2'>Name</th>
                            <th rowspan="2">Roll Number</th>
                                <?php
                                    foreach ($values as $index => $value){
                                        $choice_selected = $choice_array[$index];
                                        if($choice_selected === 'No'){
                                            echo '<th colspan="'.$value. '> Part A</th>';
                                        }
                                        elseif ($choice_selected === 'Yes'){
                                            echo '<th colspan="'.($value*2). '> Part B</th>';
                                        }
                                    }
                                    echo '<th>EDIT</th>';
                                    echo "</tr>";
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
                                    while ($row = mysqli_fetch_assoc($result_students)) {
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
                                                        echo '<input style="width:45px" placeholder="" type="text" name="' . $inputName . '" value="0" onfocus="if (this.value === \'0\') this.value = \'\';" onblur="choiceNO(this)" required/>';
                                                        echo '</td>';
                                                        $pointer = $pointer + 1;
                                                    } elseif ($choice_selected === 'Yes') {
                                                        echo '<td class="choiceYes">';
                                                        $inputName1 = 'marks_yes[' . $row['StudentID'] . '][' . $pointer . ']';
                                                        $inputName2 = 'marks_yes[' . $row['StudentID'] . '][' . ($pointer + 1) . ']';
                                                        $defaultValue1 = isset($_POST['marks_yes'][$row['StudentID']][$pointer]) ? $_POST['marks_yes'][$row['StudentID']][$pointer] : '';
                                                        $defaultValue2 = isset($_POST['marks_yes'][$row['StudentID']][$pointer + 1]) ? $_POST['marks_yes'][$row['StudentID']][$pointer + 1] : '';
                                                        echo '<input style="width:45px" placeholder="" type="text" name="' . $inputName1 . '" value="0" onfocus="if (this.value === \'0\') this.value = \'\';" onblur="choiceYES(this)" required/></td>';
                                                        echo '<td class="choiceYes">';
                                                        echo '<input style="width:45px" placeholder="" type="text" name="' . $inputName2 . '" value="0" onfocus="if (this.value === \'0\') this.value = \'\';" onblur="choiceYES(this)" required/>';
                                                        echo '</td>';
                                                        $pointer = $pointer + 2;
                                                    }
                                
                                                }
                                            }
                                        }
                                        echo '<td><input type="text" style="width:45px" class="total-marks" name ="total['. $row['StudentID'] .']" placeholder="" required readonly></td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                ?>
                            </tr>
                            <?php   
                                    while($row=mysqli_fetch_assoc($result))
                                    {
                                        $UserID = $row['User_ID'];
                                        $UserName = $row['User_Name'];
                                        $UserEmail = $row['User_Email'];
                                        $UserAge = $row['User_Age'];
                            ?>
                                    <tr>
                                        <td><?php echo $UserID ?></td>
                                        <td><?php echo $UserName ?></td>
                                        <td><?php echo $UserEmail ?></td>
                                        <td><?php echo $UserAge ?></td>
                                        <td><a href="edit.php?GetID=<?php echo $UserID ?>">Edit</a></td>
                                        <td><a href="delete.php?Del=<?php echo $UserID ?>">Delete</a></td>
                                    </tr>        
                            <?php 
                                    }  
                            ?>                                                                    
                                   

                        </table>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html>