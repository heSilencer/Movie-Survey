
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "survey_database";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the keys exist in $_POST before accessing them
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";

   // Check if a survey with the same email or name already exists
    $checkQuery = "SELECT * FROM responses WHERE email = ? OR name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $email, $name);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Survey with the same email or name already exists
        $checkStmt->close();
        $conn->close();
        echo '<script>alert("You have already submitted a survey with this email or name. You cannot take the survey again."); window.location.href = "index.html";</script>';
        exit();
    }


    $phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
    $address = isset($_POST["address"]) ? $_POST["address"] : "";
    $age = isset($_POST["age"]) ? $_POST["age"] : "";
    $role = isset($_POST["role"]) ? $_POST["role"] : "";
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $genre = isset($_POST["genre"]) ? $_POST["genre"] : "";

    if ($genre === "other") {
        // User selected "Other," use the custom genre value
        $customGenre = isset($_POST["otherGenreSpecify"]) ? $_POST["otherGenreSpecify"] : "";
        $genre = !empty($customGenre) ? $customGenre : "Other"; // Use "Other" if custom genre is empty
    }
    $subtitles = isset($_POST["subtitles"]) ? $_POST["subtitles"] : "";
    $element = isset($_POST["element"]) ? $_POST["element"] : "";
    $oftenwatch = isset($_POST["oftenwatch"]) ? $_POST["oftenwatch"] : "";
    $avoid = isset($_POST["avoid"]) ? $_POST["avoid"] : "";
    $device = isset($_POST["device"]) ? $_POST["device"] : "";
    $watching = isset($_POST["watching"]) ? $_POST["watching"] : "";
    $hour = isset($_POST["hour"]) ? $_POST["hour"] : "";
    $ratings = isset($_POST["ratings"]) ? $_POST["ratings"] : "";  
    $laughOutloud = isset($_POST["laughOutloud"]) ? $_POST["laughOutloud"] : "";    
    // Check if "prefer" key is set and is an array before using implode
    $feedback = isset($_POST["feedback"]) ? $_POST["feedback"] : "";

    // Prepare and execute SQL statement
    // ...

// Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO responses (name, email, phone, address, age, role, gender, genre,
    subtitles, element, oftenwatch, avoid, device, watching, hour, ratings, laughOutloud, feedback)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Update the order of parameters in bind_param to match the SQL query
    $stmt->bind_param("ssssssssssssssssss", $name, $email, $phone, $address, $age, $role, $gender, 
    $genre, $subtitles, $element, $oftenwatch, $avoid, $device, $watching,
    $hour, $ratings, $laughOutloud, $feedback);
    $stmt->execute();
    $stmt->close();

// ...


    // Close the database connection
    $conn->close();

    // JavaScript alert to show success message
    echo '<script>alert("Survey response submitted successfully!"); window.location.href = "index.html";</script>';
    
    // Redirect to the index.html page or any other page you want after submitting the form
    //header("Location: index.html");
    exit();
} else {
    echo "Invalid request!";
    exit();
}
?>
