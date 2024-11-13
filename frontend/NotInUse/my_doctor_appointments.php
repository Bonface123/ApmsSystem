<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_login.php");
    exit();
}

require 'db.php';  // Include database connection

$patient = $_SESSION['username'];  // Logged-in patient's username

// Retrieve all appointments where the logged-in patient is involved (either booked by the patient or the doctor)
$sql = "SELECT * FROM appointments WHERE username = ? OR (doctor = ? AND scheduled_by = 'doctor')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $patient, $patient);
$stmt->execute();
$appointments_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient's View Appointments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Your Appointments</h1>
    </header>

    
    <!-- Navigation Bar -->
    <nav>
        <ul>
        <li><a href="index.html">Home</a></li>
            <li><a href="patient_appointments.php">Schedule Appointment</a></li>
            <li><a href="prescriptions.php">View Prescriptions</a></li>
            <li><a href="billing.php">Manage Billing</a></li>
            <li><a href="records.php">Medical Records</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <!-- Main Content -->
    <main>
        <h2>Your Upcoming Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Booked On</th>
                    <th>Booked By</th> <!-- Showing whether doctor or patient booked -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                        <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo ($row['scheduled_by'] == 'doctor') ? 'Doctor' : 'You'; ?></td> <!-- Correctly show who scheduled -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
