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

// Function to fetch data for a specific genre and date
function fetchData($genre, $date) {
    global $conn;

    $sql = "SELECT AVG(age) AS avg_age FROM responses WHERE genre=? AND submission_date=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $genre, $date);
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

// Function to perform simple linear regression and predict future ages
function predictFutureAges($data, $numPredictions, $currentDate) {
    // This is a placeholder, replace it with your actual linear regression logic
    // For simplicity, let's assume a linear relationship between time and age
    $coefficients = linearRegression($data);

    // Assuming linear regression coefficients [slope, intercept]
    $slope = $coefficients[0];
    $intercept = $coefficients[1];

    // Generate future dates based on the current date
    $futureDates = [];
    $currentTimestamp = strtotime($currentDate);

    for ($i = 1; $i <= $numPredictions; $i++) {
        $futureTimestamp = strtotime("+{$i} days", $currentTimestamp);
        $futureDates[] = date('Y-m-d', $futureTimestamp);
    }

    // Predict future ages (replace this with your actual prediction logic)
    $futureAges = [];
    foreach ($futureDates as $date) {
        $futureAges[] = round($slope * (count($data) + array_search($date, $futureDates)) + $intercept);
    }

    return $futureAges;
}

// Function to perform simple linear regression (replace this with your actual regression logic)
function linearRegression($data) {
    // Placeholder implementation, replace with your actual linear regression logic
    // This function should return the regression coefficients [slope, intercept]
    // You might use a machine learning library or implement your own regression
    return [2, 50]; // Replace with your regression coefficients
}

// Fetch unique genres
$genres = fetchGenres();

// Fetch data for different genres and dates
$data = [];
$dates = []; // Unique submission dates

// Fetch unique submission dates
$sqlDates = "SELECT DISTINCT submission_date FROM responses";
$resultDates = $conn->query($sqlDates);

if ($resultDates->num_rows > 0) {
    while ($rowDates = $resultDates->fetch_assoc()) {
        $dates[] = $rowDates['submission_date'];
    }
}

// Fetch data for each genre and date
foreach ($genres as $genre) {
    $genreData = [];

    foreach ($dates as $date) {
        $genreData[$date] = fetchData($genre, $date);
    }

    $data[] = [
        'genre' => $genre,
        'data' => $genreData,
    ];
}

// Predict future ages for each genre
$futureAges = [];

foreach ($data as $genreData) {
    $genre = $genreData['genre'];
    $ages = array_values($genreData['data']);
    $dateKeys = array_keys($genreData['data']);
    $currentDate = end($dateKeys); // Get the latest date from the existing data
    $predictedAges = predictFutureAges($ages, 5, $currentDate); // Predicting ages for the next 5 days

    $futureAges[$genre] = $predictedAges;
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
    <!-- Bar Graph for Genres -->
    <canvas id="chartGenres"></canvas>
</div>

<script>
    var genres = <?php echo json_encode($genres); ?>;
    var data = <?php echo json_encode($data); ?>;
    var futureAges = <?php echo json_encode($futureAges); ?>;

    var options = {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Genre'
                }
            },
            yActual: {
                position: 'left',
                title: {
                    display: true,
                    text: 'Actual Age'
                },
                beginAtZero: true
            },
            yPredicted: {
                position: 'right',
                title: {
                    display: true,
                    text: 'Predicted Age'
                },
                beginAtZero: true
            }
        }
    };

    // Create Bar Graph for Genres
    var ctxGenres = document.getElementById('chartGenres').getContext('2d');
    var chartGenres = new Chart(ctxGenres, {
        type: 'bar',
        data: {
            labels: [],  // Empty labels, as they will be dynamically set
            datasets: []
        },
        options: options
    });

    // Add actual data to the Bar Graph
    genres.forEach(genre => {
        var genreData = data.find(item => item.genre === genre);
        var genreColor = getRandomColor();

        chartGenres.data.datasets.push({
            label: `${genre} (Actual)`,
            data: Object.values(genreData.data),
            backgroundColor: genreColor,
            borderColor: genreColor,
            yAxisID: 'yActual', // Use the 'yActual' axis for actual data
            borderWidth: 1
        });
    });

    // Add predicted ages to the Bar Graph
    genres.forEach(genre => {
        var futureAgeData = futureAges[genre];
        var genreColor = getRandomColor();

        chartGenres.data.datasets.push({
            label: `${genre} (Predicted)`,
            data: futureAgeData,
            backgroundColor: 'rgba(0, 0, 0, 0.2)',
            borderColor: 'rgba(0, 0, 0, 1)',
            yAxisID: 'yPredicted', // Use the 'yPredicted' axis for predicted data
            borderWidth: 1
        });
    });

    // Set the labels (dates) dynamically
    chartGenres.data.labels = Object.keys(data[0].data);

    chartGenres.update(); // Update the chart with new data

    // Function to generate random colors for the Bar Graph
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script>

</body>
</html>
