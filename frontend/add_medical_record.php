<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include your database connection

$doctor_username = $_SESSION['username'];

// Initialize $success to false
$success = false;

// Check if a success message should be displayed
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = true;
    header("Location: manage_medical_records.php");
}

// Fetch all patients managed by the doctor from the 'users' table
$sql_patients = "SELECT username  FROM users WHERE role = 'patient'";
$stmt_patients = $conn->prepare($sql_patients);
if (!$stmt_patients) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt_patients->execute();
$patients_result = $stmt_patients->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Record - Doctor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
        /* Additional styling for better form appearance */
        form {
            max-width: 600px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        select, textarea, input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Add Medical Record</h1>
    </header>

    <!-- Sidebar Navigation -->
    <nav>
        <ul>
            <li><a href="doctor_dashboard.php">Home</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li><a href="managePatients.php">Manage Patients</a></li>
            <li><a href="prescribeMedications.php">Prescribe Medications</a></li>
            <li><a href="add_medical_record.php">Add Medical Record</a></li>
            <li><a href="manage_medical_records.php">Manage Medical Records</a></li>
            <li><a href="doctor_logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <main>
        <h2>Enter Medical Details for a Patient</h2>

        <?php if ($success): ?>
            <p class="success-message">Medical record added successfully!</p>
        <?php endif; ?>

        <form action="insert_medical_record.php" method="POST">
            <label for="patient">Select Patient:</label>
            <select id="patient" name="patient_username" required>
                <option value="">--Select Patient--</option>
                <?php while ($patient = $patients_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($patient['username']); ?>">
                        <?php echo htmlspecialchars($patient['username']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="diagnosis">Diagnosis:</label>
            <textarea id="diagnosis" name="diagnosis" rows="4" required></textarea>

            <label for="treatment">Treatment:</label>
            <textarea id="treatment" name="treatment" rows="4" required></textarea>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" rows="4"></textarea>

            <button type="submit">Add Medical Record</button>
        </form>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>
<?php
 
$stmt_patients->close();
$conn->close();
?>
