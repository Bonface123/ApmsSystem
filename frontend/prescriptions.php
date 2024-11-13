<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: patient_login.php"); // Redirect to patient login if not logged in
    exit();
}

require 'db.php';  // Include database connection

$username = $_SESSION['username'];  // Get the logged-in patient's username

// Fetch prescriptions and medications for the logged-in patient
$sql = "SELECT pm.medication, pm.dosage, pm.duration, p.prescribed_by 
        FROM prescriptions p
        JOIN prescription_medications pm ON p.id = pm.prescription_id
        WHERE p.patient = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Prescriptions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Your Prescriptions</h1>
    </header>
    <!-- Navigation Bar -->
    <nav>
    <ul>
        <li><a href="index.html">HomePage</a></li>
        <li><a href="patient_appointments.php">Schedule Appointment</a></li>
        <li><a href="patient_view_appointments.php">View Appointments</a></li> <!-- Combined View -->
        <li><a href="prescriptions.php">View Prescriptions</a></li>
        <li><a href="view_billing.php">view Billing</a></li>
        <li><a href="records.php">Medical Records</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

    <!-- Main Content -->
    <main>
        <h2>Prescriptions</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Duration(Days)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['medication']); ?></td>
                            <td><?php echo htmlspecialchars($row['dosage']); ?></td>
                            <td><?php echo htmlspecialchars($row['duration']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No prescriptions found.</p>
        <?php endif; ?>
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
