<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to patient login
    exit();
}

require 'db.php';  // Include database connection

$patient_username = $_SESSION['username'];  // Logged-in patient's username

// Initialize variables
$success_message = '';
$error_message = '';

// Fetch all available doctors to populate the dropdown
$doctors = [];
$doctors_sql = "SELECT username  FROM users WHERE role = 'doctor'"; // Ensure 'full_name' column exists in 'users'
$doctors_result = $conn->query($doctors_sql);
if ($doctors_result) {
    while ($doctor = $doctors_result->fetch_assoc()) {
        $doctors[] = $doctor;
    }
} else {
    $error_message = "Failed to retrieve doctors. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $doctor_username = isset($_POST['doctor']) ? trim($_POST['doctor']) : '';
    $appointment_date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    $scheduled_by = 'patient';
    $created_at = date('Y-m-d H:i:s');

    // Basic validation
    if (empty($doctor_username) || empty($appointment_date) || empty($reason)) {
        $error_message = "All fields are required.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $appointment_date)) {
        $error_message = "Invalid date and time format.";
    } else {
        // Convert appointment_date to MySQL DATETIME format
        $appointment_date_mysql = date('Y-m-d H:i:s', strtotime($appointment_date));

        // Prepare the SQL statement with corrected column count
        $sql = "INSERT INTO appointments (username, doctor, appointment_date, reason, scheduled_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssss", $patient_username, $doctor_username, $appointment_date_mysql, $reason, $scheduled_by, $created_at);

            if ($stmt->execute()) {
                // Fetch patient's email
                $email_sql = "SELECT email FROM users WHERE username = ?";
                $stmt_email = $conn->prepare($email_sql);
                $stmt_email->bind_param("s", $patient_username);
                $stmt_email->execute();
                $stmt_email->bind_result($patient_email);
                $stmt_email->fetch();
                $stmt_email->close();

                // Fetch doctor's full name
                $doctor_sql = "SELECT username FROM users WHERE username = ?";
                $stmt_doctor = $conn->prepare($doctor_sql);
                $stmt_doctor->bind_param("s", $doctor_username);
                $stmt_doctor->execute();
                $stmt_doctor->bind_result($doctor_full_name);
                $stmt_doctor->fetch();
                $stmt_doctor->close();

                // Send confirmation email via Formspree
                $formspree_endpoint = 'https://formspree.io/f/xkgwarar'; // Replace with your actual Formspree form endpoint
                $email_data = [
                    'from' => 'no-reply@apms.com', // Your sender email
                    'to' => $patient_email,
                    'subject' => 'Appointment Confirmation',
                    'message' => "Dear {$patient_username},\n\nYour appointment with Dr. {$doctor_full_name} on {$appointment_date_mysql} has been successfully booked.\n\nReason: {$reason}\n\nThank you for using our system.\n\nBest regards,\nAPMS Team"
                ];

                // Initialize cURL for Formspree email
                $ch = curl_init($formspree_endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($email_data));
                curl_exec($ch);
                curl_close($ch);

                // Success: Redirect with success parameter
                header("Location: patient_view_appointments.php?success=1");
                exit();
            } else {
                $error_message = "Failed to schedule appointment: " . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        } else {
            $error_message = "Failed to prepare the appointment booking. Please try again.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment - Patient Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .appointment-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        .appointment-container h2 {
            text-align: center;
            color: #1e88e5;
            margin-bottom: 20px;
        }
        .appointment-container form {
            display: flex;
            flex-direction: column;
        }
        .appointment-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .appointment-container select,
        .appointment-container input,
        .appointment-container textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .appointment-container button {
            padding: 12px;
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .appointment-container button:hover {
            background-color: #1565c0;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1rem;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <h1>Schedule a New Appointment</h1>
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

    <main>
        <div class="appointment-container">
            <h2>Book an Appointment</h2>

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="message success">
                    Your appointment has been successfully booked!
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="patient_appointments.php" method="POST">
                <label for="doctor">Select Doctor:</label>
                <select id="doctor" name="doctor" required>
                    <option value="" disabled selected>Select a doctor</option>
                    <?php
                    if (!empty($doctors)) {
                        foreach ($doctors as $doctor) {
                            echo '<option value="' . htmlspecialchars($doctor['username']) . '">' . htmlspecialchars($doctor['full_name']) . ' (' . htmlspecialchars($doctor['username']) . ')</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No doctors available</option>';
                    }
                    ?>
                </select>

                <label for="date">Date & Time:</label>
                <input type="datetime-local" id="date" name="date" required min="<?php echo date('Y-m-d\TH:i'); ?>">

                <label for="reason">Reason for Appointment:</label>
                <textarea id="reason" name="reason" required></textarea>

                <button type="submit">Book Appointment</button>
            </form>
        </div>
    </main>
</body>
</html>
