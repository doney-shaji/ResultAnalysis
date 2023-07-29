<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
    <script src="javascript.js"></script>
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
        form.form{
        text-align: left;
        font-family: Calibri;
        font-size: 20px;
        /* border: 1px solid black; */
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

    <div class="form">
        <!-- Your form elements -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
        
            <select name="exam_type" onchange='Checkexam_types(this.value);'> 
                <option>(pick a exam_type)</option>  
                <option>Series 1</option>
                <option>Series 2</option>
                <option value="ESE">ESE</option>
                <option value="Retest">Retest</option>
                <option value="others">others</option>
            </select>
            <select class="batch" name="batch" id="batch" onchange="">
                <option value="">(Select Batch)</option>
                <option value="2020-2024">2020-2024</option>
                <option value="2021-2025">2021-2025</option>
                <option value="2022-2026">2022-2026</option>
                <option value="2023-2027">2023-2027</option>
            </select><br>
            <input type="submit" name="submit" value="Retrieve Data">
        </form>
        
        <?php
        
        // Step 1: Connect to the MySQL database
        $servername = "localhost";
        $username = "root";
        $password = "3321185";
        $dbname = "result_analysis";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if (isset($_POST['submit'])) {

            // Assuming the names of the columns in the internal_exam table are 'program', 'semester', 'subject', 'exam_type', and 'batch'.
            $program = $_POST['program'];
            $semester = $_POST['semester'];
            $subject = $_POST['subject'];
            $examType = $_POST['exam_type'];
            $batch = $_POST['batch'];
            echo "<h4>Program: <small class='text-muted'>" . $program . " </small>| ";
            echo "Semester: <small class='text-muted'>" . $semester . " </small> | ";
            echo "Exam: <small class='text-muted'>" . $examType . " </small>| ";
            echo "Course: <small class='text-muted'>" . $subject . " </small>| ";
            echo "Batch: <small class='text-muted'>" . $batch . " </small></h4>";
            ?>
            <form id="pdfForm" method="POST" action="generate_pdf_module.php" target="_blank">
                <!-- Hidden input fields to store PHP variables -->
                <input type="hidden" name="exam_name" value="<?php echo isset($_POST['exam_type']) ? $_POST['exam_type'] : ''; ?>">
                <input type="hidden" name="ProgramID" value="<?php echo isset($_POST['program']) ? $_POST['program'] : ''; ?>">
                <input type="hidden" name="semester" value="<?php echo isset($_POST['semester']) ? $_POST['semester'] : ''; ?>">
                <input type="hidden" name="subject" value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : ''; ?>">
                <input type="hidden" name="batch" value="<?php echo isset($_POST['batch']) ? $_POST['batch'] : ''; ?>">

                <!-- Your "Generate PDF" button -->
                <button type="submit" class="btn btn-outline-secondary">Generate PDF</button>
            </form>
            <?php

            // Step 3: Retrieve data from the database based on the selected values
            $sql = "SELECT q.ModuleNo, q.QuestionNo
                    FROM question AS q
                    INNER JOIN internal_exam AS i ON q.lastid = i.examid
                    WHERE i.ProgramID = '$program'
                    AND i.Semester = '$semester'
                    AND i.CourseID = '$subject'
                    AND i.ExamName = '$examType'
                    AND i.Batch = '$batch'";
            $result = $conn->query($sql);
            $questionsByModule = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $ModuleNo = $row['ModuleNo'];
                    $questionNumber = $row['QuestionNo'];
                    
                    // Group questions by module number in a PHP array
                    if (!isset($questionsByModule[$ModuleNo])) {
                        $questionsByModule[$ModuleNo] = array();
                    }
                    
                    $questionsByModule[$ModuleNo][] = $questionNumber;
                }
            }

            // Step 3: Fetch marks obtained by students and group them by module number
            $sql = "SELECT m.QuestionNo, m.StudentID, SUM(m.MarksObtained) AS totalMarks
                    FROM marks AS m
                    INNER JOIN internal_exam AS i ON m.ExamID = i.ExamID
                    WHERE i.ProgramID = '$program'
                    AND i.Semester = '$semester'
                    AND i.CourseID = '$subject'
                    AND i.ExamName = '$examType'
                    AND i.Batch = '$batch'
                    GROUP BY m.QuestionNO, m.StudentID";
            $result = $conn->query($sql);
            $marksByModule = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $QuestionNumber = $row['QuestionNo'];
                    $student_id = $row['StudentID'];
                    $totalMarks = $row['totalMarks'];
                    
                    // Find the corresponding module number for the question
                    $ModuleNo = null;
                    foreach ($questionsByModule as $module => $questions) {
                        if (in_array($QuestionNumber, $questions)) {
                            $ModuleNo = $module;
                            break;
                        }
                    }
                    
                    if ($ModuleNo) {
                        // Store the total marks obtained by each student in each module
                        $marksByModule[$ModuleNo][$student_id] = isset($marksByModule[$ModuleNo][$student_id]) ? $marksByModule[$ModuleNo][$student_id] + $totalMarks : $totalMarks;
                    }
                }
            }

            // Step 4: Store both the percentage of each module for each student
            $modulePercentage = array();
            foreach ($marksByModule as $ModuleNo => $marks) {
                foreach ($marks as $student_id => $totalMarks) {
                    // Get the corresponding partid for the module from the question table
                    // Assuming you have a database connection as $conn
                    $selectPartIdSql = "SELECT q.PartID 
                                        FROM question AS q
                                        INNER JOIN internal_exam AS i ON q.LastID = i.ExamID
                                        WHERE i.ProgramID = ? 
                                        AND i.Semester = ? 
                                        AND i.CourseID = ? 
                                        AND i.ExamName = ? 
                                        AND i.Batch = ?
                                        AND q.ModuleNo = ?";

                    $selectPartIdStmt = $conn->prepare($selectPartIdSql);
                    $selectPartIdStmt->bind_param("ssssss", $program, $semester, $subject, $examType, $batch, $ModuleNo);
                    $selectPartIdStmt->execute();
                    $partIdResult = $selectPartIdStmt->get_result();
                    $totalMaxMarksForModule = 0; // Variable to store the maximum marks for the module

                    while ($partIdRow = $partIdResult->fetch_assoc()) {
                        $partId = $partIdRow['PartID'];

                        // Get the corresponding marks for each question from the internal_exam_part table
                        $selectMarksSql = "SELECT MarksEachQst, QstChoice FROM internal_exam_part WHERE PartID = ?";
                        $selectMarksStmt = $conn->prepare($selectMarksSql);
                        $selectMarksStmt->bind_param("s", $partId);
                        $selectMarksStmt->execute();
                        $marksResult = $selectMarksStmt->get_result();

                        $maxMarksForPart = 0; // Variable to store the maximum marks for the part

                        while ($marksRow = $marksResult->fetch_assoc()) {
                            $marksEachQst = $marksRow['MarksEachQst'];
                            $choice = $marksRow['QstChoice'];

                            if ($choice === 'No') {
                                $maxMarksForPart += $marksEachQst;
                            } else {
                                $maxMarksForPart += $marksEachQst / 2;
                            }
                        }

                        $totalMaxMarksForModule += $maxMarksForPart;
                    }
                    echo $totalMaxMarksForModule;
                    // Calculate the percentage for the module
                    $percentage = ($totalMarks / $totalMaxMarksForModule) * 100;

                    // Store the percentage and module number for each module for each student
                    if (!isset($modulePercentage[$student_id])) {
                        $modulePercentage[$student_id] = array(
                            $ModuleNo => array(
                                'ModuleNo' => $ModuleNo,
                                'percentage' => $percentage
                            )
                        );
                    } else {
                        // If data for the module already exists, update the percentage if needed
                        if (!isset($modulePercentage[$student_id][$ModuleNo]) || $percentage > $modulePercentage[$student_id][$ModuleNo]['percentage']) {
                            $modulePercentage[$student_id][$ModuleNo] = array(
                                'ModuleNo' => $ModuleNo,
                                'percentage' => $percentage
                            );
                        }
                    }
                }
            }
            // Step 5: Prepare the data for the table
            $tableData = array();
            foreach ($modulePercentage as $student_id => $modules) {
                $row = array('student_id' => $student_id);

                // Loop through each module and store its percentage in the row data
                foreach ($modules as $moduleNumber => $moduleData) {
                    $percentage = $moduleData['percentage'];
                    $row['Module ' . $moduleNumber] = $percentage . '%';

                    // Optionally, you can also determine the remarks based on which module has the highest percentage
                    if (!isset($row['HighestPercentage']) || $percentage > $row['HighestPercentage']) {
                        $row['HighestPercentage'] = $percentage;
                        $row['Remarks'] = 'Module ' . $moduleNumber . ' scored more';
                    }

                    // Determine if the current module is the weakest among all modules for this student
                    $isWeakestModule = true;
                    foreach ($modules as $otherModuleNumber => $otherModuleData) {
                        if ($moduleNumber !== $otherModuleNumber && $percentage >= $otherModuleData['percentage']) {
                            $isWeakestModule = false;
                            break;
                        }
                    }

                    // Set weakerModule value to 1 if this is the weakest module, otherwise set it to 0
                    $weaker_module_value = $isWeakestModule ? 1 : 0;

                    // Get the ExamID for this module using getExamIDFromDB() function
                    $examId = getExamIDFromDB($conn, $program, $semester, $batch, $examType, $subject);

                    // Insert the data into the student_module_analysis table using insertStudentModuleAnalysis() function
                    if ($examId !== null)
                    {insertStudentModuleAnalysis($conn, $student_id, $moduleNumber, $weaker_module_value, $examId);}
                }

                $tableData[] = $row;
            }

            // Step 6: Display the results in a table
            echo '<table border="1">';
            echo '<tr><th>Student ID</th>';
            foreach ($modulePercentage[array_key_first($modulePercentage)] as $moduleData) {
                echo '<th>Module ' . $moduleData['ModuleNo'] . '</th>';
            }
            echo '<th>Remarks</th></tr>';

            foreach ($tableData as $row) {
                echo '<tr>';
                echo '<td>' . $row['student_id'] . '</td>';
                foreach ($modulePercentage[array_key_first($modulePercentage)] as $moduleData) {
                    $moduleNumber = 'Module ' . $moduleData['ModuleNo'];
                    echo '<td>' . (isset($row[$moduleNumber]) ? $row[$moduleNumber] : 'N/A') . '</td>';
                }
                echo '<td>' . (isset($row['Remarks']) ? $row['Remarks'] : 'N/A') . '</td>';
                echo '</tr>';
            }
            echo '</table>';

        }
        // Function to retrieve the ExamID from internal_exam table based on provided values
        function getExamIDFromDB($conn, $program, $semester, $batch, $examType, $subject) {
            $selectExamIdSql = "SELECT ExamID FROM internal_exam WHERE ProgramID = ? AND Semester = ? AND Batch = ? AND ExamName = ? AND CourseID = ?";
            $selectExamIdStmt = $conn->prepare($selectExamIdSql);
            $selectExamIdStmt->bind_param("sssss", $program, $semester, $batch, $examType, $subject);
            $selectExamIdStmt->execute();
            $selectExamIdResult = $selectExamIdStmt->get_result();

            // Check if ExamID is retrieved successfully
            if ($selectExamIdResult->num_rows > 0) {
                $row_examId = $selectExamIdResult->fetch_assoc();
                return $row_examId['ExamID'];
            } else {
                echo "ExamID not found in internal_exam table for the provided criteria.";
                return null;
            }
        }

        // Function to insert data into the student_module_analysis table
        function insertStudentModuleAnalysis($conn, $student_id, $Module_Number, $Weaker_Module_Value, $examId) {
            // Check if the record already exists in the table
            $selectSql = "SELECT * FROM student_module_analysis WHERE StudentID = ? AND ModuleID = ? AND ExamID = ?";
            $selectStmt = $conn->prepare($selectSql);
            $selectStmt->bind_param("sss", $student_id, $Module_Number, $examId);
            $selectStmt->execute();
            $selectResult = $selectStmt->get_result();

            // If the record does not exist, then insert it
            if ($selectResult->num_rows === 0) {
                $insertSql = "INSERT INTO student_module_analysis (StudentID, ModuleID, weakerModule, ExamID) VALUES (?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("ssss", $student_id, $Module_Number, $Weaker_Module_Value, $examId);
                if ($insertStmt->execute() !== TRUE) {
                    $_SESSION['status'] = 'Error Inserting data' . $conn->error;
                }else{
                    $_SESSION['status'] = 'Data Inserted Succesfully';
                }
            } else {
                $_SESSION['status'] = 'Value already inserted';
            }
        }

       
   
        // Close the MySQL connection
        $conn->close();
        ?>
</body>

    