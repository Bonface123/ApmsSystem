<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $patient_username = trim($_POST['patient_username']);
    $diagnosis = trim($_POST['diagnosis']);
    $treatment = trim($_POST['treatment']);
    $notes = trim($_POST['notes']);

    // Basic validation
    if (empty($patient_username) || empty($diagnosis) || empty($treatment)) {
        echo "Patient, Diagnosis, and Treatment fields are required.";
        exit();
    }

    // Verify that the selected user is a patient from the 'users' table
    $stmt_check = $conn->prepare("SELECT username FROM users WHERE username = ? AND role = 'patient'");
    $stmt_check->bind_param("s", $patient_username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo "Selected patient does not exist or is not a patient.";
        exit();
    }

    $stmt_check->close();

    // Insert the medical record into the database
    $stmt_insert = $conn->prepare("INSERT INTO medical_records (patient_username, diagnosis, treatment, record_notes) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("ssss", $patient_username, $diagnosis, $treatment, $notes);

    if ($stmt_insert->execute()) {
        // Redirect back to the dashboard with a success message
        header("Location: add_medical_record.php?success=1");
        exit();
    } else {
        echo "Error inserting medical record: " . $stmt_insert->error;
    }

    $stmt_insert->close();
    $conn->close();
} else {
    // If not a POST request, redirect to the add medical record page
    header("Location: add_medical_record.php");
    exit();
}
?>
