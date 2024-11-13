<?php
session_start();

// Ensure the user is logged in and is a finance department member
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'finance') {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve patient information from the form
    $patientName = $conn->real_escape_string($_POST['patient_name']); // Patient's name (unique identifier)
    $amount = $conn->real_escape_string($_POST['amount']);
    $date = $conn->real_escape_string($_POST['date']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Find the user_id based on the patient name
    $sql_user = "SELECT id FROM users WHERE username = '$patientName' AND role = 'patient'";
    $result_user = $conn->query($sql_user);
    
    if ($result_user->num_rows > 0) {
        // Patient found, retrieve their user_id
        $user = $result_user->fetch_assoc();
        $user_id = $user['id'];

        // Insert the new billing record with the user_id
        $sql = "INSERT INTO billing_info (user_id, patient_name, amount, date, status)
                VALUES ('$user_id', '$patientName', '$amount', '$date', '$status')";

        if ($conn->query($sql) === TRUE) {
            // Redirect back to the finance billing page with a success message
            header("Location: finance_billing.php?success=Record added successfully");
        } else {
            // Redirect back with an error message
            header("Location: finance_billing.php?error=" . urlencode("Error: " . $conn->error));
        }
    } else {
        // Patient not found, handle this case
        header("Location: finance_billing.php?error=" . urlencode("Patient not found."));
    }

    $conn->close();
}
?>
