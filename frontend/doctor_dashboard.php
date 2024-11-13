<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get the current hour in 24-hour format
$current_hour = date("H");

// Determine the greeting based on the time of day
if ($current_hour >= 5 && $current_hour < 12) {
    $greeting = "Good Morning";
} elseif ($current_hour >= 12 && $current_hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - AI-Enhanced Patient Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo $greeting; ?> Dr.<?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
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
    <main id="content">
        <!-- Dynamic content will be loaded here -->
        <h2>Welcome to Your Doctor Dashboard</h2>
        <p>Select a section from the sidebar to manage your tasks.</p>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>

    <script src="doctor_dashboard.js"></script>  <!-- JS file for dynamic content loading -->
</body>
</html>
