<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include your database connection

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_medical_records.php");
    exit();
}

$record_id = intval($_GET['id']);

// Optional: Verify that the record exists and belongs to a patient managed by this doctor
// Implement additional checks based on your application's logic

// Delete the medical record
$stmt_delete = $conn->prepare("DELETE FROM medical_records WHERE record_id = ?");
$stmt_delete->bind_param("i", $record_id);

if ($stmt_delete->execute()) {
    // Redirect back with a success message
    header("Location: manage_medical_records.php?deleted=1");
    exit();
} else {
    echo "Error deleting medical record: " . $stmt_delete->error;
}

$stmt_delete->close();
$conn->close();
?>
