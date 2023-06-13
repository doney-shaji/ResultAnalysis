function populateSubjects(){
    console.log("here");
    var branchSelect = document.getElementById("program");
    var semesterSelect = document.getElementById("semester");
    var subjectSelect = document.getElementById("subject");
    
    var selectedBranch = branchSelect.value;
    var selectedSemester = semesterSelect.value;

    //Clear the subjects select option
    subjectSelect.innerHTML = "";
    //Define subjects for each branch and semester
    var subjects = {
        AEI: {
            S1: ["100908/MA100A LINEAR ALGEBRA AND CALCULUS","100908/CH900B ENGINEERING CHEMISTRY","100908/ME900C ENGINEERING GRAPHICS","100908/CO900D BASICS OF CIVIL AND MECHANICAL ENGINEERING","100908/EN100E LIFE SKILLS"],
            S2: ["100908/MA200A VECTOR CALCULUS, DIFFERENTIAL EQUATIONS AND TRANSFORMS","100906/PH900B ENGINEERING PHYSICS A","100908/CE900C ENGINEERING MECHANICS","100908/CO900F BASICS OF ELECTRICAL AND ELECTRONICS ENGINEERING","100908/EN200E PROFESSIONAL COMMUNICATION","100908/CO200G PROGRAMMING IN C"],
            
        },
        IT: {
            S1: ["100908/MA100A LINEAR ALGEBRA AND CALCULUS","100906/PH900B ENGINEERING PHYSICS A","100908/CE900C ENGINEERING MECHANICS","100908/CO900F BASICS OF ELECTRICAL AND ELECTRONICS ENGINEERING","100908/EN100E LIFE SKILLS"],
            S2: ["100908/MA200A VECTOR CALCULUS, DIFFERENTIAL EQUATIONS AND TRANSFORMS","100908/CH900B ENGINEERING CHEMISTRY","100908/ME900C ENGINEERING GRAPHICS","100908/CO900D BASICS OF CIVIL AND MECHANICAL ENGINEERING","100908/EN200E PROFESSIONAL COMMUNICATION","100908/CO200G PROGRAMMING IN C"],
            S3: ["100903/MA300A DISCRETE MATHEMATICAL STRUCTURES","100004/IT300B DATA STRUCTURES","100004/IT300C DIGITAL SYSTEM DESIGN","100004/IT300D PROBLEM SOLVING USING PYTHON","100908/CO900E DESIGN AND ENGINEERING","100908/CO300F SUSTAINABLE ENGINEERING"],
            S4: ["100004/IT400A PRINCIPLES OF OBJECT ORIENTED TECHNIQUES","100902/MA400B PROBABILITY, STATISTICS AND ADVANCED GRAPH THEORY","100004/IT400C COMPUTER ORGANIZATION","100004/IT400D DATABASE MANAGEMENT SYSTEMS","100908/EN900E PROFESSIONAL ETHICS","100908/ES400F CONSTITUTION OF INDIA"],
            S5: ["100004/IT500A WEB APPLICATION DEVELOPMENT","100004/IT500B OPERATING SYSTEM CONCEPTS","100004/IT500C DATA COMMUNICATION AND NETWORKING","100004/IT500D FORMAL LANGUAGES AND AUTOMATA THEORY","100902/IT500E MANAGEMENT FOR SOFTWARE ENGINEERS","100908/CO500F DISASTER MANAGEMENT"],
        }
    };

    //Main Logic happens here
    if (selectedBranch in subjects && selectedSemester in subjects[selectedBranch]) {
        var branchSemesterSubjects = subjects[selectedBranch][selectedSemester];
        for (var i = 0; i < branchSemesterSubjects.length; i++) {
          var option = document.createElement("option");
          option.text = branchSemesterSubjects[i];
          subjectSelect.appendChild(option);
        }
      } else {
        var option = document.createElement("option");
        option.text = "No subjects available";
        subjectSelect.appendChild(option);
      }
}
// Define a JavaScript function to display the corresponding number of input fields
function displayInputFields() {
  var numberInput = document.getElementById("number-of-parts").value;
  var inputContainer = document.getElementById("input-container");

  // Clear any previously displayed input fields
  inputContainer.innerHTML = "";

  // Display the specified number of input fields
  for (var i = 0; i < numberInput; i++) {

    //Creating the corresponding labels
    var label = document.createElement("label");
        label.textContent = "Marks for Part " + String.fromCharCode(65 + i) + ":"; //ASCII 65-A
        inputContainer.appendChild(label);

    // Creating the corresponding input fields
    var input = document.createElement("input");
    input.type = "text";
    input.name = "input" + (i + 1);
    var partname = "input" + (i + 1);
    input.placeholder = "Input " + (i + 1);
    inputContainer.appendChild(input);

    inputContainer.appendChild(document.createElement("br"));

    var label = document.createElement("label");
        label.textContent = "Number Of Questions in Part " + String.fromCharCode(65 + i) + ":"; //ASCII 65-A
        inputContainer.appendChild(label);

    // Creating the corresponding input fields
    var input = document.createElement("input");
    input.type = "number";
    input.name = "input" + (i + 1);
    input.placeholder = "Questions in " + (i + 1);
    inputContainer.appendChild(input);
    inputContainer.appendChild(document.createElement("br"));

    if (String.fromCharCode(65 + i) == 'A'){
        
    }
    else{
      var label = document.createElement("label");
      label.textContent = "Question Choice in Part " + String.fromCharCode(65 + i) + ":"; //ASCII 65-A
      inputContainer.appendChild(label);
      // Creating the "Yes" checkbox
      var checkboxYes = document.createElement("input");
      checkboxYes.type = "checkbox";
      checkboxYes.name = "Y";
      checkboxYes.value = "Yes";
      inputContainer.appendChild(checkboxYes);
      inputContainer.appendChild(document.createTextNode("Yes"));

      // Creating the "No" checkbox
      var checkboxNo = document.createElement("input");
      checkboxNo.type = "checkbox";
      checkboxNo.name = "N";
      checkboxNo.value = "No";
      inputContainer.appendChild(checkboxNo);
      inputContainer.appendChild(document.createTextNode("No"));

      inputContainer.appendChild(document.createElement("br"));
    }
  }
}