<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection

$doctor = $_SESSION['username'];  // Logged-in doctor's username

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['patient'];  // Get selected patient's username
    $appointment_date = $_POST['date'];  // Get selected date
    $reason = $_POST['reason'];  // Get reason for appointment
    $scheduled_by = 'doctor';  // Indicate that this was scheduled by the doctor

    // Insert the new appointment into the database
    $sql = "INSERT INTO appointments (username, doctor, appointment_date, reason, scheduled_by) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $doctor, $appointment_date, $reason, $scheduled_by);

    if ($stmt->execute()) {
        echo "Appointment scheduled successfully!";
        header("Location: view_appointments.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
