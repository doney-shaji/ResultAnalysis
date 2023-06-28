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
            <!-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"> -->
                <!-- <button onclick="window.location.href='logout.php';" class="btn btn-outline-danger" >Log out</button> -->
                <?php if (isset($user)): ?>
                    <a href="logout.php" class="btn btn-outline-danger">Log out</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-danger">Log in</a>
                <!-- <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a> or or Enter the <a href="C:\xampp\htdocs\Result_analysis\mark_entry.php">Marks</a></p> -->
                
                <?php endif; ?>
                <!-- <a href="logout.php" class="btn btn-outline-danger">Log out</a> -->
            </form>
        </div>
    </div>
</nav>
<center>
    <div class="container">
    <form method="POST"  name="reg_form"  action = "internal_process.php" class="form">
        <h2>Internal Entry</h2>
        <select name="program" id="program" onchange="populateSubjects()">
            <option value="">(Select PROGRAM)</option>
            <option value="AEI">APPLIED ELECTRONICS AND INSTRUMENTATION ENGINEERING</option>
            <option value="AD">ARTIFICIAL INTELLIGENCE AND DATA SCIENCE</option>
            <option value="CE">CIVIL ENGINEERING</option>
            <option value="CSE">COMPUTER SCIENCE AND ENGINEERING</option>
            <option value="EEE">ELECTRICAL AND ELECTRONICS ENGINEERING</option>
            <option value="ECE">ELECTRONICS AND COMMUNICATION ENGINEERING</option>
            <option value="IT">INFORMATION TECHNOLOGY</option>
            <option value="ME">MECHANICAL ENGINEERING</option>
        </select>

        <select class="semester" name="semester" id="semester" onchange="populateSubjects()">
            <option value="">(Select Semester)</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
            <option value="S4">S4</option>
            <option value="S5">S5</option>
            <option value="S6">S6</option>
            <option value="S7">S7</option>
            <option value="S8">S8</option>
        </select>

        <select name="subject" id="subject">
            <option value="">Please select a branch and semester</option>
        </select>
       
        <select class="exam_type" name="exam_type" id="exam_type">
            <option value="">Exam Name:</option>
            <option>Series 1</option>
            <option>Series 2</option>
        </select>
<!--         
        <label for="total_marks">Total Marks:</label>
        <input type="text" name="total_marks" class="form-control total_marks" /> -->
        <br>
        <input type="number" name="parts" class="parts" placeholder="Number of Parts: " id="number-of-parts" min="1" onchange="displayInputFields()"/>

        <div id="input-container"></div>
        
        



        
        <input type="submit" name="submit" value="submit" id="submit-select">
    </form>
    </div>
    </center>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </body>
</html>





    