<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM login_db
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
<!-- <script src="https://use.fontawesome.com/f59bcd8580.js"></script> -->
    <div class="container">
        <div class="row m-5 no-gutters shadow-lg">
            <div class="col-md-6 d-none d-md-block">
                <img src="illustration-isometric-concept-data-analysis-of-investment-business-company-free-vector.jpg" class="img-fluid" style="min-height:100%;" />
            </div>
            <div class="col-md-6 bg-white p-5">
                <h3 class="pb-3">Login</h3>
                <?php if ($is_invalid): ?>
                    <em>Invalid login</em>
                <?php endif; ?>
                <div class="form-style">
                    <form method="post">
                        <div class="form-group pb-3">
                            <input type="email" placeholder="Email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                        </div>
                        <div class="form-group pb-3">
                            <input type="password" placeholder="Password" class="form-control" name="password" id="password">
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <input name="" type="checkbox" value="" /> <span class="pl-2 font-weight-bold">Remember Me</span>
                            </div>
                           
                        </div>
                        <div class="pb-2">
                            <button type="submit" class="btn btn-dark w-100 font-weight-bold mt-2">Submit</button>
                        </div>
                        <div class="d-flex align-items-center justify-content-between"><a href="signup.html">Or Sign Up</a></div>
                    </form>

            </div>
        </div>
    </div>
</body>
</html>








