<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';  // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_SESSION['username'];  // Logged-in user's username
    $doctor = $_POST['doctor'];
    $appointment_date = $_POST['date'];
    $reason = $_POST['reason'];

    // Validate inputs
    if (empty($doctor) || empty($appointment_date) || empty($reason)) {
        echo "All fields are required!";
        exit();
    }

    // Insert appointment into database
    $stmt = $conn->prepare("INSERT INTO appointments (username, doctor, appointment_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $doctor, $appointment_date, $reason);

    if ($stmt->execute()) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
