<?php
$servername = "ble5yqb2cqx9wfu6iimn-mysql.services.clever-cloud.com";
$username = "u2waladsf1crcwtx";
$password = "1TJgJ2X2x9HMV92Hjkm0";
$dbname = "ble5yqb2cqx9wfu6iimn";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to the MySQL database";
// Rest of your code goes here...

// Close the database connection
$conn->close();
?>
