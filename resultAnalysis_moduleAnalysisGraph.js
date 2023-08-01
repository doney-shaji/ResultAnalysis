// Function to update the chart based on the selected values
function updateChart() {
    // Get the selected values from the selects
    var program = document.getElementById("program").value;
    var semester = document.getElementById("semester").value;
    var exam_type = document.getElementById("exam_type").value;
    var batch = document.getElementById("batch").value;
    var subject = document.getElementById("subject").value;

    // Send the selected values to the PHP script using AJAX
    $.ajax({
        type: "POST",
        url: "moduleFetch_data.php",
        data: {
            program: program,
            semester: semester,
            exam_type: exam_type,
            batch: batch,
            subject: subject,
        },
        dataType: "json",
        success: function (data) {
            // Update the chart with the received data
            moduleAnalysisChart.data.labels = data.moduleNumbers;
            moduleAnalysisChart.data.datasets[0].data = data.strongCounts;
            moduleAnalysisChart.data.datasets[1].data = data.weakCounts;
            moduleAnalysisChart.update();
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", error);
        },
    });
    // Create the chart
var moduleAnalysisChart = new Chart(document.getElementById('moduleAnalysisChart'), config);

}

// Add event listeners for select elements
document.getElementById("program").addEventListener("change", updateChart);
document.getElementById("semester").addEventListener("change", updateChart);
document.getElementById("exam_type").addEventListener("change", updateChart);
document.getElementById("batch").addEventListener("change", updateChart);
document.getElementById("subject").addEventListener("change", updateChart);

// Initial chart update
updateChart();

