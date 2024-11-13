<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $patient = $_POST['patient'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $allergies = $_POST['allergies'];
    $symptoms = $_POST['symptoms'];
    $billing_amount = $_POST['billing_amount'];  // New billing amount
    $ai_suggestions = $_POST['ai_suggestions'];  // Hidden input for AI suggestions

    // Get medication data arrays
    $medications = $_POST['medications'];
    $dosages = $_POST['dosages'];
    $durations = $_POST['durations'];

    // Start database transaction
    $conn->begin_transaction();

    try {
        // Insert prescription info without medication details
        $stmt = $conn->prepare("INSERT INTO prescriptions (patient, age, gender, weight, allergies, symptoms, billing_amount, ai_suggestions) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssis", $patient, $age, $gender, $weight, $allergies, $symptoms, $billing_amount, $ai_suggestions);
        $stmt->execute();
        
        // Get the ID of the inserted prescription for linking medication records
        $prescription_id = $conn->insert_id;

        // Insert each medication entry
        $stmt_med = $conn->prepare("INSERT INTO prescription_medications (prescription_id, medication, dosage, duration) 
                                    VALUES (?, ?, ?, ?)");
        $stmt_med->bind_param("isss", $prescription_id, $medication, $dosage, $duration);

        // Loop through each medication and insert it into prescription_medications table
        foreach ($medications as $index => $medication) {
            $dosage = $dosages[$index];
            $duration = $durations[$index];
            $stmt_med->execute();
        }

        // Commit the transaction
        $conn->commit();
        echo "Prescription and billing details saved successfully.";
        
    } catch (Exception $e) {
        // Roll back the transaction if an error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close statements
    $stmt->close();
    $stmt_med->close();
}

// Close the database connection
$conn->close();
?>
