<?php
// Connect to your database (replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "survey_database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database for students
$sqlStudent = "SELECT genre, COUNT(*) AS count FROM responses WHERE role='student' GROUP BY genre";
$resultStudent = $conn->query($sqlStudent);

// Fetch data from the database for teachers
$sqlTeacher = "SELECT genre, COUNT(*) AS count FROM responses WHERE role='teacher' GROUP BY genre";
$resultTeacher = $conn->query($sqlTeacher);

// Fetch data from the database for jobs
$sqlJob = "SELECT genre, COUNT(*) AS count FROM responses WHERE role='job' GROUP BY genre";
$resultJob = $conn->query($sqlJob);

// Prepare data for Chart.js
$labels = [];
$dataStudent = [];
$dataTeacher = [];
$dataJob = [];

while ($row = $resultStudent->fetch_assoc()) {
    $labels[] = $row['genre'];
    $dataStudent[] = $row['count'];
}

while ($row = $resultTeacher->fetch_assoc()) {
    $dataTeacher[] = $row['count'];
}

while ($row = $resultJob->fetch_assoc()) {
    $dataJob[] = $row['count'];
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Data Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: auto;">
        <!-- Chart for Students -->
        <canvas id="chartStudent"></canvas>
        
        <!-- Chart for Teachers -->
        <canvas id="chartTeacher"></canvas>
        
        <!-- Chart for Jobs -->
        <canvas id="chartJob"></canvas>
    </div>

    <script>
        var dataStudent = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Student Responses',
                data: <?php echo json_encode($dataStudent); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        var dataTeacher = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Teacher Responses',
                data: <?php echo json_encode($dataTeacher); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var dataJob = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Job Responses',
                data: <?php echo json_encode($dataJob); ?>,
                backgroundColor: 'rgba(255, 205, 86, 0.2)',
                borderColor: 'rgba(255, 205, 86, 1)',
                borderWidth: 1
            }]
        };

        var options = {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Responses'
                    }
                }
            }
        };

        // Create charts for each role
        var ctxStudent = document.getElementById('chartStudent').getContext('2d');
        var chartStudent = new Chart(ctxStudent, {
            type: 'bar',
            data: dataStudent,
            options: options
        });

        var ctxTeacher = document.getElementById('chartTeacher').getContext('2d');
        var chartTeacher = new Chart(ctxTeacher, {
            type: 'bar',
            data: dataTeacher,
            options: options
        });

        var ctxJob = document.getElementById('chartJob').getContext('2d');
        var chartJob = new Chart(ctxJob, {
            type: 'bar',
            data: dataJob,
            options: options
        });
    </script>
</body>
</html>
