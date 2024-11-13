<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection

$doctor = $_SESSION['username'];  // Logged-in doctor's username

// Check if the doctor has approved or rejected any appointments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['action'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action']; // Either 'approve' or 'reject'

    $new_status = ($action == 'approve') ? 'approved' : 'rejected';

    // Update the appointment status
    $update_sql = "UPDATE appointments SET approval_status = ? WHERE id = ? AND doctor = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sis', $new_status, $appointment_id, $doctor);
    $update_stmt->execute();
}

// Retrieve all appointments where the logged-in doctor is involved
$sql = "SELECT * FROM appointments WHERE doctor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor);
$stmt->execute();
$appointments_result = $stmt->get_result();

// Retrieve all patients for scheduling new appointments
$patients_sql = "SELECT username FROM users WHERE role = 'patient'";
$patients_result = $conn->query($patients_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's View Appointments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Your Appointments</h1>
    </header>

    <!-- Sidebar Navigation -->
    <nav>
        <ul>
            <li><a href="doctor_dashboard.php">Home</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li><a href="managePatients.php">Manage Patients</a></li>
            <li><a href="prescribeMedications.php">Prescribe Medications</a></li>
            <li><a href="manage_medical_records.php">Manage Medical Records</a></li>
            <li><a href="add_medical_record.php">Add Medical Records</a></li>
             <li><a href="doctor_logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <h2>Upcoming Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Booked On</th>
                    <th>Approval Status</th> <!-- Added to display the approval status -->
                    <th>Action</th> <!-- New column for approving/rejecting -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['approval_status']); ?></td> <!-- Display approval status -->
                        <td>
                            <?php if ($row['approval_status'] == 'pending'): ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                    <button type="submit" name="action" value="reject">Reject</button>
                                </form>
                            <?php else: ?>
                                <?php echo ucfirst($row['approval_status']); ?> <!-- Show if already approved/rejected -->
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Schedule New Appointment Section -->
        <h2>Schedule New Appointment</h2>
        <form action="schedule_appointment.php" method="POST">
            <label for="patient">Select Patient:</label>
            <select id="patient" name="patient" required>
                <?php while ($row = $patients_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['username']); ?>"><?php echo htmlspecialchars($row['username']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="reason">Reason for Appointment:</label>
            <input type="text" id="reason" name="reason" required>

            <button type="submit">Schedule Appointment</button>
        </form>
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
