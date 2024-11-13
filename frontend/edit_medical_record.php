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

// Fetch the existing medical record
$stmt = $conn->prepare("SELECT * FROM medical_records WHERE record_id = ?");
$stmt->bind_param("i", $record_id);
$stmt->execute();
$record_result = $stmt->get_result();

if ($record_result->num_rows === 0) {
    echo "Medical record not found.";
    exit();
}

$record = $record_result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = trim($_POST['diagnosis']);
    $treatment = trim($_POST['treatment']);
    $notes = trim($_POST['notes']);

    // Basic validation
    if (empty($diagnosis) || empty($treatment)) {
        $error = "Diagnosis and Treatment fields are required.";
    } else {
        // Update the medical record
        $stmt_update = $conn->prepare("UPDATE medical_records SET diagnosis = ?, treatment = ?, record_notes = ? WHERE record_id = ?");
        $stmt_update->bind_param("sssi", $diagnosis, $treatment, $notes, $record_id);

        if ($stmt_update->execute()) {
            // Redirect back to manage_medical_records.php with a success message
            header("Location: manage_medical_records.php?success=1");
            exit();
        } else {
            $error = "Error updating medical record: " . $stmt_update->error;
        }

        $stmt_update->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medical Record - Doctor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Medical Record</h1>
    </header>

    <!-- Sidebar Navigation -->
    <nav>
        <ul>
        <li><a href="doctor_dashboard.html">Home</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li><a href="managePatients.php">Manage Patients</a></li>
            <li><a href="prescribeMedications.php">Prescribe Medications</a></li>
            <li><a href="manage_medical_records.php">Manage Medical Records</a></li>
            <li><a href="doctor_logout.php">Logout</a></li></li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <main>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="edit_medical_record.php?id=<?php echo $record_id; ?>" method="POST">
            <label for="diagnosis">Diagnosis:</label>
            <textarea id="diagnosis" name="diagnosis" rows="4" required><?php echo htmlspecialchars($record['diagnosis']); ?></textarea>

            <label for="treatment">Treatment:</label>
            <textarea id="treatment" name="treatment" rows="4" required><?php echo htmlspecialchars($record['treatment']); ?></textarea>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($record['record_notes']); ?></textarea>

            <button type="submit">Update Medical Record</button>
        </form>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>
