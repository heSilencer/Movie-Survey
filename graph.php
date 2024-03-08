<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "survey_database";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch unique genres from the database
function fetchGenres() {
    global $conn;

    $sql = "SELECT DISTINCT genre FROM responses";
    $result = $conn->query($sql);

    $genres = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genres[] = $row['genre'];
        }
    }

    return $genres;
}

// Fetch unique genres
$genres = fetchGenres();

// Function to fetch data for a specific role and genre
function fetchData($role, $genre) {
    global $conn;

    $sql = "SELECT AVG(age) AS avg_age FROM responses WHERE role=? AND genre=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $role, $genre);
    $stmt->execute();
    $result = $stmt->get_result();

    $avgAge = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $avgAge = $row['avg_age'];
    }

    $stmt->close();

    return $avgAge;
}

// Fetch data for different roles and genres
$data = [];

foreach ($genres as $genre) {
    $data[] = [
        'genre' => $genre,
        'student' => fetchData('student', $genre),
        'teacher' => fetchData('teacher', $genre),
        'job' => fetchData('job', $genre),
    ];
}

// Function to fetch unique subtitles from the database
function fetchSubtitles() {
    global $conn;

    $sql = "SELECT DISTINCT subtitles FROM responses";
    $result = $conn->query($sql);

    $subtitles = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subtitles[] = $row['subtitles'];
        }
    }

    return $subtitles;
}

// Fetch unique subtitles
$subtitles = fetchSubtitles();

// Function to fetch data for a specific role and subtitle
function fetchDataForSubtitles($role, $subtitle) {
    global $conn;

    $sql = "SELECT AVG(age) AS avg_age FROM responses WHERE role=? AND subtitles=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $role, $subtitle);
    $stmt->execute();
    $result = $stmt->get_result();

    $avgAge = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $avgAge = $row['avg_age'];
    }

    $stmt->close();

    return $avgAge;
}

// Fetch data for different roles and subtitles
$subtitlesData = [];

foreach ($subtitles as $subtitle) {
    $subtitlesData[] = [
        'subtitle' => $subtitle,
        'student' => fetchDataForSubtitles('student', $subtitle),
        'teacher' => fetchDataForSubtitles('teacher', $subtitle),
        'job' => fetchDataForSubtitles('job', $subtitle),
    ];
}

// Function to fetch unique devices from the database
function fetchDevices() {
    global $conn;

    $sql = "SELECT DISTINCT device FROM responses";
    $result = $conn->query($sql);

    $devices = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $devices[] = $row['device'];
        }
    }

    return $devices;
}

// Fetch unique devices
$devices = fetchDevices();

// Function to fetch data for a specific role and device
function fetchDataForDevice($role, $device) {
    global $conn;

    $sql = "SELECT AVG(age) AS avg_age FROM responses WHERE role=? AND device=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $role, $device);
    $stmt->execute();
    $result = $stmt->get_result();

    $avgAge = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $avgAge = $row['avg_age'];
    }

    $stmt->close();

    return $avgAge;
}

// Fetch data for different roles and devices
$deviceData = [];

foreach ($devices as $device) {
    $deviceData[] = [
        'device' => $device,
        'student' => fetchDataForDevice('student', $device),
        'teacher' => fetchDataForDevice('teacher', $device),
        'job' => fetchDataForDevice('job', $device),
    ];
}

// Close the database connection
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

<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="graph.php">Charts</a></li>
        <li><a href="prediction.php">Prediction</a></li>
    </ul>
</nav>

<div style="width: 80%; margin: auto;">
    <!-- Chart for Genres -->
    <canvas id="chartGenres"></canvas>

    <!-- Chart for Subtitles -->
    <canvas id="chartSubtitles"></canvas>

    <!-- Chart for Devices -->
    <canvas id="chartDevices"></canvas>
</div>

<script>
    var genres = <?php echo json_encode($genres); ?>;
    var data = <?php echo json_encode($data); ?>;
    var subtitles = <?php echo json_encode($subtitles); ?>;
    var subtitlesData = <?php echo json_encode($subtitlesData); ?>;
    var devices = <?php echo json_encode($devices); ?>;
    var deviceData = <?php echo json_encode($deviceData); ?>;

    var options = {
        scales: {
            y: {
                beginAtZero: false,
                min: 1,
                max: 100,
                title: {
                    display: true,
                    text: 'Average Age'
                }
            }
        }
    };

    // Create chart for Genres
    var ctxGenres = document.getElementById('chartGenres').getContext('2d');
    var chartGenres = new Chart(ctxGenres, {
        type: 'bar',
        data: {
            labels: genres,
            datasets: [
                {
                    label: 'Student Average Age',
                    data: data.map(item => item.student),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Teacher Average Age',
                    data: data.map(item => item.teacher),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Job Average Age',
                    data: data.map(item => item.job),
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    borderColor: 'rgba(255, 205, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: options
    });

    // Create chart for Subtitles
    var ctxSubtitles = document.getElementById('chartSubtitles').getContext('2d');
    var chartSubtitles = new Chart(ctxSubtitles, {
        type: 'bar',
        data: {
            labels: subtitles,
            datasets: [
                {
                    label: 'Student Average Age',
                    data: subtitlesData.map(item => item.student),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Teacher Average Age',
                    data: subtitlesData.map(item => item.teacher),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Job Average Age',
                    data: subtitlesData.map(item => item.job),
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    borderColor: 'rgba(255, 205, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: options
    });

    // Create chart for Devices
    var ctxDevices = document.getElementById('chartDevices').getContext('2d');
    var chartDevices = new Chart(ctxDevices, {
        type: 'bar',
        data: {
            labels: devices,
            datasets: [
                {
                    label: 'Student Average Age',
                    data: deviceData.map(item => item.student),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Teacher Average Age',
                    data: deviceData.map(item => item.teacher),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Job Average Age',
                    data: deviceData.map(item => item.job),
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    borderColor: 'rgba(255, 205, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: options
    });
</script>
</body>
</html>
