<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include your database connection

$doctor_username = $_SESSION['username'];

// Fetch all medical records for users (patients)
$sql_records = "SELECT mr.record_id, u.username AS patient_username, mr.diagnosis, mr.treatment, mr.record_notes, mr.created_at 
                FROM medical_records mr
                JOIN users u ON mr.patient_username = u.username  -- Using patient_username to join with users
                ORDER BY mr.created_at DESC";
$records_result = $conn->query($sql_records);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Medical Records - User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic table styling */
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

        /* Action buttons */
        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            text-decoration: none;
            color: #fff;
            border-radius: 3px;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .delete-btn {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Medical Records</h1>
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

    <!-- Main Content Area -->
    <main>
        <h2>All Medical Records</h2>
        <?php if ($records_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Patient Username</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Notes</th>
                        <th>Recorded On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($record = $records_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['record_id']); ?></td>
                            <td><?php echo htmlspecialchars($record['patient_username']); ?></td>
                            <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                            <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                            <td><?php echo htmlspecialchars($record['record_notes']); ?></td>
                            <td><?php echo htmlspecialchars($record['created_at']); ?></td>
                            <td>
                            <a href="add_medical_record.php" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to Add this record?');">Add</a>
                            <a href="edit_medical_record.php?id=<?php echo $record['record_id']; ?>" class="action-btn edit-btn">Edit</a>
                            <a href="delete_medical_record.php?id=<?php echo $record['record_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No medical records found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>
