<?php

require_once('database.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
        width: 250px;
        }
        input[type="submit"]{
        width: 77px;
        height: 27px;
        position: relative;left: 180px;
        }
        form{
        text-align: left;
        font-family: Calibri;
        font-size: 20px;
        border: 1px solid black;
        width: 900px;
        margin: 30px;
        padding: 30px;
        }
        label{
        display:inline-block;
        width:200px;
        margin-right:30px;
        text-align:right;
        }
    </style>
</head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Result_analysis/index.php">RESULT ANALYSIS</a>
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
            <!-- <form class="d-flex">
                <?php if (isset($user)): ?>
                    <a href="logout.php" class="btn btn-outline-danger">Log out</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-danger">Log in</a>
                <?php endif; ?>
            </form> -->
        </div>
    </div>
</nav>
<center>
    <div class="container">
    <form id="uploadForm" method="POST" action="resultAnalysis_studentProcess.php" enctype="multipart/form-data">
    <h2>Student List Entry</h2>
    <label for="program">Program: </label>
    <select name="program" id="program">
            <option value="">(Select PROGRAM)</option>
            <option value="AEI">APPLIED ELECTRONICS AND INSTRUMENTATION ENGINEERING</option>
            <option value="AD">ARTIFICIAL INTELLIGENCE AND DATA SCIENCE</option>
            <option value="CE">CIVIL ENGINEERING</option>
            <option value="CSE">COMPUTER SCIENCE AND ENGINEERING</option>
            <option value="EEE">ELECTRICAL AND ELECTRONICS ENGINEERING</option>
            <option value="ECE">ELECTRONICS AND COMMUNICATION ENGINEERING</option>
            <option value="IT">INFORMATION TECHNOLOGY</option>
            <option value="ME">MECHANICAL ENGINEERING</option>
        </select><br>
        <label for="semester">Semester: </label>
        <select class="semester" name="semester" id="semester">
            <option value="">(Select Semester)</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
            <option value="S4">S4</option>
            <option value="S5">S5</option>
            <option value="S6">S6</option>
            <option value="S7">S7</option>
            <option value="S8">S8</option>
        </select><br>
        <label for="batch">Batch: </label>
        <select class="batch" name="batch" id="batch" onchange="">
            <option value="">(Select Batch)</option>
            <option value="2020-2024">2020-2024</option>
            <option value="2021-2025">2021-2025</option>
            <option value="2022-2026">2022-2026</option>
            <option value="2023-2027">2023-2027</option>
        </select><br>
        <label>Upload Student List: </label>
        <input type="file" name="excel_file" accept=".xls, .xlsx">
        <br>
        <br>
        <input type="submit" name="submit_button" value="submit_button" id="submitButton">
        <br>
        <br>
        <br>
        <table>
            <caption>Student List Format</caption>
            <tr>
                <td span colspan="4"><h5>The Excel to be Uploaded should be of the following format:</h5></td>
            </tr>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>UID</th>
                <th>Ret Number</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Student A</td>
                <td>U2000000</td>
                <td>RET20000</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Student B</td>
                <td>U2000000</td>
                <td>RET20000</td>
            </tr>
        </table>
        <br>
    </form>
    </div>
    </center>
    <!-- Add the JavaScript code outside the form -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("uploadForm");
        const submitButton = document.getElementById("submitButton");

        submitButton.addEventListener("click", function (event) {
            event.preventDefault();

            const program = document.getElementById("program").value;
            const semester = document.getElementById("semester").value;
            const batch = document.getElementById("batch").value;

            if (!program || !semester || !batch) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please select Program, Semester, and Batch before submitting.",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK",
                });
            } else {
                // Display the confirmation dialog
                Swal.fire({
                    icon: "question",
                    title: "Are you sure?",
                    text: "You are about to submit the form with the selected options:\n\n" +
                        "Program: " + program + "\n" +
                        "Semester: " + semester + "\n" +
                        "Batch: " + batch,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, submit it!",
                    cancelButtonText: "No, change options",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
<?php
$query = "SELECT DISTINCT ProgramID, Semester, Batch FROM student";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo ' <br>
    <table>
        <caption>Student List Entered</caption>
        <tr>
            <th>ProgramID</th>
            <th>Semester</th>
            <th>Batch</th>
        </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['ProgramID'] . '</td>';
        echo '<td>' . $row['Semester'] . '</td>';
        echo '<td>' . $row['Batch'] . '</td>';
        echo '</tr>';
    }

    echo '</table><br>';
} else {
    echo ' <br>
    <table>
        <caption>Student List Entered</caption>
        <tr>
            <th>No Student List Uploaded</th>
        </tr>';
    echo '</table><br>';
}
?>

</body>
</html>