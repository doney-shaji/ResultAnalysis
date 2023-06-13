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
        <form action="question_process.php" method="post" class="form">
            <h2>Question Entry</h2>
            <label for="part_id" class="form-label">Part ID:</label>
            <input type="text" name="part_id" id="part_id" class="form-control parts" required>

            <label for="module_no" class="form-label">Module No:</label>
            <input type="text" name="module_no" id="module_no" class="form-control parts" required>

            <label for="question_text" class="form-label">Question Text:</label>
            
            <input type="text" name="question_text" id="question_text" class="form-control parts" required>

            <input type="submit" value="Submit">
        </form>
    </div>
    </center>
</body>
</html>