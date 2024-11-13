<?php 
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to patient login
    exit();
}

require 'db.php';  // Include database connection

$patient_username = $_SESSION['username'];  // Logged-in patient's username

// Retrieve appointments booked by the patient
$sql_patient = "SELECT id, doctor, appointment_date, reason, created_at, status 
                FROM appointments 
                WHERE username = ? AND scheduled_by = 'patient'
                ORDER BY appointment_date ASC";
$stmt_patient = $conn->prepare($sql_patient);
if (!$stmt_patient) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt_patient->bind_param("s", $patient_username);
$stmt_patient->execute();
$appointments_patient = $stmt_patient->get_result();

// Retrieve appointments scheduled by the doctor for the patient
$sql_doctor = "SELECT id, doctor, appointment_date, reason, created_at, status 
               FROM appointments 
               WHERE username = ? AND scheduled_by = 'doctor'
               ORDER BY appointment_date ASC";
$stmt_doctor = $conn->prepare($sql_doctor);
if (!$stmt_doctor) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt_doctor->bind_param("s", $patient_username);
$stmt_doctor->execute();
$appointments_doctor = $stmt_doctor->get_result();



$stmt_patient->close();
$stmt_doctor->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments - Patient Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .section-title {
            margin-top: 40px;
        }
        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Appointments</h1>
    </header>

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

    <main>
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Appointment scheduled successfully!</p>
        <?php endif; ?>

        <!-- Appointments Booked by the Patient -->
        <section>
            <h2>Appointments You Have Booked</h2>
            <?php if ($appointments_patient->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $appointments_patient->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have not booked any appointments yet.</p>
            <?php endif; ?>
        </section>

        <!-- Appointments Scheduled by the Doctor -->
        <section>
            <h2>Appointments Scheduled by Your Doctor</h2>
            <?php if ($appointments_doctor->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Scheduled On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $appointments_doctor->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Your doctor has not scheduled any appointments for you.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>

