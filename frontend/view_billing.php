<?php
session_start();

// Check if the user is logged in and is a patient
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$patient_username = $_SESSION['username'];

// Fetch the patient's user_id based on the session username
$sql_user = "SELECT id FROM users WHERE username = '$patient_username' AND role = 'patient'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['id'];

    // Retrieve billing records associated with this user_id
    $sql_billing = "SELECT * FROM billing_info WHERE user_id = '$user_id'";
    $result_billing = $conn->query($sql_billing);

    if ($result_billing->num_rows > 0) {
        
        // Display the records
        echo "<h1>Financial Records for $patient_username</h1>";
        echo "<table>
                <tr>
                    <th>Patient Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>";
        while ($row = $result_billing->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['patient_name']}</td>
                    <td>ksh{$row['amount']}</td>
                    <td>{$row['date']}</td>
                    <td class='".($row['status'] == 'Paid' ? 'paid' : 'pending')."'>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No billing records found.</p>";
    }
} else {
    echo "<p>Patient not found.</p>";
}

$conn->close();
?>
<link rel="stylesheet" href="styles.css">

<!-- Internal CSS Styling -->
<style>
    /* General reset for margin and padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        line-height: 1.6;
        padding: 20px;
    }

    /* Heading Styles */
    h1 {
        text-align: center;
        color: #007bff;
        margin-bottom: 20px;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    td {
        background-color: #fff;
    }

    /* Status styling */
    td.paid {
        color: #28a745;
        font-weight: bold;
    }

    td.pending {
        color: #ffc107;
        font-weight: bold;
    }

    /* Table Hover Effect */
    tr:hover {
        background-color: #f1f1f1;
    }

    /* Alert styling */
    p {
        color: #721c24;
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
    }
</style>

     <!-- Navigation Bar -->
     <nav>
    <ul>
        <li><a href="patient_dashboard.php">Home</a></li>
        <li><a href="patient_appointments.php">Schedule Appointment</a></li>
        <li><a href="patient_view_appointments.php">View Appointments</a></li> <!-- Combined View -->
        <li><a href="prescriptions.php">View Prescriptions</a></li>
        <li><a href="view_billing.php">view Billing</a></li>
        <li><a href="records.php">Medical Records</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
