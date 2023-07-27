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
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .buttons button{
            margin: 5px;
        }
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
<div>
    <?php
        if(isset($_SESSION['status'])){
            ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Hey!</strong> <?php echo $_SESSION['status'];?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['status']);
        }
    ?>
</div>
<center>
    <h1>Home</h1> 
    <?php if (isset($user)): ?>
        <div class="buttons" style="width: 700px; height: 200px">
            <p>Welcome <?= htmlspecialchars($user["name"]) ?></p>
            <button class="btn btn-outline-warning" onclick="window.location.href = '/phpspreadsheet/resultAnalysis_student.php';">Student List</button>
            <button class="btn btn-outline-warning" onclick="window.location.href = 'internal.php';">Internal</button>
            <button class="btn btn-outline-warning" onclick="window.location.href = 'question.php';">Question</button>
            <button class="btn btn-outline-warning" onclick="window.location.href = 'resultAnalysis_totalMarksSelection.php';">Total Marks Entry</button>
            
            <button class="btn btn-outline-warning" onclick="window.location.href = 'resultAnalysis_RemedialList.php';">Remedial List</button>
            <button class="btn btn-outline-warning" onclick="window.location.href = 'resultAnalysis_marks.php';">Individual Marks</button>
            <button class="btn btn-outline-warning" onclick="window.location.href = 'demo.html';">Subject Analysis</button>
        </div>
    <?php endif; ?>
    

    </center>
</body>
</html>
    
    
    
    
    
    
    
    
    
    
    