<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient = $_POST['patient'];
    $medication = $_POST['medication'];
    $dosage = $_POST['dosage'];
    $duration = $_POST['duration'];
    $doctor = $_SESSION['username'];  // Logged-in doctor username

    // Optionally capture AI suggestions
    $ai_suggestions = isset($_POST['ai_suggestions']) ? $_POST['ai_suggestions'] : '';

    // Insert prescription into the database
    $sql = "INSERT INTO prescriptions (patient, doctor, medication, dosage, duration, ai_suggestions) 
            VALUES ('$patient', '$doctor', '$medication', '$dosage', '$duration', '$ai_suggestions')";

    if ($conn->query($sql) === TRUE) {
        echo "Prescription submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
