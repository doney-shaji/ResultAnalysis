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
    <script src="javascript.js"></script>
    <style>
        
    </style>
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
    // Connect to the database
    $host = 'localhost';
    $username = 'root';
    $password = '3321185';
    $database = 'result_analysis';
    
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die('Could not connect to the database: ' . mysqli_connect_error());
    }
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process the submitted form data
        
        // Iterate over the submitted marks
        foreach ($_POST['marks'] as $studentId => $marks) {
            // Sanitize the input (optional)
            $sanitizedStudentId = mysqli_real_escape_string($conn, $studentId);
            
            // Update the database with the submitted marks
            $query = "UPDATE students SET marks = '" . implode(',', $marks) . "' WHERE id = $sanitizedStudentId";
            $result = mysqli_query($conn, $query);
            
            if (!$result) {
                echo 'Error updating marks for student ID ' . $studentId . ': ' . mysqli_error($conn) . '<br>';
            }
        }
    }
    
    // Fetch the student data from the database
    $query = "SELECT id, name, roll_number FROM students";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die('Error executing the query: ' . mysqli_error($conn));
    }
    
    if (isset($_SESSION['id']) && isset($_SESSION['Parts'])) {
        $id = $_SESSION['id']; // Retrieve the 'id' value
        $no_of_parts = $_SESSION['Parts'];
    } else {
        echo "The 'id' value is not set in the session.";
    }

    // Prepare the SQL query
    $q = "SELECT NoOfQst FROM internal_exam_part WHERE lastid = '$id'";
    // Execute the query
    $r = mysqli_query($conn, $q);
    // Check if the query was successful
    if ($r) {
        // Initialize an array to store the values
        $values = array();
        // Fetch each row as an associative array
        while ($ro = mysqli_fetch_assoc($r)) {
            // Access the value of the number_of_questions column
            $numberOfQuestions = $ro['NoOfQst'];
            // Add the value to the array
            $values[] = $numberOfQuestions;
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
        echo '<th colspan="'.$value. '">Part '.chr(65 + ($index)).'</th>';
    }
    echo '</tr><tr>';
    foreach ($values as $index => $value){
        if($value!=0){
            for($i=1;$i<=$value;$i++){
                echo '<th>Q'.($value).'</th>';
            }
        }
    }
    echo '</tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['name'] . ' </td>';
        echo '<td>' . $row['roll_number'] . ' </td>';
        
        
        // Generate input boxes for marks
        
        foreach ($values as $index => $value) {
            if($value!=0){
                    for($i=1;$i<=$value;$i++){
                        echo '<td>';
                        $inputName = 'marks[' . $row['id'] . '][' . $i . ']';
                        $defaultValue = isset($_POST['marks'][$row['id']][$i]) ? $_POST['marks'][$row['id']][$i] : '';
                        echo '<input style="width:55px" placeholder="Marks" type="text" name="' . $inputName . '" value="' . $defaultValue . '" />';
                        echo '</td>';
                    }
                }
            }
        }
        
        
        echo '</tr>';
    
    
    echo '</table>';
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    
    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
