<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection

// Delete Patient
if (isset($_GET['delete'])) {
    $username = $_GET['delete'];

    // First, delete all related medical records
    $stmt = $conn->prepare("DELETE FROM medical_records WHERE patient_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Then, delete the patient
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    header("Location: managePatients.php");  // Refresh page after deletion
    exit();
}

// Fetch all patients
$sql = "SELECT * FROM users WHERE role = 'patient'";  // Make sure to only fetch patients
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmDeletion(username) {
            if (confirm("Are you sure you want to delete this patient?")) {
                window.location.href = "managePatients.php?delete=" + encodeURIComponent(username);
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Manage Patients</h1>
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
        <h2>Patient Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Age</th>
                    <th>Medical History</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['medical_history']); ?></td>
                        <td>
                            <a href="update_patient.php?username=<?php echo urlencode($row['username']); ?>">Edit</a> |
                            <a href="javascript:void(0);" onclick="confirmDeletion('<?php echo urlencode($row['username']); ?>');">Delete</a>
                        </td>
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
$conn->close();
?>
