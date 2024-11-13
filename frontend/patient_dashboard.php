<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get the current hour to personalize the greeting
$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour >= 12 && $hour < 18) {
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
    <title>Patient Dashboard - AI-Enhanced Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo $greeting . ', ' . htmlspecialchars($_SESSION['username']) . '!'; ?></h1>
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
        <section class="intro">
            <h2>Welcome to Your Health Dashboard</h2>
            <p style="text-align: center;">Access your medical records, schedule appointments, view prescriptions, and manage billing all in one place.</p>
        </section>

        <section class="features">
            <div class="feature">
                <h3>Medical Records</h3>
                <p>View and manage your medical history. Our AI-driven system provides easy-to-understand descriptions of medical terms.</p>
                <a href="records.php" class="btn">View Records</a>
            </div>
            <div class="feature">
                <h3>Schedule Appointment</h3>
                <p>Book appointments with your healthcare providers conveniently.</p>
                <a href="patient_appointments.php" class="btn">Book Now</a>
            </div>
            <div class="feature">
                <h3>View Appointments</h3>
                <p>Check upcoming appointments, including both those you've scheduled and those booked by your doctor.</p>
                <a href="patient_view_appointments.php" class="btn">View Appointments</a>
            </div>
            <div class="feature">
                <h3>Prescriptions</h3>
                <p>View your current and past prescriptions.</p>
                <a href="prescriptions.php" class="btn">View Prescriptions</a>
            </div>
            <div class="feature">
                <h3>Billing</h3>
                <p>Manage your billing information and view payment history.</p>
                <a href="view_billing.php" class="btn">Manage Billing</a>
            </div>
        </section>

        <section>
    <h2>We Value Your Feedback</h2>
    <form action="https://formspree.io/f/xkgwarar" method="POST">
        <label for="feedback">Your Feedback:</label>
        <textarea id="feedback" name="feedback" rows="4" required></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
</section>

        
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>
