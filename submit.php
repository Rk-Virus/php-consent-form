<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "acknowledgements"; 

// Connect to MySQL
$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $name = $_POST['student_name'];
    $class = $_POST['grade'];
    $roll_number = $_POST['roll'] ?? null;
    $section = $_POST['section'] ?? null;
    $parent_name = $_POST['guardian_name'];
    $number = $_POST['guardian_phone'];
    $alternate_number = $_POST['alt_contact'] ?? null;
    $conditions = $_POST['medical'] ?? null;
    $relationship = $_POST['relationship'] ?? null;
    $consent = isset($_POST['consent_travel']) ? 1 : 0;
    $date = $_POST['date'];

    // Commented out file upload code for now
    /*
    $parent_sign = null;
    if (isset($_FILES['parent_signature']) && $_FILES['parent_signature']['error'] == UPLOAD_ERR_OK) {
        $parent_sign = file_get_contents($_FILES['parent_signature']['tmp_name']);
    }

    $student_sign = null;
    if (isset($_FILES['student_signature']) && $_FILES['student_signature']['error'] == UPLOAD_ERR_OK) {
        $student_sign = file_get_contents($_FILES['student_signature']['tmp_name']);
    }
    */

    // Prepare statement (text fields only)
    $stmt = $conn->prepare("INSERT INTO trip_consent
        (`name`, `class`, `roll_number`, `section`, `parent_name`, `number`, `alternate_number`, `conditions`, `relationship`, `consent`, `date`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) die("Prepare failed: " . $conn->error);

    // Bind parameters (11 variables for 11 placeholders)
    $stmt->bind_param(
        "sssssssssis",
        $name,
        $class,
        $roll_number,
        $section,
        $parent_name,
        $number,
        $alternate_number,
        $conditions,
        $relationship,
        $consent,
        $date
    );

    if ($stmt->execute()) {
        echo "Record successfully created!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
